@extends('layouts/main')
@section('title', 'Dashboard')

@section('content')
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="page-description">
                            <h1>Laporan Masuk</h1>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($users as $user)
                        <div class="col-xl-4 card-row">
                            <a href="{{ route('admin.reportsin.show', $user->id) }}"
                                style="text-decoration: none; color: inherit;">
                                <div class="card d-flex flex-column align-items-center laporan-container">
                                    <img src="{{ asset('assets/images/admin/' . (($loop->index % 6) + 1) . '.png') }}"
                                        class="laporan-foto" alt="Report Image">
                                    <div class="card-body d-flex flex-column align-items-center text-center flex-fill">
                                        <h5 class="card-title title">{{ $user->nama }}</h5>
                                    </div>
                                    <div class="card-footer d-flex justify-content-between align-items-center w-100 px-3">

                                        <div class="ms-auto">
                                            <p class="card-text mb-0">
                                                {{ $user->submitted_reports_count }} / {{ $user->total_reports_count }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
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
    </style>
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
