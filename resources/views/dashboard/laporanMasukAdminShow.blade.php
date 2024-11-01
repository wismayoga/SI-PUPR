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
                                <a href="{{ route('admin.reportsin') }}" class="back-link">
                                    <i class="material-icons-two-tone">arrow_back_ios</i>
                                </a>
                                {{ $user->nama }}
                                @if ($totalReportsCount != null)
                                    ({{ $totalReportsCount }})
                                @endif
                            </h1>

                        </div>
                    </div>
                </div>
                <div class="row">
                    @if ($reports->isEmpty())
                        <div class="text-center">
                            <img src="{{ asset('assets/images/admin/admin.png') }}" class="empty-foto" alt=""
                                style="width: 450px; height: auto;" id="empty-image">
                            <p class="mt-2">Admin belum menginput laporan keluar.</p>
                        </div>
                    @else
                        @foreach ($reports as $report)
                            <div class="col-xl-4 card-row">
                                <div class="card d-flex flex-column align-items-center laporan-container">
                                    <img src="{{ asset('assets/images/cards/documents.png') }}" class="laporan-foto"
                                        alt="Report Image">
                                    <div class="card-body d-flex flex-column align-items-center text-center flex-fill">
                                        <h5 class="card-title">{{ $report->report_name }}</h5>
                                    </div>
                                    <div class="card-footer d-flex justify-content-between align-items-center w-100 px-3">
                                        @if ($report->status == 'not submitted')
                                            <a href="#" class="btn btn-warning"
                                                onclick="checkDeadline('{{ route('admin.reportsin.show.details', $report->id) }}', '{{ $report->deadline }}')">Kumpulkan</a>
                                        @else
                                            <a href="{{ route('admin.reportsin.show.details', $report->id) }}"
                                                class="btn btn-success"><i class="material-icons">check</i>Terkumpul</a>
                                        @endif
                                        <div style="display: flex; align-items: center;">
                                            <i class="material-icons"
                                                style="margin-right: 4px;color:rgb(216, 33, 33);font-size: 22px;">schedule</i>
                                            <p class="card-text mb-0">
                                                {{ \Carbon\Carbon::parse($report->deadline)->format('d M Y') }}
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                @if ($reports->isNotEmpty())
                    <!-- Custom Pagination Links -->
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            {{-- Previous Page Link --}}
                            <li class="page-item {{ $reports->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $reports->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&lt;</span>
                                </a>
                            </li>

                            {{-- Pagination Elements --}}
                            @foreach ($reports->getUrlRange(1, $reports->lastPage()) as $page => $url)
                                <li class="page-item {{ $page == $reports->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            {{-- Next Page Link --}}
                            <li class="page-item {{ $reports->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $reports->nextPageUrl() }}" aria-label="Next">
                                    <span aria-hidden="true">&gt;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <style>
        .laporan-container {
            height: 100%;
            margin: 10px;
            /* Optional if you want full height for the column */
        }

        .laporan-foto {
            width: 150px;
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
@endpush

@push('script')
    <!-- Import SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function checkDeadline(url, deadline) {
            console.log("Tombol Kumpulkan ditekan");
            const currentDate = new Date();
            const reportDeadline = new Date(deadline);

            // Cek jika waktu tenggat sudah lewat
            if (currentDate > reportDeadline) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Waktu Tenggat Terlewat',
                    text: 'Laporan ini sudah melewati waktu tenggat!',
                    confirmButtonText: 'OK'
                });
            } else {
                // Jika belum melewati tenggat, arahkan ke halaman pengumpulan
                window.location.href = url;
            }
        }
    </script>
@endpush

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    @if (session('success'))
        <script>
            $(document).ready(function() {
                swal({
                    title: "Berhasil!",
                    text: "{{ session('success') }}",
                    type: "success",
                    confirmButtonText: "OK"
                });
            });
        </script>
    @endif
@endpush
