@extends('layouts/main')
@section('title', 'Laporan Keluar Show')

@section('content')
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="page-description">
                            <h1>
                                <a href="{{ route('admin.reports') }}" class="back-link">
                                    <i class="material-icons-two-tone">arrow_back_ios</i>
                                </a>
                                {{ $user->nama }}
                                @if ($user->total_reports_count != null)
                                    ({{ $user->submitted_reports_count }} /
                                    {{ $user->total_reports_count }})
                                @endif
                            </h1>
                        </div>
                    </div>
                </div>

                <form id="batch-download-form" action="{{ route('admin.reports.batchDownload') }}" method="POST">
                    @csrf
                    <div class="row mb-3 justify-content-end me-4">
                        @if ($reports->isEmpty())
                        @else
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary m-b-sm" id="download-selected-btn"
                                    style="background-color: #006989;color:#F3F7EC;border:none;">
                                    Download Laporan (0)
                                </button>
                            </div>
                        @endif
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary m-b-sm" data-bs-toggle="modal"
                                data-bs-target="#exampleModalCenter"
                                style="background-color: #006989;color:#F3F7EC;border:none;">
                                <i class="material-icons">add</i>Laporan
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        @if ($reports->isEmpty())
                            <div class="text-center">
                                <img src="{{ asset('assets/images/admin/empty.png') }}" class="empty-foto" alt=""
                                    style="width: 450px; height: auto;" id="empty-image">
                                <p class="mt-2">Tidak ada laporan yang tersedia, silahkan tambahkan.</p>
                            </div>
                        @else
                            <div class="row">
                                @foreach ($reports as $report)
                                    <div class="col-md-4 card-row">
                                        <div class="card d-flex flex-column align-items-center laporan-container">

                                            <!-- Status Badge in the top-left corner -->
                                            <div class="position-absolute top-0 start-0 mt-2 ms-3">
                                                <span
                                                    class="badge {{ $report->status == 'submitted' ? 'badge-submitted' : 'badge-not-submitted' }}">
                                                    {{ $report->status == 'submitted' ? 'Dikumpul' : 'Belum' }}
                                                </span>
                                            </div>


                                            <!-- Checkbox in the top-right corner -->
                                            <div class="position-absolute top-0 end-0 mt-2 me-3">
                                                <input type="checkbox" class="form-check-input report-checkbox"
                                                    value="{{ $report->id }}" title="Select Report"
                                                    @if ($report->status != 'submitted') disabled @endif>
                                            </div>

                                            <div class="text-center">
                                                <img src="{{ asset('assets/images/cards/documents.png') }}"
                                                    class="laporan-foto" alt="Report Image">
                                            </div>
                                            <div
                                                class="card-body d-flex flex-column align-items-center text-center flex-fill">
                                                <h5 class="card-title title">{{ $report->report_name }}</h5>
                                            </div>
                                            <div
                                                class="card-footer d-flex justify-content-between align-items-center w-100 px-3">


                                                <div class="d-flex align-items-center">
                                                    <!-- Tombol Hapus -->
                                                    <button type="button"
                                                        class="btn btn-link p-0 d-flex align-items-center delete-button"
                                                        title="Delete Report" style="color: #005C78; font-size: 22px;"
                                                        data-report-id="{{ $report->id }}">
                                                        <i class="material-icons">delete</i>
                                                    </button>

                                                    <!-- Edit Button -->
                                                    <a href="#" class="d-flex align-items-center text-decoration-none"
                                                        title="Edit Report" data-bs-toggle="modal"
                                                        data-bs-target="#editReportModal{{ $report->id }}"
                                                        data-report-id="{{ $report->id }}"
                                                        data-report-name="{{ $report->report_name }}"
                                                        data-deadline="{{ $report->deadline }}">
                                                        <i class="material-icons"
                                                            style="color: #005C78; font-size: 22px;">edit</i>
                                                    </a>

                                                    <!-- Schedule Icon and Deadline Text -->
                                                    <div class="d-flex align-items-center ms-2">
                                                        <i class="material-icons"
                                                            style="margin-right: 4px; color: #E88D67; font-size: 22px;">schedule</i>
                                                        <p class="card-text mb-0">
                                                            {{ \Carbon\Carbon::parse($report->deadline)->format('d M Y') }}
                                                        </p>
                                                    </div>
                                                </div>


                                                <div class="d-flex align-items-center">
                                                    <!-- Tombol Unduh -->
                                                    <a href="{{ route('admin.reports.download', $report->id) }}"
                                                        class="download-icon d-flex align-items-center text-decoration-none"
                                                        title="Download Report"
                                                        @if ($report->status != 'submitted') disabled @endif>
                                                        <i class="material-icons"
                                                            style="font-size: 24px;color: #005C78;">download</i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Modal for Adding Report -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Data Laporan ({{ $user->nama }})</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.reports.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="assigned_user_id" value="{{ $user->id }}">
                    <div class="modal-body">
                        <div class="form-group form-laporan">
                            <label for="report_name">Nama Laporan</label>
                            <input type="text" class="form-control" id="report_name" name="report_name" required>
                        </div>
                        <div class="form-group form-laporan">
                            <label for="deadline">Tenggat</label>
                            <input type="datetime-local" class="form-control" id="deadline" name="deadline" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            style="border: none;">Batal</button>
                        <button type="submit" class="btn btn-primary"
                            style="color: #F3F7EC;background-color:#006989;border:none">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Report -->
    @foreach ($reports as $report)
        <div class="modal fade" id="editReportModal{{ $report->id }}" tabindex="-1"
            aria-labelledby="editReportModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editReportModalTitle">Edit Laporan
                            ({{ $report->report_name }})
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.reports.update', $report->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="report_id" id="report_id" value="{{ $report->id }}">
                        <input type="hidden" name="user" id="user" value="{{ $report->assigned_user_id }}">
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="editReportName">Nama Laporan</label>
                                <input type="text" class="form-control" id="editReportName" name="report_name"
                                    value="{{ $report->report_name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="editDeadline">Tenggat</label>
                                <input type="datetime-local" class="form-control" id="editDeadline" name="deadline"
                                    value="{{ $report->deadline }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <form id="delete-form-{{ $report->id }}" action="{{ route('admin.reports.destroy', $report->id) }}"
            method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        {{-- <form class="ms-2" action="{{ route('admin.reports.destroy', $report->id) }}" method="POST"
            onsubmit="return confirm('Are you sure you want to delete this report?');" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-link p-0 d-flex align-items-center" title="Delete Report"
                style="color: #005C78; font-size: 22px;">
                <i class="material-icons">delete</i>
            </button>
        </form> --}}
    @endforeach



    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: "Berhasil!",
                    text: '{{ session('success') }}',
                    icon: "success"
                });
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif
@endsection

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .back-link {
            color: inherit;
            /* Keeps the icon color same as text */
            text-decoration: none;
            /* Removes underline */
            margin-right: 8px;
            /* Adds spacing between icon and text */
        }

        .back-link:hover {
            color: #006989;
            /* Change color on hover */
        }

        .form-laporan {
            margin-bottom: 10px;
        }
    </style>
