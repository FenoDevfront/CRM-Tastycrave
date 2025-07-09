<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Tastycrave CRM')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/jpeg" href="/images/Tastycrave.jpg">
    @stack('styles')
    <style>
        :root {
            --bs-primary-rgb: 163, 102, 40; /* #a36628 */
            --bs-primary: #a36628;
            --bs-secondary-rgb: 158, 123, 88; /* #9e7b58 */
            --bs-secondary: #9e7b58;
            --bs-tertiary-rgb: 206, 183, 159; /* #ceb79f */
            --bs-tertiary: #ceb79f;
            --bs-body-font-family: 'Inter', sans-serif;
        }

        [data-bs-theme="light"] {
            --bs-body-bg: #f8f9fa;
            --bs-body-color: #212529;
            --bs-tertiary-bg: #ffffff;
            --bs-emphasis-color: #000;
        }

        [data-bs-theme="dark"] {
            --bs-body-bg: #1a1a1a;
            --bs-body-color: #dee2e6;
            --bs-tertiary-bg: #2c2c2c;
            --bs-emphasis-color: #fff;
        }

        body {
            padding-top: 70px;
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
        }

        .navbar {
            border-bottom: 1px solid var(--bs-border-color);
            background-color: var(--bs-tertiary-bg) !important;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--bs-secondary);
        }

        .nav-link {
            font-weight: 500;
            color: var(--bs-emphasis-color);
            transition: color 0.2s ease-in-out;
        }

        .nav-link:hover {
            color: var(--bs-primary);
        }

        .nav-item.active .nav-link {
            color: var(--bs-primary) !important;
            font-weight: 700;
            border-bottom: 3px solid var(--bs-primary);
        }

        .card {
            border: 1px solid var(--bs-border-color);
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            background-color: var(--bs-tertiary-bg);
        }
        
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.07);
        }

        .card-header {
            background-color: var(--bs-tertiary-bg);
            border-bottom: 1px solid var(--bs-border-color);
            font-weight: 600;
        }
        
        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .page-item.active .page-link {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .theme-switcher {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg shadow-sm fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="/images/Tastycrave.jpg" alt="Tastycrave Logo" style="height: 32px; margin-right: 10px;">
                Tastycrave CRM
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Tableau de bord</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Produits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('stock.index') }}">Stock</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto d-flex flex-row align-items-center">
                    <li class="nav-item me-3">
                        <a class="nav-link theme-switcher" id="theme-switcher">
                            <i class="fas fa-moon"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
    <script>
        // Theme Switcher Logic
        (function() {
            const themeSwitcher = document.getElementById('theme-switcher');
            const themeIcon = themeSwitcher.querySelector('i');
            const htmlElement = document.documentElement;

            function setTheme(theme) {
                htmlElement.setAttribute('data-bs-theme', theme);
                if (theme === 'dark') {
                    themeIcon.classList.remove('fa-moon');
                    themeIcon.classList.add('fa-sun');
                } else {
                    themeIcon.classList.remove('fa-sun');
                    themeIcon.classList.add('fa-moon');
                }
                localStorage.setItem('theme', theme);
            }

            themeSwitcher.addEventListener('click', () => {
                const currentTheme = htmlElement.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                setTheme(newTheme);
            });

            // Load theme from local storage on page load
            const savedTheme = localStorage.getItem('theme') || 'light';
            setTheme(savedTheme);
        })();
    </script>

    @stack('scripts')

    <script type="text/javascript">
        jQuery(document).ready(function(){
            let selector = ".navbar-nav .nav-item .nav-link";
            
            // Set active class on page load based on URL
            const currentUrl = window.location.href;
            jQuery(selector).each(function() {
                const linkUrl = jQuery(this).attr('href').split('#')[0].replace(/\/$/, '');
                const currentPath = currentUrl.split('#')[0].split('?')[0].replace(/\/$/, '');
                if (linkUrl === currentPath) {
                    jQuery(this).closest('.nav-item').addClass('active');
                }
            });

            // The click event for active class should be after the initial setup
            jQuery(selector).on('click', function(){
                jQuery(selector).closest('.nav-item').removeClass('active');
                jQuery(this).closest('.nav-item').addClass('active');
            });
        });
    </script>
</body>
</html>
