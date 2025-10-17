<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index()
    {
        return view('admin.jurusan.index', [
            'departments' => Department::all(),
            'studyPrograms' => StudyProgram::all(),
            
        ]);
    }

    public function show($id) 
    {
        $department = Department::findOrFail($id);
        return view('admin.jurusan.show', compact('department'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:10|unique:departments,code',
            
        ]);

        try {
            Department::create([
                'name' => $request->name,
                'code' => $request->code
            
            ]);

            return redirect()->route('admin.jurusan.index')->with('success', 'Jurusan berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagagl menambahkan Jurusan:' . $e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:10|unique:departments,code,' . $id . ',id',
        ]);

        try {
            // Find the user or fail
            $department = Department::findOrFail($id);

            // Prepare the data for update
            $updateData = [
                'name' => $validatedData['name'],
                'code' => $validatedData['code'],
            ];


            // Perform the update
            $department->update($updateData);

            return redirect()->route('admin.jurusan.index')
                ->with('success', 'Data Jurusan berhasil diperbarui');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.jurusan.index')
                ->with('error', 'Jurusan tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui jurusan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {   
        try {
            $department = Department::findOrFail($id);

            // Hapus pengecekan posts() jika tidak diperlukan
            $department->delete();

            return redirect()->route('admin.jurusan.index')
                ->with('success', 'Jurusan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