@endpush

@push('style')
    <style>
        .laporan-container {
            height: 100%;
            margin: 10px;
            /* Optional if you want full height for the column */
        }

        .laporan-foto {
            width: 100px;
            margin-top: 30px;
            /* Optional: adds spacing at the top */
        }

        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            width: 100%;
            padding: 10px;
            margin-top: auto;
            /* Pushes footer to the bottom */
        }

        .card-row {
            margin-bottom: 40px;
        }

        .badge-submitted {
            background-color: #E88D67;
            opacity: 95%;
            /* Green for submitted */
            color: white;
            /* Text color */
        }

        .badge-not-submitted {
            background-color: #bbbbbb;
            opacity: 90%;
            /* Red for not submitted */
            color: white;
            /* Text color */
        }

        .download-icon[disabled] {
            pointer-events: none;
            opacity: 0.5;
            /* Makes it look grayed out */
            cursor: not-allowed;
            /* Changes cursor to indicate disabled state */
        }

        /* Default styling for desktop */
        #empty-image {
            width: 450px;
            height: auto;
        }

        /* Media query for smaller screens (up to 600px wide) */
        @media (max-width: 600px) {
            #empty-image {
                width: 100%;
                /* Adjust the width to take the full screen on mobile */
                max-width: 300px;
                /* Set a maximum width if needed */
            }
        }
    </style>
@endpush

@push('script')
    <script>
        const updateDownloadButtonText = () => {
            const selectedCheckboxes = document.querySelectorAll('.report-checkbox:checked');
            const count = selectedCheckboxes.length;
            const button = document.getElementById('download-selected-btn');

            if (count > 0) {
                button.textContent = `Download Laporan (${count})`;
            } else {
                button.textContent = `Download Laporan (0)`;
            }
        };

        document.querySelectorAll('.report-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateDownloadButtonText);
        });

        document.getElementById('download-selected-btn').addEventListener('click', function() {
            const selectedCheckboxes = document.querySelectorAll('.report-checkbox:checked');
            const reportIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);

            if (reportIds.length === 0) {
                alert('Please select at least one report to download.');
                return;
            }

            const form = document.getElementById('batch-download-form');
            reportIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'report_ids[]';
                input.value = id;
                form.appendChild(input);
            });

            form.submit();
            setTimeout(() => {
                location.reload();
            }, 3000);
        });

        // Initial call to set the button text
        updateDownloadButtonText();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const reportId = this.getAttribute('data-report-id');
                const deleteForm = document.getElementById(`delete-form-${reportId}`);

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data laporan akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteForm.submit(); // Submit form delete jika konfirmasi positif
                    }
                });
            });
        });
    </script>
@endpush
