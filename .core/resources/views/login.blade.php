@extends ('layouts.default')

@section('mainbody')

<style>
    #loginBox {
        position: fixed;
        top: 20%;
        left: 50%;
        transform: translateX(-50%);
        z-index: 5;
        width: min(22rem, 92vw);
        box-sizing: border-box;
        border-radius: 1rem;
        border: 4px solid #34504a;
        background-color: rgba(255, 255, 255, 0.92);
        padding: 1.2rem;
        font-size: 0.9rem;
    }
    #loginBox h1 { font-size: 1.3rem; text-align: center; margin: 0 0 1rem; }
    #loginBox label { display: block; margin-top: 0.6rem; }
    #loginBox .form-control { width: 100%; box-sizing: border-box; }
    #loginBox .actions { margin-top: 1rem; text-align: center; }
    #loginBox .error { color: #a40000; margin-bottom: 0.6rem; }
</style>

<div id="loginBox">
    <h1>Admin login</h1>

    @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" class="form-control"
               value="{{ old('email') }}" required autofocus autocomplete="username">

        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control"
               required autocomplete="current-password">

        <label>
            <input type="checkbox" name="remember" value="1"> Keep me logged in
        </label>

        <div class="actions">
            <button type="submit" class="btn btn-success">Log in</button>
            <a href="/" class="btn btn-warning">Back to the map</a>
        </div>
    </form>
</div>

@endsection
