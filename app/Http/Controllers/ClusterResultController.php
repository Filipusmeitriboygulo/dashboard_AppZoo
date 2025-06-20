<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClusterResult;
use Illuminate\Support\Facades\Auth;

class ClusterResultController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();

        if (in_array($user->role, ['admin', 'kepala_upa', 'wakil_direktur'])) {
            $results = ClusterResult::all();
        } elseif ($user->role === 'ketua_prodi') {
            $results = ClusterResult::where('prodi', $user->studyProgram->name)->get();
        } elseif ($user->role === 'ketua_jurusan') {
            $results = ClusterResult::where('jurusan', $user->department->name)->get();
        } else {
            abort(403);
        }

        return view('cluster_results.index', compact('results'));
    }

    public function show($id)
    {
        $result = ClusterResult::findOrFail($id);

        $this->authorize('view', $result);

        return view('cluster_results.show', compact('result'));
    }
}
