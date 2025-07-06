<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\KlasterisasiExport;
use App\Models\UploadLog;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ExportController extends Controller
{
    public function exportExcel($upload_id)
    {
        try {
            // Log untuk debugging
            Log::info('Excel Export started for upload_id: ' . $upload_id);

            // Validasi upload_id
            $upload = UploadLog::findOrFail($upload_id);
            Log::info('Upload found: ' . $upload->id);

            // Pastikan ada data untuk diekspor
            $resultsCount = $upload->clusterResults()->count();
            Log::info('Results count: ' . $resultsCount);

            if ($resultsCount == 0) {
                Log::warning('No data to export for upload_id: ' . $upload_id);
                return back()->with('error', 'Tidak ada data untuk diekspor.');
            }

            // Cek apakah class export ada
            if (!class_exists(\App\Exports\KlasterisasiExport::class)) {
                Log::error('KlasterisasiExport class not found');
                return back()->with('error', 'Export class tidak ditemukan.');
            }

            // Generate filename
            $filename = 'klasterisasi_' . $upload->id . '_' . date('Y-m-d_H-i-s') . '.xlsx';
            Log::info('Generated filename: ' . $filename);

            // Download Excel file
            return Excel::download(
                new KlasterisasiExport($upload_id),
                $filename,
                \Maatwebsite\Excel\Excel::XLSX
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Excel Export - Upload not found: ' . $upload_id);
            return back()->with('error', 'Data upload tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Excel Export Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Gagal mengekspor Excel: ' . $e->getMessage());
        }
    }

    public function exportPdf($upload_id)
    {
        // Tingkatkan memory limit dan execution time
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 300); // 5 menit

        try {
            $upload = UploadLog::findOrFail($upload_id);

            // Gunakan eager loading untuk menghindari N+1 query
            $results = $upload->clusterResults()->with('toeflScoreEntry')->get();

            // Batasi data jika terlalu banyak
            if ($results->count() > 1000) {
                return back()->with('error', 'Data terlalu banyak (>1000 records). Silakan filter data terlebih dahulu.');
            }

            // Log memory usage untuk debugging
            Log::info('Memory before PDF generation: ' . memory_get_usage(true) / 1024 / 1024 . ' MB');

            $pdf = Pdf::loadView('exports.klasterisasi-pdf', compact('upload', 'results'))
                ->setPaper('a4', 'landscape') // Landscape untuk tabel lebar
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isPhpEnabled' => true,
                    'defaultFont' => 'sans-serif',
                    'dpi' => 96, // Kurangi DPI untuk menghemat memory
                    'debugKeepTemp' => false,
                    'debugCss' => false,
                    'debugLayout' => false,
                    'debugLayoutLines' => false,
                    'debugLayoutBlocks' => false,
                    'debugLayoutInline' => false,
                    'debugLayoutPaddingBox' => false,
                ]);

            Log::info('Memory after PDF generation: ' . memory_get_usage(true) / 1024 / 1024 . ' MB');

            return $pdf->download('klasterisasi_' . $upload->id . '.pdf');
        } catch (\Exception $e) {
            Log::error('PDF Export Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }
}
