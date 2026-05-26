<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare request data before validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'first_name' => $this->normalize($this->input('first_name')),
            'middle_name' => $this->normalize($this->input('middle_name')),
            'last_name' => $this->normalize($this->input('last_name')),
            'address' => $this->normalize($this->input('address')),
            'contact' => $this->normalize($this->input('contact')),
            'email' => $this->normalizeLower($this->input('email')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $student = $this->route('student');
        $studentId = $student instanceof Student ? $student->id : $student;

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'min:5'],
            'contact' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'email' => ['required', 'email', 'max:255', Rule::unique('students', 'email')->ignore($studentId)],
            'degree_id' => ['required', 'integer', Rule::exists('degrees', 'id')],
        ];
    }

    /**
     * Get custom validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'contact.regex' => 'Contact may only contain numbers, spaces, plus, dash, and parentheses.',
        ];
    }

    private function normalize(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        $normalized = trim($value);

        return $normalized === '' ? null : $normalized;
    }

    private function normalizeLower(mixed $value): mixed
    {
        $normalized = $this->normalize($value);

        return is_string($normalized) ? strtolower($normalized) : $normalized;
    }
}
