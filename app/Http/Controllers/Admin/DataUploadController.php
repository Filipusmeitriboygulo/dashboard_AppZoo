<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DataUploadRequest;
use App\Services\ExcelProcessingService;
use App\Models\Department;
use App\Models\StudyProgram;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;




class DataUploadController extends Controller
{
    protected $excelService;

    public function __construct(ExcelProcessingService $excelService)
    {
        $this->middleware(['auth', 'role:admin']);
        $this->excelService = $excelService;
    }

    public function index()
    {
        $departments = Department::with('studyPrograms.classes')->get();
        $recentUploads = $this->excelService->getRecentUploads();

        return view('admin.data-upload.index', compact('departments', 'recentUploads'));
    }

    public function showUploadForm($scope, $scopeId = null)
    {
        $scopeData = $this->getScopeData($scope, $scopeId);

        return view('admin.data-upload.form', compact('scope', 'scopeId', 'scopeData'));
    }

    public function upload(DataUploadRequest $request)
    {
        try {
            $file = $request->file('data_file');
            $scope = $request->input('scope');
            $scopeId = $request->input('scope_id');

            // Generate unique batch ID
            $batchId = 'batch_' . Str::random(10) . '_' . time();

            // Store file temporarily with original extension
            $extension = $file->getClientOriginalExtension();
            $filePath = $file->storeAs('uploads/data', $batchId . '.' . $extension);

            // Preview data first
            $preview = $this->excelService->previewFile($filePath, $scope, $scopeId);

            return response()->json([
                'success' => true,
                'batch_id' => $batchId,
                'file_path' => $filePath,
                'file_type' => $extension,
                'preview' => $preview,
                'message' => 'File uploaded successfully. Please review the preview.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 422);
        }
    }

    public function processFile(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|string',
            'file_path' => 'required|string',
            'file_type' => 'required|string',
            'scope' => 'required|in:class,study_program,department,campus',
            'scope_id' => 'nullable|integer'
        ]);

        try {
            $result = $this->excelService->processFile(
                $request->file_path,
                $request->file_type,
                $request->scope,
                $request->scope_id,
                $request->batch_id,
                Auth::id()
            );

            // Clean up temporary file
            Storage::delete($request->file_path);

            return response()->json([
                'success' => true,
                'result' => $result,
                'message' => 'Data processed successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Processing failed: ' . $e->getMessage()
            ], 422);
        }
    }

    public function getPreview(Request $request)
    {
        $request->validate([
            'file_path' => 'required|string',
            'file_type' => 'required|string',
            'scope' => 'required|string',
            'scope_id' => 'nullable|integer'
        ]);

        try {
            $preview = $this->excelService->previewFile(
                $request->file_path,
                $request->file_type,
                $request->scope,
                $request->scope_id
            );

            return response()->json([
                'success' => true,
                'preview' => $preview
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Preview failed: ' . $e->getMessage()
            ], 422);
        }
    }

    private function getScopeData($scope, $scopeId)
    {
        switch ($scope) {
            case 'class':
                return ClassModel::with('studyProgram.department')->find($scopeId);
            case 'study_program':
                return StudyProgram::with('department')->find($scopeId);
            case 'department':
                return Department::find($scopeId);
            case 'campus':
                return (object) ['name' => 'Seluruh Kampus'];
            default:
                return null;
        }
    }
}
