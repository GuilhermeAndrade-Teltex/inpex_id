<!doctype html>
<html class="fixed">

<head>
    <title>Registrar Senha - InpexID</title>
    <!-- Basic -->
    <meta charset="UTF-8">
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Web Fonts  -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800|Shadows+Into+Light"
        rel="stylesheet" type="text/css">

    <link rel="icon" href="{{ asset('images/logos/inpexid.svg') }}" type="image/x-icon">
    <!-- Vendor CSS -->
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/css/theme.css', 'resources/js/app.js', 'resources/js/theme.js', 'resources/js/custom.js'])
</head>

<body>
    <!-- start: page -->
    <section class="body-sign">
        <div class="center-sign">
            <div class="panel card-sign">
                <div class="card-title-sign mt-3 mb-4 text-end" style="display: flex">
                    <a href="#" class="logo float-start teltex-logo">
                        <img src="{{ asset('images/logos/inpexid.svg') }}" height="70" alt="Teltex" />
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf
                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                        <div class="form-group mb-3">
                            <div class="clearfix">
                                <label class="col-form-label" for="password">Nova Senha</label>
                            </div>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" autocomplete="new-password" />
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="clearfix">
                                <label class="col-form-label" for="password_confirmation">Confirmar Senha</label>
                            </div>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" autocomplete="new-password" />
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-end">
                                <button type="submit" class="btn btn-primary mt-2">Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- end: page -->

    <!-- Vendor -->
    @include('components.footer')
</body>

</html>