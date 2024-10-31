<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Submission;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage as FacadesStorage;

class UserController extends Controller
{
    public function index()
    {
        // Get all reports assigned to the authenticated user
        $reports = Report::where('assigned_user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        // $reports = Report::paginate(2);
        // dd($reports);

        // Get total report count
        $totalReportsCount = Report::where('assigned_user_id', Auth::id())->count();

        return view('dashboard.laporanMasuk', compact('reports', 'totalReportsCount'));
    }

    public function show($id)
    {
        $report = Report::with('submission')->findOrFail($id); // Eager load submission
        return view('dashboard.laporanMasukShow', compact('report'));
    }

    public function submit(Request $request, $reportId)
    {

        // Temukan laporan berdasarkan ID
        $report = Report::findOrFail($reportId);

        // Ambil nama pengguna dari session (asumsikan Anda menyimpannya di session setelah login)
        $username = Auth::user()->nama; // Sesuaikan jika Anda menyimpan nama pengguna dengan cara berbeda

        // Buat nama file baru
        $timestamp = now()->format('YmdHis'); // Format timestamp
        $newFileName = "{$username}_{$report->report_name}_{$timestamp}." . $request->file('file')->getClientOriginalExtension(); // Tambahkan ekstensi file

        // Store the file with the new name
        $path = $request->file('file')->storeAs('submissions', $newFileName); // Simpan file dengan nama baru

        // Create the submission
        Submission::updateOrCreate(
            ['report_id' => $reportId], // Create or update based on report ID
            ['file_path' => $path]
        );

        // Update the report's status to 'submitted' after file upload
        $report->status = 'submitted'; // Update status
        $report->save(); // Save changes

        return redirect()->route('user.reports.show', $reportId)->with('success', 'File berhasil dikumpulkan.');
    }


    public function destroy($id)
    {
        // Find the submission by ID
        $submission = Submission::findOrFail($id);

        // Get the report associated with the submission
        $report = Report::findOrFail($submission->report_id);

        // Delete the file from storage
        FacadesStorage::delete($submission->file_path);

        // Delete the submission record
        $submission->delete();

        // Update the report's status to 'not submitted'
        $report->status = 'not submitted'; // Update status
        $report->save(); // Save changes

        return redirect()->back()->with('success', 'File berhasil dihapus.');
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'report_file' => 'required|file|mimes:pdf,doc,docx|max:2048', // Adjust rules as needed
        ]);

        $report = Report::findOrFail($id);

        // Check if a report file already exists and delete it if needed
        if ($report->file_path) {
            FacadesStorage::delete($report->file_path);
        }

        // Store the new report file
        $path = $request->file('report_file')->store('reports'); // Store in 'storage/app/reports'

        $report->file_path = $path;
        $report->status = 'submitted'; // Update status
        $report->save();

        return redirect()->route('user.reports.index')->with('success', 'Report uploaded successfully.');
    }

    // public function destroy($id)
    // {
    //     $report = Report::findOrFail($id);

    //     // Delete the existing file
    //     if ($report->file_path) {
    //         FacadesStorage::delete($report->file_path);
    //     }

    //     // Optionally, delete the report record from the database
    //     $report->delete();

    //     return redirect()->route('user.reports.index')->with('success', 'Report deleted successfully.');
    // }
}
