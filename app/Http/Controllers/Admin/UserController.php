<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index()
    {
        return view('admin.user', [
            'departments' => Department::all(),
            'studyPrograms' => StudyProgram::all(),
            'users' => User::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:admin,kepala_upa,ketua_jurusan,ketua_prodi,wakil_direktur',
            'department_id' => 'nullable|exists:departments,id',
            'study_program_id' => 'nullable|exists:study_programs,id',
            'is_active' => 'required|boolean',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'department_id' => $request->department_id,
                'study_program_id' => $request->study_program_id,
                'is_active' => $request->is_active,
                'password' => bcrypt('password'), // password default
            ]);

            return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagagl menambahkan user:' . $e->getMessage());
        }
    }
}
