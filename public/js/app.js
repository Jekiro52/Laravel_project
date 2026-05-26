(function ($, bootstrap, window) {
    'use strict';

    if (! $ || ! bootstrap) {
        return;
    }

    const syncKey = 'student-degree-teacher-management:changed';
    const tabId = `${Date.now()}-${Math.random().toString(36).slice(2)}`;

    $(function () {
        setupAjax(window.studentAjaxConfig?.csrfToken || window.teacherAjaxConfig?.csrfToken || window.degreeAjaxConfig?.csrfToken);

        if (window.studentAjaxConfig && $('#studentsTableBody').length) {
            initStudents(window.studentAjaxConfig);
        }

        if (window.teacherAjaxConfig && $('#teachersTableBody').length) {
            initTeachers(window.teacherAjaxConfig);
        }

        if (window.degreeAjaxConfig && $('#degreesTableBody').length) {
            initDegrees(window.degreeAjaxConfig);
        }
    });

    function setupAjax(configToken) {
        const csrfToken = $('meta[name="csrf-token"]').attr('content') || configToken;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
        });
    }

    function initStudents(config) {
        const $tableBody = $('#studentsTableBody');
        const $emptyState = $('#studentsEmptyState');
        const $pagination = $('#studentsPagination');
        const viewModal = new bootstrap.Modal(document.getElementById('viewStudentModal'));
        const createModal = new bootstrap.Modal(document.getElementById('createStudentModal'));
        const editModal = new bootstrap.Modal(document.getElementById('editStudentModal'));
        let currentPage = 1;

        reloadStudents();

        $('#createStudentForm').on('submit', function (event) {
            event.preventDefault();
            saveForm($(this), function () {
                createModal.hide();
                $('#createStudentForm')[0].reset();
                reloadStudents(1);
            }, '#studentAlert', '.student-field', 'students');
        });

        $('#editStudentForm').on('submit', function (event) {
            event.preventDefault();
            saveForm($(this), function () {
                editModal.hide();
                reloadStudents();
            }, '#studentAlert', '.student-field', 'students');
        });

        $tableBody.on('click', '.js-view-student', function () {
            fetchRecord($(this), 'student', function (student) {
                $('#viewStudentModalLabel').text(student.full_name);
                $('#viewStudentName').text(student.full_name);
                $('#viewStudentEmail').text(student.email);
                $('#viewStudentContact').text(student.contact);
                $('#viewStudentDegree').text(student.degree_title);
                $('#viewStudentAddress').text(student.address);
                viewModal.show();
            }, '#studentAlert');
        });

        $tableBody.on('click', '.js-edit-student', function () {
            fetchRecord($(this), 'student', function (student) {
                const $form = $('#editStudentForm');
                clearFormErrors($form);
                $form.attr('action', student.update_url);
                fillFields($form, student, ['first_name', 'middle_name', 'last_name', 'address', 'contact', 'email', 'degree_id']);
                editModal.show();
            }, '#studentAlert');
        });

        $tableBody.on('click', '.js-delete-student', function () {
            deleteRecord($(this), 'student', '#studentAlert', function () {
                reloadStudents(currentPage);
            }, 'students');
        });

        onCollectionChanged(['students'], function () {
            reloadStudents(currentPage);
        });
        onCollectionChanged(['degrees'], function () {
            reloadStudents(currentPage);
            reloadStudentDegreeOptions(config.routes.degreesIndex);
        });
        clearModalForms('.modal');

        function reloadStudents(page = currentPage) {
            currentPage = page;

            loadCollection(config.routes.index, $tableBody, $pagination, 5, 'students', page, function (response) {
                renderStudents(response.students || [], $tableBody, $emptyState);
                renderPagination(response.pagination, $pagination, reloadStudents);
                currentPage = response.pagination?.current_page || currentPage;
            });
        }
    }

    function renderStudents(students, $tableBody, $emptyState) {
        if (! students.length) {
            $tableBody.html(emptyRow(5, 'No students found.'));
            showEmptyState($emptyState);
            return;
        }

        hideEmptyState($emptyState);
        $tableBody.html(students.map(function (student) {
            const fullName = escapeHtml(student.full_name);

            return `
                <tr>
                    <td><span class="table-label">${fullName}</span></td>
                    <td>${escapeHtml(student.email)}</td>
                    <td>${escapeHtml(student.contact)}</td>
                    <td><span class="table-chip">${escapeHtml(student.degree_title)}</span></td>
                    <td><div class="table-actions">${viewButton('js-view-student', student.show_url, fullName)}${editButton('js-edit-student', student.show_url, fullName)}${deleteButton('js-delete-student', student.delete_url, fullName)}</div></td>
                </tr>
            `;
        }).join(''));
    }

    function initTeachers(config) {
        const $tableBody = $('#teachersTableBody');
        const $emptyState = $('#teachersEmptyState');
        const $pagination = $('#teachersPagination');
        const viewModal = new bootstrap.Modal(document.getElementById('viewTeacherModal'));
        const createModal = new bootstrap.Modal(document.getElementById('createTeacherModal'));
        const editModal = new bootstrap.Modal(document.getElementById('editTeacherModal'));
        let currentPage = 1;

        reloadTeachers();

        $('#createTeacherForm').on('submit', function (event) {
            event.preventDefault();
            saveForm($(this), function () {
                createModal.hide();
                $('#createTeacherForm')[0].reset();
                reloadTeachers(1);
            }, '#teacherAlert', '.teacher-field', 'teachers');
        });

        $('#editTeacherForm').on('submit', function (event) {
            event.preventDefault();
            saveForm($(this), function () {
                editModal.hide();
                reloadTeachers();
            }, '#teacherAlert', '.teacher-field', 'teachers');
        });

        $tableBody.on('click', '.js-view-teacher', function () {
            fetchRecord($(this), 'teacher', function (teacher) {
                $('#viewTeacherModalLabel').text(teacher.display_name);
                $('#viewTeacherName').text(teacher.display_name);
                $('#viewTeacherUsername').text(teacher.username);
                $('#viewTeacherEmail').text(teacher.email);
                $('#viewTeacherContact').text(teacher.contact || 'No contact number');
                $('#viewTeacherStatus').text(teacher.status);
                viewModal.show();
            }, '#teacherAlert');
        });

        $tableBody.on('click', '.js-edit-teacher', function () {
            fetchRecord($(this), 'teacher', function (teacher) {
                const $form = $('#editTeacherForm');
                clearFormErrors($form);
                $form.attr('action', teacher.update_url);
                fillFields($form, teacher, ['first_name', 'middle_name', 'last_name', 'username', 'email', 'contact']);
                $form.find('[name="password"]').val('');
                $form.find('[name="is_active"][type="checkbox"]').prop('checked', Boolean(teacher.is_active));
                editModal.show();
            }, '#teacherAlert');
        });

        $tableBody.on('click', '.js-delete-teacher', function () {
            deleteRecord($(this), 'teacher', '#teacherAlert', function () {
                reloadTeachers(currentPage);
            }, 'teachers');
        });

        onCollectionChanged(['teachers'], function () {
            reloadTeachers(currentPage);
        });
        clearModalForms('.modal');

        function reloadTeachers(page = currentPage) {
            currentPage = page;

            loadCollection(config.routes.index, $tableBody, $pagination, 6, 'teachers', page, function (response) {
                renderTeachers(response.teachers || [], $tableBody, $emptyState);
                renderPagination(response.pagination, $pagination, reloadTeachers);
                currentPage = response.pagination?.current_page || currentPage;
            });
        }
    }

    function renderTeachers(teachers, $tableBody, $emptyState) {
        if (! teachers.length) {
            $tableBody.html(emptyRow(6, 'No teachers found.'));
            showEmptyState($emptyState);
            return;
        }

        hideEmptyState($emptyState);
        $tableBody.html(teachers.map(function (teacher) {
            const displayName = escapeHtml(teacher.display_name);

            return `
                <tr>
                    <td><span class="table-label">${displayName}</span></td>
                    <td>${escapeHtml(teacher.username)}</td>
                    <td>${escapeHtml(teacher.email)}</td>
                    <td>${escapeHtml(teacher.contact || 'No contact number')}</td>
                    <td><span class="table-chip">${escapeHtml(teacher.status)}</span></td>
                    <td><div class="table-actions">${viewButton('js-view-teacher', teacher.show_url, displayName)}${editButton('js-edit-teacher', teacher.show_url, displayName)}${deleteButton('js-delete-teacher', teacher.delete_url, displayName)}</div></td>
                </tr>
            `;
        }).join(''));
    }

    function initDegrees(config) {
        const $tableBody = $('#degreesTableBody');
        const $emptyState = $('#degreesEmptyState');
        const $pagination = $('#degreesPagination');
        const viewModal = new bootstrap.Modal(document.getElementById('viewDegreeModal'));
        const createModal = new bootstrap.Modal(document.getElementById('createDegreeModal'));
        const editModal = new bootstrap.Modal(document.getElementById('editDegreeModal'));
        let currentPage = 1;

        reloadDegrees();

        $('#createDegreeForm').on('submit', function (event) {
            event.preventDefault();
            saveForm($(this), function () {
                createModal.hide();
                $('#createDegreeForm')[0].reset();
                reloadDegrees(1);
            }, '#degreeAlert', '.degree-field', 'degrees');
        });

        $('#editDegreeForm').on('submit', function (event) {
            event.preventDefault();
            saveForm($(this), function () {
                editModal.hide();
                reloadDegrees();
            }, '#degreeAlert', '.degree-field', 'degrees');
        });

        $tableBody.on('click', '.js-view-degree', function () {
            fetchRecord($(this), 'degree', function (degree) {
                $('#viewDegreeModalLabel').text(degree.title);
                $('#viewDegreeTitle').text(degree.title);
                $('#viewDegreeCount').text(studentCountText(degree.students_count));
                $('#viewDegreeStudents').html(degree.students.length ? degree.students.map(function (student) {
                    return `<div class="list-group-item"><strong>${escapeHtml(student.full_name)}</strong><small class="d-block mt-1">${escapeHtml(student.email)}</small></div>`;
                }).join('') : '<div class="list-group-item text-muted">No students enrolled in this degree.</div>');
                viewModal.show();
            }, '#degreeAlert');
        });

        $tableBody.on('click', '.js-edit-degree', function () {
            fetchRecord($(this), 'degree', function (degree) {
                const $form = $('#editDegreeForm');
                clearFormErrors($form);
                $form.attr('action', degree.update_url);
                $form.find('[name="title"]').val(degree.title);
                editModal.show();
            }, '#degreeAlert');
        });

        $tableBody.on('click', '.js-delete-degree', function () {
            deleteRecord($(this), 'degree', '#degreeAlert', function () {
                reloadDegrees(currentPage);
            }, 'degrees');
        });

        onCollectionChanged(['degrees', 'students'], function () {
            reloadDegrees(currentPage);
        });
        clearModalForms('.modal');

        function reloadDegrees(page = currentPage) {
            currentPage = page;

            loadCollection(config.routes.index, $tableBody, $pagination, 3, 'degrees', page, function (response) {
                renderDegrees(response.degrees || [], $tableBody, $emptyState);
                renderPagination(response.pagination, $pagination, reloadDegrees);
                currentPage = response.pagination?.current_page || currentPage;
            });
        }
    }

    function renderDegrees(degrees, $tableBody, $emptyState) {
        if (! degrees.length) {
            $tableBody.html(emptyRow(3, 'No degrees found.'));
            showEmptyState($emptyState);
            return;
        }

        hideEmptyState($emptyState);
        $tableBody.html(degrees.map(function (degree) {
            const title = escapeHtml(degree.title);

            return `
                <tr>
                    <td><span class="table-label">${title}</span></td>
                    <td><span class="metric-chip">${studentCountText(degree.students_count)}</span></td>
                    <td><div class="table-actions">${viewButton('js-view-degree', degree.show_url, title)}${editButton('js-edit-degree', degree.show_url, title)}${deleteButton('js-delete-degree', degree.delete_url, title)}</div></td>
                </tr>
            `;
        }).join(''));
    }

    function loadCollection(url, $tableBody, $pagination, colspan, label, page, onSuccess) {
        $tableBody.html(emptyRow(colspan, `Loading ${label}...`));

        $.getJSON(url, { page: page }, onSuccess).fail(function () {
            $tableBody.html(`<tr><td colspan="${colspan}" class="text-center py-4 text-danger">Unable to load ${label}.</td></tr>`);
            $pagination.empty();
        });
    }

    function renderPagination(pagination, $pagination, onPageChange) {
        if (! pagination || pagination.last_page <= 1) {
            $pagination.empty();
            return;
        }

        const currentPage = pagination.current_page;
        const lastPage = pagination.last_page;
        const pages = paginationPages(currentPage, lastPage);
        const info = pagination.from && pagination.to
            ? `Showing ${pagination.from}-${pagination.to} of ${pagination.total}`
            : `Showing ${pagination.total} records`;

        $pagination.html(`
            <div class="small text-muted">${info}</div>
            <nav aria-label="Table pagination">
                <ul class="pagination pagination-sm mb-0">
                    ${pageButton('Previous', currentPage - 1, currentPage === 1)}
                    ${pages.map(function (page) {
                        return pageButton(page, page, false, page === currentPage);
                    }).join('')}
                    ${pageButton('Next', currentPage + 1, currentPage === lastPage)}
                </ul>
            </nav>
        `);

        $pagination.find('[data-page]').on('click', function () {
            onPageChange(Number($(this).data('page')));
        });
    }

    function paginationPages(currentPage, lastPage) {
        const firstPage = Math.max(1, currentPage - 2);
        const finalPage = Math.min(lastPage, firstPage + 4);
        const pages = [];

        for (let page = firstPage; page <= finalPage; page += 1) {
            pages.push(page);
        }

        return pages;
    }

    function pageButton(label, page, disabled = false, active = false) {
        return `
            <li class="page-item ${disabled ? 'disabled' : ''} ${active ? 'active' : ''}">
                <button type="button" class="page-link" ${disabled ? 'disabled' : `data-page="${page}"`}>${label}</button>
            </li>
        `;
    }

    function fetchRecord($button, key, onSuccess, alertSelector) {
        $button.prop('disabled', true);

        $.getJSON($button.data('url'), function (response) {
            onSuccess(response[key]);
        }).fail(function () {
            showAlert(alertSelector, `Unable to load ${key} details. Please try again.`, 'danger');
        }).always(function () {
            $button.prop('disabled', false);
        });
    }

    function saveForm($form, onSuccess, alertSelector, fieldSelector, syncCollection) {
        const $submitButton = $form.find('[type="submit"]');

        clearFormErrors($form);
        $submitButton.prop('disabled', true);

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            success: function (response) {
                showAlert(alertSelector, response.message || 'Record saved successfully.', 'success');
                onSuccess();
                notifyCollectionChanged(syncCollection);
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    showFormErrors($form, xhr.responseJSON.errors, fieldSelector);
                    return;
                }

                showAlert(alertSelector, xhr.responseJSON?.message || 'Unable to save the record. Please try again.', 'danger');
            },
            complete: function () {
                $submitButton.prop('disabled', false);
            },
        });
    }

    function deleteRecord($button, label, alertSelector, onSuccess, syncCollection) {
        const recordName = $button.data('record-name');

        showDeleteConfirmation(recordName, function () {
            $button.prop('disabled', true);

            $.ajax({
                url: $button.data('url'),
                type: 'DELETE',
                success: function (response) {
                    showAlert(alertSelector, response.message || 'Record deleted successfully.', 'success');
                    onSuccess();
                    notifyCollectionChanged(syncCollection);
                },
                error: function (xhr) {
                    showAlert(alertSelector, xhr.responseJSON?.message || `Unable to delete the ${label}. Please try again.`, 'danger');
                },
                complete: function () {
                    $button.prop('disabled', false);
                },
            });
        });
    }

    function showDeleteConfirmation(recordName, onConfirm) {
        const modalElement = ensureDeleteModal();
        const deleteModal = bootstrap.Modal.getOrCreateInstance(modalElement);
        const $modal = $(modalElement);

        $modal.find('#deleteConfirmName').text(recordName);
        $modal.find('#confirmDeleteButton')
            .off('click')
            .on('click', function () {
                deleteModal.hide();
                onConfirm();
            });

        deleteModal.show();
    }

    function ensureDeleteModal() {
        let modalElement = document.getElementById('deleteConfirmModal');

        if (modalElement) {
            return modalElement;
        }

        $('body').append(`
            <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content text-light" style="background: linear-gradient(155deg, rgba(18, 28, 48, 0.98), rgba(8, 14, 26, 0.98)); border: 1px solid rgba(99, 203, 255, 0.28); border-radius: 1rem; box-shadow: 0 18px 34px rgba(0, 0, 0, 0.42); overflow: hidden;">
                        <div class="modal-header" style="background: rgba(9, 17, 30, 0.72); border-bottom: 1px solid var(--line);">
                            <h2 class="modal-title h5" id="deleteConfirmModalLabel">Confirm Delete</h2>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-2" style="color: var(--muted);">This record will be permanently removed.</p>
                            <div style="padding: 0.85rem 1rem; border: 1px solid rgba(99, 203, 255, 0.34); border-radius: 0.85rem; background: rgba(79, 140, 255, 0.14); color: #c7e6ff;">
                                Delete <strong id="deleteConfirmName"></strong>?
                            </div>
                        </div>
                        <div class="modal-footer" style="border-top: 1px solid var(--line);">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="confirmDeleteButton">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        `);

        modalElement = document.getElementById('deleteConfirmModal');

        return modalElement;
    }

    function fillFields($form, record, fields) {
        fields.forEach(function (field) {
            $form.find(`[name="${field}"]`).val(record[field]);
        });
    }

    function showFormErrors($form, errors, fieldSelector) {
        Object.entries(errors).forEach(function ([field, messages]) {
            const $field = $form.find(`[name="${field}"]`);
            $field.addClass('is-invalid');
            $field.closest(fieldSelector).find('.invalid-feedback').first().text(messages[0]);
        });
    }

    function clearFormErrors($form) {
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('.invalid-feedback').text('');
    }

    function reloadStudentDegreeOptions(url) {
        if (! url) {
            return;
        }

        $.getJSON(url, function (response) {
            const degrees = response.degrees || [];

            $('select[name="degree_id"]').each(function () {
                const $select = $(this);
                const selectedValue = $select.val();

                $select.html('<option value="">Select Degree</option>' + degrees.map(function (degree) {
                    return `<option value="${degree.id}">${escapeHtml(degree.title)}</option>`;
                }).join(''));

                $select.val(selectedValue);
            });
        });
    }

    function notifyCollectionChanged(collection) {
        if (! collection) {
            return;
        }

        try {
            window.localStorage.setItem(syncKey, JSON.stringify({
                collection: collection,
                source: tabId,
                time: Date.now(),
            }));
        } catch (error) {
            // Cross-tab sync is a convenience; table updates in the current tab still work.
        }
    }

    function onCollectionChanged(collections, callback) {
        window.addEventListener('storage', function (event) {
            if (event.key !== syncKey || ! event.newValue) {
                return;
            }

            try {
                const change = JSON.parse(event.newValue);

                if (change.source !== tabId && collections.includes(change.collection)) {
                    callback(change);
                }
            } catch (error) {
                // Ignore malformed storage payloads from old tabs or manual edits.
            }
        });
    }

    function clearModalForms(selector) {
        $(selector).on('hidden.bs.modal', function () {
            const $form = $(this).find('form');
            clearFormErrors($form);
        });
    }

    function viewButton(className, url, title) {
        return `
            <button type="button" class="table-action table-action-view ${className}" data-url="${url}" title="View ${title}" aria-label="View ${title}">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </button>
        `;
    }

    function editButton(className, url, title) {
        return `
            <button type="button" class="table-action table-action-edit ${className}" data-url="${url}" title="Edit ${title}" aria-label="Edit ${title}">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m12 20 8-8"></path>
                    <path d="M18 6a2.12 2.12 0 1 1 3 3l-9 9-4 1 1-4 9-9Z"></path>
                </svg>
            </button>
        `;
    }

    function deleteButton(className, url, title) {
        return `
            <button type="button" class="table-action table-action-delete ${className}" data-url="${url}" data-record-name="${title}" title="Delete ${title}" aria-label="Delete ${title}">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 6h18"></path>
                    <path d="M8 6V4h8v2"></path>
                    <path d="m19 6-1 14H6L5 6"></path>
                    <path d="M10 11v6"></path>
                    <path d="M14 11v6"></path>
                </svg>
            </button>
        `;
    }

    function showAlert(selector, message, type) {
        $(selector)
            .removeClass('d-none alert-success alert-danger alert-info')
            .addClass(`alert-${type}`)
            .text(message);
    }

    function emptyRow(colspan, message) {
        return `<tr><td colspan="${colspan}" class="text-center py-4 text-muted">${message}</td></tr>`;
    }

    function showEmptyState($emptyState) {
        $emptyState.removeClass('d-none').addClass('d-flex');
    }

    function hideEmptyState($emptyState) {
        $emptyState.addClass('d-none').removeClass('d-flex');
    }

    function studentCountText(count) {
        const total = Number(count) || 0;

        return `${total} Student${total === 1 ? '' : 's'}`;
    }

    function escapeHtml(value) {
        return $('<div>').text(value ?? '').html();
    }
})(window.jQuery, window.bootstrap, window);
