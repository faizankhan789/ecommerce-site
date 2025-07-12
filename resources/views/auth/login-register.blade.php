<!doctype html>
<html lang="en">
<head>
  <title>E-Commerce - Login/Register</title>
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
    
    [type="checkbox"]:checked,
    [type="checkbox"]:not(:checked){
      display: none;
    }
    
    .checkbox:checked + label:before {
      transform: translateX(44px) rotate(-270deg);
    }
    
    .checkbox:checked + label,
    .checkbox:not(:checked) + label{
      position: relative;
      display: block;
      text-align: center;
      width: 60px;
      height: 16px;
      border-radius: 8px;
      padding: 0;
      margin: 10px auto;
      cursor: pointer;
      background-color: #ffeba7;
    }
    
    .checkbox:checked + label:before,
    .checkbox:not(:checked) + label:before{
      position: absolute;
      display: block;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      color: #ffeba7;
      background-color: #020305;
      font-family: 'unicons';
      content: '\eb4f';
      z-index: 20;
      top: -10px;
      left: -10px;
      line-height: 36px;
      text-align: center;
      font-size: 24px;
      transition: all 0.5s ease;
    }
    
    .card-3d-wrap {
      position: relative;
      width: 440px;
      max-width: 100%;
      height: 400px;
      -webkit-transform-style: preserve-3d;
      transform-style: preserve-3d;
      perspective: 800px;
      margin-top: 60px;
    }
    
    .card-3d-wrapper {
      width: 100%;
      height: 100%;
      position:absolute;
      -webkit-transform-style: preserve-3d;
      transform-style: preserve-3d;
      transition: all 600ms ease-out; 
    }
    
    .card-front, .card-back {
      width: 100%;
      height: 100%;
      background-color: #2b2e38;
      background-image: url('/img/pattern_japanese-pattern-2_1_2_0-0_0_1__ffffff00_000000.png');
      position: absolute;
      border-radius: 6px;
      -webkit-transform-style: preserve-3d;
      transform-style: preserve-3d;
      -webkit-backface-visibility: hidden;
      backface-visibility: hidden;
    }
    
    .card-back {
      transform: rotateY(180deg);
    }
    
    .checkbox:checked ~ .card-3d-wrap .card-3d-wrapper {
      transform: rotateY(180deg);
    }
    
    .center-wrap{
      position: absolute;
      width: 100%;
      padding: 0 35px;
      top: 50%;
      left: 0;
      transform: translate3d(0, -50%, 35px) perspective(100px);
      z-index: 20;
      display: block;
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
    }
    
    .link:hover {
      color: #c4c3ca;
    }
  </style>
</head>
<body>
	<div class="min-h-screen flex items-center justify-center">
		<div class="w-full px-4">
			<div class="text-center">
				<h6 class="mb-0 pb-3">
					<span class="px-5 text-yellow-200 font-bold">Log In</span>
					<span class="px-5 text-yellow-200 font-bold">Sign Up</span>
				</h6>
				<input class="checkbox" type="checkbox" id="reg-log" name="reg-log"/>
				<label for="reg-log"></label>
				
				<div class="card-3d-wrap mx-auto">
					<div class="card-3d-wrapper">
						<!-- Login Form -->
						<div class="card-front">
							<div class="center-wrap">
								<div class="text-center">
									<h4 class="mb-8 pb-3 text-2xl font-semibold text-yellow-200">Log In</h4>
									<form method="POST" action="{{ route('login.post') }}">
										@csrf
										<div class="relative mb-4">
											<input type="email" name="email" class="form-style" placeholder="Email" value="{{ old('email') }}" required>
											<i class="input-icon uil uil-at"></i>
										</div>
										<div class="relative mb-4">
											<input type="password" name="password" class="form-style" placeholder="Password" required>
											<i class="input-icon uil uil-lock-alt"></i>
										</div>
										<button type="submit" class="btn-submit mt-4">LOGIN</button>
									</form>
									<p class="mb-0 mt-4 text-center text-sm">
										<a href="{{ route('password.request') }}" class="link">Forgot your password?</a>
									</p>
								</div>
							</div>
						</div>
						
						<!-- Register Form -->
						<div class="card-back">
							<div class="center-wrap">
								<div class="text-center">
									<h4 class="mb-5 pb-3 text-2xl font-semibold text-yellow-200">Sign Up</h4>
									<form method="POST" action="{{ route('register.post') }}">
										@csrf
										<div class="relative mb-4">
											<input type="text" name="name" class="form-style" placeholder="Full Name" value="{{ old('name') }}" required>
											<i class="input-icon uil uil-user"></i>
										</div>
										<div class="relative mb-4">
											<input type="tel" name="phone" class="form-style" placeholder="Phone Number" value="{{ old('phone') }}">
											<i class="input-icon uil uil-phone"></i>
										</div>
										<div class="relative mb-4">
											<input type="email" name="email" class="form-style" placeholder="Email" value="{{ old('email') }}" required>
											<i class="input-icon uil uil-at"></i>
										</div>
										<div class="relative mb-4">
											<input type="password" name="password" class="form-style" placeholder="Password" required>
											<i class="input-icon uil uil-lock-alt"></i>
										</div>
										<button type="submit" class="btn-submit mt-4">REGISTER</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	@if ($errors->any())
		<script>
			alert('{{ $errors->first() }}');
		</script>
	@endif
</body>
</html>