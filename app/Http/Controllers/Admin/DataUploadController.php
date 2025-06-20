<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\ToeflScoreEntry;
use App\Models\UploadLog;

class DataUploadController extends Controller
{
    protected $excelService;

    public function index()
    {
        $departments = Department::with('studyPrograms.classes')->get();
        // $recentUploads = $this->excelService->getRecentUploads();
        $files = UploadLog::all();

        return view('admin.data-upload.index', compact('departments', 'files'));
    }
    public function viewScores($fileId)
    {
        // dd($fileId);
        $file = UploadLog::findOrFail($fileId);
        $scores = ToeflScoreEntry::where('score_id', $fileId)->get();

        return view('admin.ToeflScoreEntry', compact('file', 'scores'));
    }

}
