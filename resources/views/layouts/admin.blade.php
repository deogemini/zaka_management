<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
	<meta name="author" content="AdminKit">
	<meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="{{ asset('adminkit/img/icons/icon-48x48.png') }}" />

	<link rel="canonical" href="https://demo-basic.adminkit.io/" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'ZAKA MANAGEMENT SYSTEM') }}</title>

	<link href="{{ asset('adminkit/css/app.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
	<div class="wrapper">
		<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<a class="sidebar-brand" href="{{ route('dashboard') }}">
          <span class="align-middle">{{ config('app.name', 'ZAKA MANAGEMENT SYSTEM') }}</span>
        </a>

				<ul class="sidebar-nav">
					<li class="sidebar-header">
						Pages
					</li>

					<li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('dashboard') }}">
              <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
            </a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('kandas.*') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('kandas.index') }}">
              <i class="align-middle" data-feather="map"></i> <span class="align-middle">Kanda Management</span>
            </a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('jumuiyas.*') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('jumuiyas.index') }}">
              <i class="align-middle" data-feather="users"></i> <span class="align-middle">Jumuiya Management</span>
            </a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('mwanajumuiya.*') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('mwanajumuiya.index') }}">
              <i class="align-middle" data-feather="user"></i> <span class="align-middle">Mwanajumuiya Management</span>
            </a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('zakas.*') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('zakas.index') }}">
              <i class="align-middle" data-feather="dollar-sign"></i> <span class="align-middle">Zaka Management</span>
            </a>
					</li>
                    <li class="sidebar-item {{ request()->routeIs('watotos.*') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('watotos.index') }}">
              <i class="align-middle" data-feather="smile"></i> <span class="align-middle">Watoto Management</span>
            </a>
					</li>

                    <li class="sidebar-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <a data-bs-target="#reports" data-bs-toggle="collapse" class="sidebar-link collapsed">
                            <i class="align-middle" data-feather="bar-chart-2"></i> <span class="align-middle">Report Management</span>
                        </a>
                        <ul id="reports" class="sidebar-dropdown list-unstyled collapse {{ request()->routeIs('reports.*') ? 'show' : '' }}" data-bs-parent="#sidebar">
                            <li class="sidebar-item {{ request()->routeIs('reports.zaka') ? 'active' : '' }}"><a class="sidebar-link" href="{{ route('reports.zaka') }}">Zaka Report</a></li>
                            <li class="sidebar-item {{ request()->routeIs('reports.jumuiya') ? 'active' : '' }}"><a class="sidebar-link" href="{{ route('reports.jumuiya') }}">Jumuiya Report</a></li>
                            <li class="sidebar-item {{ request()->routeIs('reports.kanda') ? 'active' : '' }}"><a class="sidebar-link" href="{{ route('reports.kanda') }}">Kanda Report</a></li>
                        </ul>
					</li>

                    @if(Auth::user()?->role === 'admin')
                    <li class="sidebar-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('users.index') }}">
              <i class="align-middle" data-feather="shield"></i> <span class="align-middle">User Management</span>
            </a>
					</li>
                    <li class="sidebar-item {{ request()->routeIs('audit_trails.*') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('audit_trails.index') }}">
              <i class="align-middle" data-feather="activity"></i> <span class="align-middle">Audit Trails</span>
            </a>
					</li>
                    @endif

					<li class="sidebar-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('profile.edit') }}">
              <i class="align-middle" data-feather="user"></i> <span class="align-middle">Profile</span>
            </a>
					</li>

					<li class="sidebar-header">
						Tools & Components
					</li>

					<li class="sidebar-item">
						<a class="sidebar-link" href="#">
              <i class="align-middle" data-feather="square"></i> <span class="align-middle">Buttons</span>
            </a>
					</li>
				</ul>
			</div>
		</nav>

		<div class="main">
			<nav class="navbar navbar-expand navbar-light navbar-bg">
				<a class="sidebar-toggle js-sidebar-toggle">
          <i class="hamburger align-self-center"></i>
        </a>

				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                <i class="align-middle" data-feather="settings"></i>
              </a>

							<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                <img src="{{ asset('adminkit/img/avatars/avatar.jpg') }}" class="avatar img-fluid rounded me-1" alt="Charles Hall" /> <span class="text-dark">{{ Auth::user()->name ?? 'Guest' }}</span>
              </a>
							<div class="dropdown-menu dropdown-menu-end">
								<a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
								<div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">Log out</a>
                                </form>
							</div>
						</li>
					</ul>
				</div>
			</nav>

			<main class="content">
				<div class="container-fluid p-0">
                    @yield('content')
				</div>
			</main>

			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-start">
							<p class="mb-0">
								ZAKA MANAGEMENT SYSTEM - BOMBAMBILI PARISH &copy; {{ date('Y') }} &nbsp; designed by <strong>EPORT SOLUTIONS LIMITED</strong>
							</p>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

	<script src="{{ asset('adminkit/js/app.js') }}"></script>
    @stack('scripts')

</body>

</html>
