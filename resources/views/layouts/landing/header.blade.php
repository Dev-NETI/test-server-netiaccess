<!-- navbar login -->
<nav class="navbar navbar-expand-lg shadow-none">
	<div class="container px-0">
		<div class="d-flex text-center">
			<a class="navbar-brand" href="#loginemp"><img src="{{asset('assets/images/oesximg/logo/logo-min.svg')}}" width="auto" height="50px">
				<br><small class="fs-6 text-secondary">
					<h4><i>ONLINE ENROLLMENT X</i></h4>
				</small>
		</div>
		<div class="order-lg-3">
			<div class="d-flex align-items-center">
				</a>
				<!-- @if(Route::has('login'))
				@auth
				@if(Auth::user()->u_type === 1)
				<a href="{{route('a.dashboard')}}" class="btn btn-outline-primary ms-2 active">Go to Dashboard</a>
				@elseif(Auth::guard('trainee')->check() && Auth::guard('trainee')->user()->u_type === 2)
				<a href="{{route('t.dashboard')}}" class="btn btn-outline-success ms-2 active">Go to Dashboard</a>
				@endif
				@else
				<a href="{{route('t.login')}}" class="btn btn-outline-primary ms-2 active">Sign in</a>
				<a href="{{route('registration')}}" class="btn btn-outline-info ms-2">Sign up</a>
				@endauth
				@endif -->

				<!-- @if(Route::has('login'))
				@auth
				@if(Auth::user()->u_type === 1)
				<a href="{{ route('a.dashboard') }}" class="btn btn-outline-primary ms-2 active">Go to Dashboard</a>
				@endif
				@endauth
				@endif

				@if(Route::has('t.login'))
				@auth('trainee')
				@if(Auth::guard('trainee')->user()->u_type === 2)
				<a href="{{ route('t.dashboard') }}" class="btn btn-outline-primary ms-2 active">Go to Dashboard</a>
				@endif
				@endauth
				@endif -->

				@if (Auth::check() || Auth::guard('trainee')->check())
				@if(Route::has('login'))
				@auth
				@if(Auth::user()->u_type === 1)
				<a href="{{ route('a.dashboard') }}" class="btn btn-outline-primary ms-2 active">Go to Dashboard</a>
				@elseif (Auth::user()->u_type === 3)
				<a href="{{ route('c.dashboard') }}" class="btn btn-outline-primary ms-2 active">Go to Dashboard</a>
				@elseif (Auth::user()->u_type === 2)
				<a href="{{ route('i.dashboard') }}" class="btn btn-outline-primary ms-2 active">Go to Dashboard</a>
				@elseif (Auth::user()->u_type === 4)
				<a href="{{ route('te.dashboard') }}" class="btn btn-outline-primary ms-2 active">Go to Dashboard</a>
				@endif
				@endauth
				@endif

				@if(Route::has('t.login'))
				@auth('trainee')
				@if(Auth::guard('trainee')->user()->u_type === 2)
				<a href="{{ route('t.dashboard') }}" class="btn btn-outline-primary ms-2 active">Go to Dashboard</a>
				@endif
				@endauth
				@endif

				@else
				<a href="{{route('t.login')}}" class="d-none d-sm-inline-block btn btn-outline-primary ms-2 active">Login</a>
				<a href="{{route('registration')}}" class="d-none d-sm-inline-block btn btn-outline-info ms-2">Register</a>
				@endif

				<!-- Button -->
				<button class="navbar-toggler collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#navbar-default" aria-controls="navbar-default" aria-expanded="false" aria-label="Toggle navigation">
					<span class="icon-bar top-bar mt-0"></span>
					<span class="icon-bar middle-bar"></span>
					<span class="icon-bar bottom-bar"></span>
				</button>
			</div>

		</div>

		<!-- Collapse -->
		<div class="collapse navbar-collapse" id="navbar-default">

			<ul class="navbar-nav mx-auto">

				<li class="nav-item">
					<a class="nav-link" href="/">
						Home
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{route('dataprivacy')}}">
						Data Privacy
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{ url('/') }}#courses">
						Courses
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{route('contact')}}">
						Contact Us
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{route('faq')}}">
						FAQ
					</a>
				</li>
				<li class="nav-item d-block d-sm-none">
					<a class="nav-link" href="{{route('t.login')}}">
						Login
					</a>
				</li>
				<li class="nav-item d-block d-sm-none">
					<a class="nav-link" href="{{route('registration')}}">
						Register
					</a>
				</li>
			</ul>

		</div>

	</div>
</nav>