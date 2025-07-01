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
        return view('admin.users.index', [
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

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }



    public function update(Request $request, $id)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,kepala_upa,ketua_jurusan,ketua_prodi,wakil_direktur',
            'is_active' => 'required|boolean',
            'department_id' => 'nullable|required_if:role,ketua_jurusan|exists:departments,id',
            'study_program_id' => 'nullable|required_if:role,ketua_prodi|exists:study_programs,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            // Find the user or fail
            $user = User::findOrFail($id);

            // Prepare the data for update
            $updateData = [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'role' => $validatedData['role'],
                'is_active' => $validatedData['is_active'],
                'department_id' => $validatedData['department_id'] ?? null,
                'study_program_id' => $validatedData['study_program_id'] ?? null,
            ];

            // Only update password if provided
            if (!empty($validatedData['password'])) {
                $updateData['password'] = bcrypt($validatedData['password']);
            }

            // Perform the update
            $user->update($updateData);

            return redirect()->route('admin.users.index')
                ->with('success', 'Data user berhasil diperbarui');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'User tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui user: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Hapus pengecekan posts() jika tidak diperlukan
            $user->delete();

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
