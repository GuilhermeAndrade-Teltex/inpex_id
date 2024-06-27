<!doctype html>
<html class="fixed">

<head>
    <title>Login - Teltex Tecnologia</title>
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

<!-- TELA DE LOGIN 1 -->

<body style="background-image: url('{{asset("images/logos/arte_ponto_1920.jpg")}}');">
    <!-- start: page -->
    <div class="content_body">
        <input type="hidden" name="session" :status="session('status')">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div id="login_card">
                <div id="image">
                    <img src="{{ asset('images/logos/inpexid.svg') }}" id="logo_inpex">
                </div>
                <div id="input_login">
                    <input id="email" name="email" type="email" :value="old('email')"
                        class="form-control form-control-lg" required autofocus autocomplete="username"
                        placeholder="E-mail">
                    @if ($errors->has('email'))
                        <span>
                            @foreach ($errors->get('email') as $error)
                                {{ $error }}
                            @endforeach
                        </span>
                    @endif
                    <input id="password" class="form-control form-control-lg" type="password" name="password" required
                        autocomplete="current-password" placeholder="Senha">
                    @if ($errors->has('password'))
                        <span>
                            @foreach ($errors->get('password') as $error)
                                {{ $error }}
                            @endforeach
                        </span>
                    @endif
                    <button class="btn btn-primary inputs" id="btn_login"> LOGIN </button>
                </div>
            </div>
        </form>
    </div>
    <!-- end: page -->

    <!-- Vendor -->
    @include('components.footer')
</body>

</html>