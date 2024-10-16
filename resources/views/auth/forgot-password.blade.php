<x-guest-layout>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Recuperar contraseña</title>
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <link href="css/sb-admin-2.min.css" rel="stylesheet">
    </head>
    <body class="bg-primary" style="background-color: #0d6efd;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-12 col-md-9">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                                <div class="p-5 w-100">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2">¿Olvidaste tu contraseña?</h1>
                                        <p class="mb-4">Ingresa tu dirección de correo electrónico para el restablecimiento de tu contraseña.</p>
                                    </div>
                                    <form method="POST" action="{{ route('password.email') }}">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <input type="email" name="email" class="form-control rounded @error('email') is-invalid @enderror" id="email" placeholder="Tu dirección de correo..." :value="old('email')" required autofocus autocomplete="username" />
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <button class="btn btn-primary btn-user btn-block mt-3 rounded" type="submit">
                                            Restablecer Contraseña
                                        </button>
                                    </form>
                                    <div class="mt-4">
                                        <hr>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="{{ route('login') }}">Regresar al Login!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="js/sb-admin-2.min.js"></script>
    </body>
</x-guest-layout>
