<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\KlasterisasiExport;
use App\Models\UploadLog;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportExcel($upload_id)
    {
        $upload = UploadLog::findOrFail($upload_id);
        return Excel::download(new KlasterisasiExport($upload), 'klasterisasi_' . $upload->id . '.xlsx');
    }

    public function exportPdf($upload_id)
    {
        $upload = UploadLog::findOrFail($upload_id);
        $results = $upload->clusterResults;
        return Pdf::loadView('exports.klasterisasi-pdf', compact('upload', 'results'))
            ->download('klasterisasi_' . $upload->id . '.pdf');
    }
}
