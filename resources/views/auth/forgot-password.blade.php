<!doctype html>
<html lang="en">
<head>
  <title>E-Commerce - Forgot Password</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.9/css/unicons.css">
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  <script src="{{ mix('js/app.js') }}" defer></script>
  <style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700,800,900');
    
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #1f2029;
    }
    
    .form-style {
      padding: 13px 20px;
      padding-left: 55px;
      height: 48px;
      width: 100%;
      font-weight: 500;
      border-radius: 4px;
      font-size: 14px;
      line-height: 22px;
      letter-spacing: 0.5px;
      outline: none;
      color: #c4c3ca;
      background-color: #1f2029;
      border: none;
      transition: all 200ms linear;
      box-shadow: 0 4px 8px 0 rgba(21,21,21,.2);
    }
    
    .form-style:focus,
    .form-style:active {
      border: none;
      outline: none;
      box-shadow: 0 4px 8px 0 rgba(21,21,21,.2);
    }
    
    .input-icon {
      position: absolute;
      top: 0;
      left: 18px;
      height: 48px;
      font-size: 24px;
      line-height: 48px;
      text-align: left;
      transition: all 200ms linear;
      color: #c4c3ca;
    }
    
    .btn-submit {
      border-radius: 4px;
      height: 44px;
      font-size: 13px;
      font-weight: 600;
      text-transform: uppercase;
      transition: all 200ms linear;
      padding: 0 30px;
      letter-spacing: 1px;
      display: inline-flex;
      align-items: center;
      background-color: #ffeba7;
      color: #000000;
      box-shadow: 0 8px 24px 0 rgba(255,235,167,.2);
      text-decoration: none;
    }
    
    .btn-submit:hover {
      background-color: #000000;
      color: #ffeba7;
      box-shadow: 0 8px 24px 0 rgba(16,39,112,.2);
    }
    
    .link {
      color: #ffeba7;
      font-weight: 500;
      transition: all 200ms linear;
      text-decoration: none;
    }
    
    .link:hover {
      color: #c4c3ca;
    }
    
    .card-wrapper {
      width: 440px;
      max-width: 100%;
      background-color: #2b2e38;
      padding: 40px;
      border-radius: 6px;
      box-shadow: 0 4px 8px 0 rgba(21,21,21,.2);
    }
    
    .success-message {
      background-color: #4ade80;
      color: #000;
      padding: 12px 20px;
      border-radius: 4px;
      margin-bottom: 20px;
      font-weight: 500;
    }
  </style>
</head>
<body>
	<div class="min-h-screen flex items-center justify-center">
		<div class="w-full px-4">
			<div class="flex justify-center">
				<div class="card-wrapper">
					<div class="text-center">
						<h4 class="mb-8 text-2xl font-semibold text-yellow-200">Reset Password</h4>
						
						@if (session('status'))
							<div class="success-message">
								{{ session('status') }}
							</div>
						@endif
						
						<p class="text-gray-400 mb-6">Enter your email address and we'll send you a link to reset your password.</p>
						
						<form method="POST" action="{{ route('password.email') }}">
							@csrf
							<div class="relative mb-6">
								<input type="email" name="email" class="form-style" placeholder="Email" value="{{ old('email') }}" required>
								<i class="input-icon uil uil-at"></i>
							</div>
							
							@error('email')
								<p class="text-red-500 text-sm mb-4">{{ $message }}</p>
							@enderror
							
							<button type="submit" class="btn-submit mb-4">SEND RESET LINK</button>
						</form>
						
						<p class="text-center">
							<a href="{{ route('auth.login-register') }}" class="link">Back to Login</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>