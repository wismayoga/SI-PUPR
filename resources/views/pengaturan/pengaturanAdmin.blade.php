@extends('layouts/main')
@section('title', 'Pengaturan')

@section('content')
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="page-description">
                            <h1>Pengaturan</h1>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @if (session('success'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: '{{ session('success') }}',
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

                    <h2 class="">Ganti Password Admin</h2>
                    <form action="{{ route('settings.admin.reset') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="old_password" class="form-label">Password Lama</label>
                            <input type="password" name="old_password" id="old_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                class="form-control" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary button-x">Ganti Password</button>
                        </div>

                    </form>

                    <h2 class="mt-5">Reset Password Pengguna</h2>

                    <form action="{{ route('settings.admin.reset-user') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Pilih User</label>
                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="">-- Pilih User --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->username }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-danger button-x">Reset Password</button>
                        </div>

                    </form>

                    <div class="d-flex justify-content-center">
                        <a href="#" onclick="confirmLogout(event)" class="btn btn-danger logout-button">Logout</a>
                    </div>


                </div>


            </div>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .logout-button {
            width: 100%;
            padding: 15px;
            font-size: 20px;
            margin-top: 50px;
            background-color: #E88D67;
            border: none;
        }

        .button-x {
            color: #F3F7EC;
            background-color: #006989;
            border: none;
        }
    </style>
@endpush

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan keluar dari aplikasi.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#E88D67',
                cancelButtonColor: '#A5A5A5FF',
                confirmButtonText: 'Ya, Logout'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('actionlogout') }}";
                }
            });
        }
    </script>
@endpush
