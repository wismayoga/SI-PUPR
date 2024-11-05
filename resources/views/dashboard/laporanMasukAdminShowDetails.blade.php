@extends('layouts/main')
@section('title', 'Dashboard')

@section('content')
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="page-description">
                            <h1>
                                <a href="{{ route('admin.reportsin.show', $report->assigned_user_id) }}" class="back-link">
                                    <i class="material-icons-two-tone">arrow_back_ios</i>
                                </a>
                                {{ $report->report_name }}
                            </h1>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <p>Status:
                        @if ($report->status == 'submitted')
                            <span class="badge rounded-pill badge-success">Terkumpul</span>
                        @else
                            <span class="badge rounded-pill badge-light"
                                style="color: #F3F7EC;background-color:#E88D67;">Belum Terkumpul</span>
                        @endif
                    </p>
                    @if ($report->submission)
                        <span class="submitted-file">
                            <i class="material-icons">description</i>
                            <p>Nama File: <b>{{ basename($report->submission->file_path) }}</b></p>
                        </span>

                        <form id="delete-form-{{ $report->submission->id }}"
                            action="{{ route('admin.reportsin.delete', $report->submission->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger delete-button"
                                data-id="{{ $report->submission->id }}">Hapus File</button>
                        </form>
                    @else
                        <div id="dropzone">
                            <form action="{{ route('admin.reportsin.submit', $report->id) }}" class="dropzone needsclick"
                                id="demo-upload" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="dz-message needsclick">
                                    <button type="button" class="dz-button">Jatuhkan file disini atau klik untuk
                                        upload</button><br />
                                    <span class="note needsclick">Maksimum ukuran file 2MB.</span>
                                </div>
                            </form>
                            <!-- Upload Button -->

                            <div id="upload-button-container">
                                <button id="upload-button" class="btn btn-primary ">Kumpulkan</button>
                            </div>
                        </div>
                    @endif


                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link href="{{ asset('assets/plugins/highlight/styles/github-gist.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/dropzone/min/dropzone.min.css') }}" rel="stylesheet">
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
    </style>
    <style>
        .submitted-file {
            display: flex;
            align-items: center;
            /* Meratakan ikon dan teks secara vertikal */
            gap: 4px;
            /* Menambahkan jarak antar-elemen */
            margin-bottom: 20px;
        }

        .submitted-file i {
            font-size: 20px;
        }

        .submitted-file p {
            margin: 0;
            /* Menghilangkan margin default agar sejajar dengan ikon */
            line-height: 1;
            /* Menyesuaikan tinggi baris agar lebih sejajar dengan ikon */
        }
    </style>
    <style>
        #dropzone {
            text-align: center;
        }

        #upload-button-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 1rem;
        }

        #upload-button {
            display: inline-block;
            /* Membuat tombol berada di sebelah kanan */
            margin-top: 1rem;
            background-color: #006989;
            color: #F3F7EC;
            border: none;
            padding: 0.5rem 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            text-align: right;
        }

        #upload-button:hover {
            background-color: #005C78;
            /* Warna background saat di-hover */
            transform: scale(1.05);
            /* Sedikit perbesar tombol saat di-hover */
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset('assets/plugins/highlight/highlight.pack.js') }}"></script>
    <script src="{{ asset('assets/plugins/dropzone/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Menangani konfirmasi penghapusan
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id; // Mendapatkan ID pengumpulan
                Swal.fire({
                    title: 'Apakah Anda yakin ingin menghapus file ini?',
                    text: 'Tindakan ini tidak dapat dibatalkan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika dikonfirmasi, kirim formulir
                        document.getElementById(`delete-form-${id}`).submit();

                        // Tangani sukses penghapusan di sini
                        document.getElementById(`delete-form-${id}`).onsubmit = function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'File berhasil dihapus!',
                                text: 'File telah berhasil dihapus.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location
                                    .reload(); // Refresh halaman setelah alert ditutup
                            });
                        };
                    }
                });
            });
        });
    </script>
    <script>
        var myDropzone = new Dropzone("#demo-upload", {
            paramName: "file",
            maxFilesize: 2,
            // acceptedFiles: ".pdf,.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png",
            acceptedFiles: ".pdf,.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png,.xls,.xlsx",

            autoProcessQueue: false,
            maxFiles: 1,
            init: function() {
                var myDropzone = this;

                // Handle file added event
                myDropzone.on("addedfile", function(file) {
                    // If there's already a file, remove the old one
                    if (myDropzone.files.length > 1) {
                        myDropzone.removeFile(myDropzone.files[0]);
                    }
                });

                document.getElementById("upload-button").addEventListener("click", function() {
                    myDropzone.processQueue();
                });

                myDropzone.on("success", function(file, response) {
                    // Show success alert using SweetAlert2
                    Swal.fire({
                        icon: 'success',
                        title: 'File berhasil dikumpulkan!',
                        text: 'File telah berhasil diupload.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload(); // Refresh the page after the alert is closed
                    });
                });

                myDropzone.on("error", function(file, response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error uploading file: ' + response,
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    </script>
@endpush
