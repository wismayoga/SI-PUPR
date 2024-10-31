<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class AdminController extends Controller
{
    public function index()
    {
        // Get all reports assigned to the authenticated user
        // $reports = Report::where('assigned_user_id', Auth::id())->get();
        $users = User::where('role', 'user')
            ->withCount([
                'reports as submitted_reports_count' => function ($query) {
                    $query->where('status', 'submitted');
                },
                'reports as total_reports_count'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.laporanKeluar', compact('users'));
    }

    public function show($id)
    {
        // Get the user by ID
        $user = User::findOrFail($id);

        // Menambahkan perhitungan laporan yang dikirim dan total laporan
        $user->loadCount([
            'reports as submitted_reports_count' => function ($query) {
                $query->where('status', 'submitted');
            },
            'reports as total_reports_count'
        ]);

        // Fetch reports where the assigned_user_id matches the user's ID
        $reports = Report::with('user')->where('assigned_user_id', $user->id)->orderBy('created_at', 'desc')->get();
        // dd($reports);

        // Pass both user and reports to the view, even if reports is empty
        return view('dashboard.laporanKeluarShow', compact('reports', 'user'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'report_name' => 'required|string|max:255',
            'assigned_user_id' => 'required',
            'deadline' => 'required|date',
        ]);

        // Create the report and associate it with the user
        Report::create([
            'assigned_user_id' => $request->assigned_user_id, // Assuming you are using authentication
            'report_name' => $validatedData['report_name'],
            'deadline' => $validatedData['deadline'],
            'status' => 'not submitted', // Set default status
        ]);

        return redirect()->route('admin.reports.show', $request->assigned_user_id)->with('success', 'Report added successfully.');
    }

    public function download($reportId)
    {
        // Temukan laporan berdasarkan ID
        $report = Report::findOrFail($reportId);

        // Temukan submission berdasarkan report ID
        $submission = Submission::where('report_id', $reportId)->first();

        // Pastikan submission ada dan file_path tidak kosong
        if ($submission && !empty($submission->file_path)) {
            // Pastikan file ada di storage
            if (Storage::disk('local')->exists($submission->file_path)) {
                return Storage::download($submission->file_path, basename($submission->file_path));
            } else {
                return redirect()->back()->with('error', 'File tidak ditemukan.');
            }
        }

        return redirect()->back()->with('error', 'Tidak ada file yang diunduh untuk laporan ini.');
    }

    public function batchDownload(Request $request)
    {
        // Validasi input untuk memastikan 'report_ids' ada
        $request->validate([
            'report_ids' => 'required|array',
            'report_ids.*' => 'exists:reports,id', // Pastikan setiap ID laporan ada di tabel reports
        ]);

        $firstReport = Report::find($request->input('report_ids')[0]);
        $user = User::find($firstReport->assigned_user_id);

        $zipFileName = 'Laporan_' . $user->nama . '_' . now()->format('YmdHis') . '.zip';
        $zipFilePath = public_path($zipFileName);

        $zip = new ZipArchive;
        // Cek apakah ZIP bisa dibuka
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return redirect()->back()->with('error', 'Failed to create ZIP file: ' . $zip->getStatusString());
        }

        // Tambahkan file ke ZIP
        foreach ($request->input('report_ids') as $reportId) {
            // Temukan laporan berdasarkan ID
            $report = Report::find($reportId);
            if ($report) {
                // Temukan submission berdasarkan report ID
                $submission = Submission::where('report_id', $reportId)->first();

                // Pastikan submission ada dan file_path tidak kosong
                if ($submission && !empty($submission->file_path)) {
                    // Tentukan path file
                    $reportPath = storage_path('app/private/' . $submission->file_path); // Path file di storage

                    // Pastikan file ada di storage
                    if (file_exists($reportPath)) {
                        $zip->addFile($reportPath, basename($submission->file_path));
                    } else {
                        // Jika file tidak ada, catat atau tambahkan pesan jika diperlukan
                        // $zip->addFromString($report->file_name, "File tidak ditemukan."); // Uncomment if needed
                    }
                }
            }
        }

        // Tutup ZIP dan periksa apakah berhasil
        if ($zip->close() === TRUE) {
            // Periksa apakah ZIP berhasil dibuat
            if (file_exists($zipFilePath)) {
                return response()->download($zipFilePath)->deleteFileAfterSend(true);
            } else {
                return redirect()->back()->with('error', 'Failed to create ZIP file: ZIP file does not exist after closing.');
            }
        } else {
            return redirect()->back()->with('error', 'Failed to close ZIP file.');
        }
    }

    public function destroy($id)
    {
        // Mencari laporan berdasarkan ID
        $report = Report::find($id);

        $user = User::find($report->assigned_user_id);

        // Memastikan laporan ditemukan
        if (!$report) {
            return redirect()->back()->with('error', 'Laporan tidak ditemukan.');
        }

        // Mencari submission terkait dengan laporan ini
        $submission = $report->submission; // Asumsi relasi sudah didefinisikan di model

        // Menghapus submission jika ada
        if ($submission) {
            $submission->delete();
        }

        // Menghapus laporan
        $report->delete();

        // Mengalihkan kembali dengan pesan sukses
        return redirect()->route('admin.reports.show', $report->assigned_user_id)->with('success', 'Laporan dan pengajuan berhasil dihapus.');
    }


    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'report_name' => 'required|string|max:255',
            'deadline' => 'required|date',
        ]);

        // dd($request, $id);

        // Find the report and update its data
        $report = Report::findOrFail($request->report_id);
        $report->report_name = $request->input('report_name');
        $report->deadline = $request->input('deadline');
        $report->save();

        return redirect()->route('admin.reports.show', $request->user)->with('success', 'Report updated successfully.');
    }
}
