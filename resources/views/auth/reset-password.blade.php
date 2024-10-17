<x-guest-layout>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Actualizar contraseña</title>
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <link href="css/sb-admin-2.min.css" rel="stylesheet">
    </head>
    <body class="bg-primary" style="background-color: #0d6efd;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-12 col-md-9">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
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
                                        <h1 class="h4 text-gray-900 mb-2">¡Registra tu nueva contraseña!</h1>
                                        <p class="mb-4">Ingresa tu nueva contraseña de usuario,¡asegúrate de recordarla!</p>
                                    </div>
                                    <x-validation-errors class="mb-4" />
                                    <form method="POST" action="{{ route('password.update') }}">
                                        @csrf
                                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                        <div class="form-group mb-4">
                                            <input type="email" name="email" class="form-control form-control-lg rounded @error('email') is-invalid @enderror" id="email" placeholder="Tu dirección de correo..." :value="old('email', $request->email)" required autofocus autocomplete="username" />
                                            <label class="form-label" for="email">Correo Electrónico</label>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-4">
                                            <input type="password" name="password" class="form-control form-control-lg rounded @error('password') is-invalid @enderror" id="password" placeholder="*****" required autocomplete="new-password" />
                                            <label class="form-label" for="password">Tu nueva contraseña</label>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-4">
                                            <input type="password" name="password_confirmation" class="form-control form-control-lg rounded" id="password_confirmation" placeholder="*****" required autocomplete="new-password" />
                                            <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
                                        </div>
                                        <button class="btn btn-primary btn-user btn-block mt-3 rounded" type="submit">
                                            Cambiar Contraseña
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
