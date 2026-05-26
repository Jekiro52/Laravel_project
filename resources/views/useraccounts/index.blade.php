@extends('layouts.app')

@section('title', 'User Accounts')
@section('subtitle', 'View all admin, teacher, and student login accounts.')

@section('content')
@if ($userAccounts->isNotEmpty())
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Contact Number</th>
                        <th>Role</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($userAccounts as $account)
                        @php
                            $displayName = $account->students?->full_name ?? $account->display_name;
                        @endphp

                        <tr>
                            <td><span class="table-label">{{ $displayName }}</span></td>
                            <td>{{ $account->username }}</td>
                            <td>{{ $account->email }}</td>
                            <td>{{ $account->contact ?? 'No contact number' }}</td>
                            <td>
                                <span class="table-chip">{{ \Illuminate\Support\Str::headline($account->role) }}</span>
                            </td>
                            <td>
                                <span class="table-chip">{{ $account->is_active ? 'Active' : 'Inactive' }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="empty-card">
        <h3 class="h5 mb-1 text-dark">No user accounts found.</h3>
        <p class="mb-0">Create a student or teacher account to show it here.</p>
    </div>
@endif
@endsection
