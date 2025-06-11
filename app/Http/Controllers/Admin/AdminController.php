<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\ToeflScore;
use App\Models\ClusteringSession;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function dashboard()
    {
        $stats = [
            'total_students' => Student::count(),
            'total_toefl_scores' => ToeflScore::count(),
            'total_clustering_sessions' => ClusteringSession::count(),
            'active_users' => User::where('is_active', true)->count(),
        ];

        return view('klasterisasi.index');
    }
}
