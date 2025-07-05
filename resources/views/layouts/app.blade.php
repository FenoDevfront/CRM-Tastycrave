<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Tastycrave CRM')</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="icon" type="image/jpeg" href="{{ asset('images/Tastycrave.jpg') }}">

    @stack('styles')

    <style>
        :root {
            --primary-color: #FF7F50; /* Placeholder: Coral - Please replace with your logo's exact hex color */
            --secondary-color: #6c757d; /* Bootstrap default grey */
            --dark-color: #343a40; /* Dark text/background */
            --light-bg: #f8f9fa; /* Light background */
            --white: #ffffff;
            --border-color: #e9ecef;
            --shadow-light: rgba(0, 0, 0, 0.08); /* Slightly stronger light shadow */
            --shadow-medium: rgba(0, 0, 0, 0.15); /* Slightly stronger medium shadow */
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-color);
        }

        .navbar {
            background-color: var(--white);
            box-shadow: 0 4px 15px var(--shadow-light); /* Softer, more spread out shadow */
            padding: 1rem 0;
            border-radius: 0 0 1rem 1rem; /* Rounded bottom corners */
        }
        .navbar-brand {
            font-weight: 700;
            color: var(--dark-color) !important;
            display: flex;
            align-items: center;
        }
        .navbar-brand img {
            height: 38px; /* Slightly larger logo */
            margin-right: 12px;
            border-radius: 8px; /* More rounded logo */
        }
        .nav-link {
            color: var(--secondary-color) !important;
            font-weight: 600;
            margin-right: 20px; /* More spacing */
            transition: color 0.3s ease, transform 0.2s ease;
        }
        .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-2px); /* Subtle hover effect */
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 8px 25px var(--shadow-medium); /* Stronger, softer shadow */
            border-radius: 0.75rem; /* More rounded */
        }
        .dropdown-item {
            color: var(--dark-color);
            padding: 0.85rem 1.5rem; /* More padding */
        }
        .dropdown-item:hover {
            background-color: var(--light-bg);
            color: var(--primary-color);
        }

        main.container {
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .card {
            border-radius: 1rem; /* More rounded cards */
            border: 1px solid var(--border-color);
            box-shadow: 0 0.75rem 2rem var(--shadow-light); /* Softer, more pronounced shadow */
            overflow: hidden;
        }
        .card-header {
            background-color: var(--white);
            border-bottom: 1px solid var(--border-color);
            font-weight: 700;
            color: var(--dark-color);
            padding: 1.25rem 1.5rem;
            font-size: 1.1rem;
            border-radius: 1rem 1rem 0 0; /* Rounded top corners for header */
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 600;
            padding: 0.85rem 1.75rem; /* More padding */
            border-radius: 0.75rem; /* More rounded buttons */
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: color-mix(in srgb, var(--primary-color) 85%, black); /* Darken on hover */
            border-color: color-mix(in srgb, var(--primary-color) 85%, black);
            transform: translateY(-3px); /* More pronounced hover effect */
            box-shadow: 0 5px 15px var(--shadow-medium);
        }
        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            font-weight: 600;
            padding: 0.85rem 1.75rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #5a6268;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px var(--shadow-medium);
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: var(--dark-color);
        }
        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #e0a800;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #c82333;
        }
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
        .btn-info:hover {
            background-color: #138496;
            border-color: #138496;
        }

        .table {
            border-radius: 0.75rem; /* More rounded table */
            overflow: hidden;
            margin-bottom: 0;
        }
        .table thead th {
            background-color: var(--light-bg);
            border-bottom: 2px solid var(--border-color);
            color: var(--secondary-color);
            font-weight: 700;
            padding: 1rem 1.5rem;
        }
        .table tbody tr {
            transition: background-color 0.2s ease;
        }
        .table tbody tr:hover {
            background-color: #f2f2f2;
        }
        .table td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
        }

        .alert {
            border-radius: 0.75rem; /* More rounded alerts */
            font-weight: 500;
        }

        .badge {
            padding: 0.6em 0.9em; /* Slightly larger badges */
            border-radius: 0.5rem; /* More rounded badges */
            font-size: 0.9em;
            font-weight: 700;
        }

        /* Custom card styles for dashboard stats */
        .card-stat {
            border-radius: 1rem; /* More rounded stat cards */
            box-shadow: 0 0.5rem 1.5rem var(--shadow-light); /* Softer, more pronounced shadow */
            color: var(--white);
            text-align: center;
            padding: 1.8rem; /* More padding */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-stat:hover {
            transform: translateY(-7px); /* More pronounced hover effect */
            box-shadow: 0 0.8rem 2.5rem var(--shadow-medium);
        }
        .card-stat h4 {
            font-size: 2.8rem; /* Larger font size */
            margin-bottom: 0.6rem;
        }
        .card-stat h6 {
            font-size: 1.1rem; /* Slightly larger font size */
            opacity: 0.9; /* Less transparent */
        }
        .bg-primary-custom {
            background-color: var(--primary-color) !important;
        }
        .bg-success-custom {
            background-color: #28a745 !important;
        }
        .bg-danger-custom {
            background-color: #dc3545 !important;
        }
        .bg-warning-custom {
            background-color: #ffc107 !important;
            color: var(--dark-color) !important;
        }
        .bg-info-custom {
            background-color: #17a2b8 !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="/images/Tastycrave.jpg" alt="Tastycrave Logo">
                Tastycrave CRM
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('dashboard') }}">Tableau de bord</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Produits</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Déconnexion
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        @yield('content')
    </main>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>