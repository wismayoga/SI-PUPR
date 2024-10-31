<!doctype html>
<html lang="en">

<head>
    <title>Login 10</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

    <link rel="stylesheet" href="{{ asset('assets_login/css/style.css') }}">


    <style>
        .logo {
            height: 120px;
            /* Adjust height of the logo */
            margin-bottom: 10px;
        }

        .main-text {
            font-size: 56px;
            font-weight: 600;
            color: #E88D67;
            margin: 0;
        }

        .sub-text {
            font-size: 24px;
            font-weight: 400;
            color: #F3F7EC;
        }

        @media (max-width: 768px) {
            .main-text {
                font-size: 30px;
                /* Reduced size for mobile */
            }

            .sub-text {
                font-size: 18px;
                /* Reduced size for mobile */
            }

            .logo {
                height: 80px;
                /* Reduced height for mobile */
            }
        }

        .header {
            display: flex;
            align-items: center;
        }

        .header .logo-container {
            flex: 0 0 auto;
            margin-right: 20px;
        }

        .header .text-container {
            flex: 1;
        }

        .ftco-section {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .form-control {
            border-radius: 8px;
            /* Reduced border radius for form fields */
            border: 1px solid #005C78;
            /* Optional: Add a border */
            padding: 10px;
            /* Adjust padding */
            background-color: #FFFFFF;
        }

        .form-control::placeholder {
            color: rgb(167, 167, 167);
            /* Change this color to match your desired placeholder color */
            opacity: 1;
            /* Optional: To ensure opacity is 1 for the placeholder */
        }

        .form-control:focus {
            border-color: #005C78;
            /* Optional: Change border color on focus */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            /* Optional: Add a focus shadow */
            background-color: #FFFFFF;
        }

        .btn-primaryx {
            background-color: #005C78;
            /* Change button color (green) */
            border: none;
            /* Remove border */
            border-radius: 8px;
            /* Reduced border radius for button */
            padding: 10px;
            /* Adjust padding */
            margin-top: 20px;
        }

        .btn-primaryx:hover {
            background-color: #006989;
            /* Darker green on hover */
        }
    </style>

</head>

<body class="img js-fullheight" style="background-image: url(assets_login/images/bg.png);">
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-md-8 d-flex justify-content-center">
                    <div class="header d-flex justify-content-center">
                        <div class="logo-container">
                            <img src="{{ asset('assets_login/images/logo.png') }}" alt="Logo" class="logo">
                        </div>
                        <div class="text-container text-start">
                            <h2 class="main-text">SISTEM INFORMASI</h2>
                            <h3 class="sub-text">DINAS PEKERJAAN UMUM DAN TATA RUANG</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="login-wrap p-0">
                        <form action="{{ route('actionlogin') }}" class="signin-form" method="post">
                            @csrf
                            <div class="form-group">
                                <input type="text" name="username" class="form-control" placeholder="Username"
                                    required>
                            </div>
                            <div class="form-group">
                                <input id="password-field" name="password" type="password" class="form-control"
                                    placeholder="Password" required>
                                <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"
                                    style="color: rgb(80, 80, 80);"></span>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="form-control btn btn-primaryx submit px-3"
                                    style="color: rgb(227, 227, 227);">Sign
                                    In</button>
                            </div>
                        </form>
                        {{-- <p class="w-100 text-center">&mdash; Or Sign In With &mdash;</p> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>



    <script src="{{ asset('assets_login/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets_login/js/popper.js') }}"></script>
    <script src="{{ asset('assets_login/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets_login/js/main.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    @if (session('error'))
        <script>
            $(document).ready(function() {
                swal({
                    title: "Error!",
                    text: "{{ session('error') }}",
                    type: "error",
                    confirmButtonText: "OK"
                });
            });
        </script>
    @endif

</body>

</html>
