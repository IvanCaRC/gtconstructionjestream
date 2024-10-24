<x-guest-layout>
    <section class="vh-100" style="background-color: #0d6efd;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-10">
                    <div class="card" style="border-radius: 1rem;">
                        <div class="row g-0">
                            <div class="col-md-6 col-lg-5 d-none d-md-block">
                                <img src="https://lirp.cdn-website.com/4df09214/dms3rep/multi/opt/4206639_e254c7b0-8401-4ee9-83e2-003e2314f846-1920w.jpg"
                                    alt="login form" class="img-fluid"
                                    style="border-radius: 1rem 0 0 1rem; height: 100%; object-fit: cover;" />
                            </div>
                            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5 text-black">

                                    <!-- Mensajes de Error de Validación con flash message-->
                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <ul>
                                                @if ($errors->has('status_error'))
                                                    <li>{{ $errors->first('status_error') }}</li>
                                                @else
                                                    <li>{{ 'Verifica correo y/o contraseña'}}</li>
                                                @endif
                                            </ul>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif

                                    <!-- Mensaje de Estado -->
                                    @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}
                                        </div>
                                    @endif

                                    <!-- Mensaje de Error -->
                                    @if (session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="d-flex align-items-center mb-3 pb-1 justify-content-center">
                                            <img src="https://dewey.tailorbrands.com/production/brand_version_mockup_image/54/8174331054_85cb5a4b-6933-4113-b236-501cd8e54b5a.png"
                                                alt="logo" class="img-fluid mb-4" />
                                        </div>
                                        <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Ingresa al sistema
                                            con tu cuenta</h5>
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="form2Example17">Correo Electrónico</label>
                                            <input type="email" name="email"
                                                class="form-control form-control-lg rounded @error('email') is-invalid @enderror"
                                                placeholder="ejemplo@gmail.com" value="{{ old('email') }}" required
                                                autofocus autocomplete="username" />
                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ 'Verifica tu correo' }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="form2Example27">Contraseña</label>
                                            <input type="password" name="password"
                                                class="form-control form-control-lg rounded @error('password') is-invalid @enderror"
                                                placeholder="*****" required autocomplete="current-password" />
                                            @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-check mb-4">
                                            <input class="form-check-input" type="checkbox" id="remember_me"
                                                name="remember">
                                            <label class="form-check-label"
                                                for="remember_me">{{ __('Remember me') }}</label>
                                        </div>
                                        @if (Route::has('password.request'))
                                            <a class="btn btn-link"
                                                href="{{ route('password.request') }}">{{ __('¿Olvidaste tu contraseña?') }}</a>
                                        @endif
                                        <div class="pt-1 mb-4">
                                            <button class="btn btn-primary btn-lg btn-block" type="submit"
                                                style="background-color: #0d6efd; border-color: #0d6efd;">Login</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
