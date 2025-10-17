<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index()
    {
        return view('admin.prodi.index', [
            'departments' => Department::all(),
            'studyPrograms' => StudyProgram::all(),
        ]);
    }

    public function show($id)
    {
        $prodi = StudyProgram::with('department')->findOrFail($id);
        return view('admin.prodi.show', compact('prodi'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:study_programs,code',
            'department_id' => 'required|exists:departments,id',
        ]);

        StudyProgram::create([
            'name' => $request->name,
            'code' => $request->code,
            'department_id' => $request->department_id,
        ]);

        return redirect()->route('admin.prodi.index')->with('success', 'Prodi berhasil ditambahkan.');
    }



    public function update(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:10|unique:study_programs,code,' . $id,
            'department_id' => 'required|exists:departments,id',
        ]);

        try {
            // Cari Prodi berdasarkan ID
            $prodi = StudyProgram::findOrFail($id);

            // Update data
            $prodi->update([
                'name' => $validatedData['name'],
                'code' => $validatedData['code'],
                'department_id' => $validatedData['department_id'],
            ]);

            return redirect()->route('admin.prodi.index')
                ->with('success', 'Data Prodi berhasil diperbarui');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.prodi.index')
                ->with('error', 'Prodi tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui Prodi: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $prodi = StudyProgram::findOrFail($id);

            // Hapus pengecekan posts() jika tidak diperlukan
            $prodi->delete();

            return redirect()->route('admin.prodi.index')
                ->with('success', 'Prodi berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
