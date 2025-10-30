<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login | CShopU</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background: #fff;
      overflow-x: hidden;
    }

    .login-container {
      display: flex;
      min-height: 100vh;
    }

    /* LEFT SIDE (Hero Section) */
    .left-side {
      flex: 1;
      background: url('{{ asset("images/building.jpg") }}') center/cover no-repeat;
      position: relative;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 3rem;
    }

    .left-overlay {
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(128,0,0,0.7);
      z-index: 1;
    }

    .left-content {
      position: relative;
      z-index: 2;
    }

    .left-content h1 {
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 1rem;
    }

    .left-content .typed-text {
      color: #facc15;
      font-weight: 600;
      font-size: 1.5rem;
    }

    /* RIGHT SIDE (Login Form) */
    .right-side {
      flex: 1;
      background-color: #fdfdfd;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 3rem;
    }

    .login-card {
      width: 100%;
      max-width: 420px;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(128,0,0,0.1);
      padding: 2.5rem;
    }

    .login-card h2 {
      text-align: center;
      color: #800000;
      font-weight: 700;
      margin-bottom: 1.5rem;
    }

    .form-control {
      border-radius: 12px;
      padding: 0.75rem 1rem;
    }

    .btn-maroon {
      background-color: #800000;
      color: #fff;
      border-radius: 50px;
      padding: 0.7rem 1.5rem;
      width: 100%;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-maroon:hover {
      background-color: #a00000;
      transform: scale(1.03);
    }

    .text-muted a {
      text-decoration: none;
      color: #800000;
      font-weight: 600;
    }

    .text-muted a:hover {
      color: #b30000;
    }

    @media (max-width: 992px) {
      .login-container {
        flex-direction: column;
      }
      .left-side {
        height: 300px;
      }
    }
  </style>
</head>

<body>

<div class="login-container">
  <!-- LEFT SIDE -->
  <div class="left-side">
    <div class="left-overlay"></div>
    <div class="left-content">
      <h1>Welcome to <span style="color:#facc15;">CShopU</span></h1>
      <p class="lead">Your One Stop Shop for Campus <span id="typed-text" class="typed-text"></span></p>
    </div>
  </div>

  <!-- RIGHT SIDE -->
  <div class="right-side">
    <div class="login-card">
      <h2>Login to CShopU</h2>

      <!-- Session Status -->
      <x-auth-session-status class="mb-4" :status="session('status')" />

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email or Student/Employee ID -->
        <div class="mb-3">
          <label for="login" class="form-label">Student/Employee ID or Email</label>
          <input id="login" type="text" name="login" class="form-control" value="{{ old('login') }}" required autofocus>
          <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input id="password" type="password" name="password" class="form-control" required>
          <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
          <label class="form-check-label" for="remember_me">Remember me</label>
        </div>

        <button type="submit" class="btn btn-maroon">Log in</button>

        <div class="mt-3 text-center text-muted">
          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">Forgot password?</a><br>
          @endif
          Don’t have an account? <a href="{{ route('register') }}">Register</a>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Typed.js Animation -->
<script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
<script>
  new Typed("#typed-text", {
    strings: ["Products", "Services", "Needs."],
    typeSpeed: 40,
    backSpeed: 30,
    backDelay: 1000,
    loop: true
  });
</script>

</body>
</html>
