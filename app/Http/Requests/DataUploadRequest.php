<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class DataUploadRequest extends FormRequest
{
    // public function authorize(): bool
    // {
    //     return Auth::check() && Auth::user()->isAdmin();
    // }
    public function authorize(): bool
    {
        $user = Auth::user();
        return $user && $user->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'data_file' => [
                'required',
                'file',
                'mimes:csv,txt,xlsx,xls',
                'max:10240', // 10MB max
            ],
            'scope' => 'required|in:class,study_program,department,campus',
            'scope_id' => 'nullable|integer|required_unless:scope,campus',
        ];
    }

    public function messages(): array
    {
        return [
            'data_file.required' => 'File data harus dipilih.',
            'data_file.mimes' => 'File harus berformat CSV atau Excel (.xlsx, .xls).',
            'data_file.max' => 'Ukuran file maksimal 10MB.',
            'scope.required' => 'Scope data harus dipilih.',
            'scope.in' => 'Scope data tidak valid.',
            'scope_id.required_unless' => 'ID scope harus diisi kecuali untuk scope campus.',
        ];
    }
}
