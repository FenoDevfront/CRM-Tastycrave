<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gemini CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/jpeg" href="{{ asset('images/Tastycrave.jpg') }}">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
        }
        .container-fluid {
            padding: 0;
        }
        .login-wrapper {
            height: 100vh;
        }
        .login-image-side {
            background-image: url('/images/Fond.jpg');
            background-size: cover;
            background-position: center;
        }
        .login-form-side {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        .login-form {
            max-width: 400px;
            width: 100%;
            padding: 4rem 2rem;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }
        .logo {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1.5rem;
            border: 4px solid #eee;
        }
        h1 {
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }
        p {
            color: #666;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        .btn-google {
            background-color: #fff;
            color: #444;
            border: 1px solid #ddd;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .btn-google:hover {
            background-color: #f7f7f7;
            border-color: #ccc;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .btn-google img {
            margin-right: 12px;
            width: 22px;
            height: 22px;
        }
        .alert {
            margin-top: 1.5rem;
            border-radius: 10px;
        }
        @media (max-width: 768px) {
            .login-image-side {
                display: none;
            }
            .login-form-side {
                background-image: url('https://source.unsplash.com/random/1200x900/?breaded-chicken,breadcrumbs');
                background-size: cover;
                background-position: center;
            }
            .login-form {
                background-color: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(5px);
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row login-wrapper">
            <div class="col-md-6 col-lg-7 login-image-side"></div>
            <div class="col-md-6 col-lg-5 d-flex align-items-center justify-content-center bg-light">
                <div class="login-form">
                    <img src="{{ asset('images/Tastycrave.jpg') }}" alt="Logo Tastycrave" class="logo">
                    <h1>Bienvenue !</h1>
                    <p>Connectez-vous pour gérer vos produits.</p>
                    <a href="{{ route('login.google') }}" class="btn btn-google">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google logo">
                        Continuer avec Google
                    </a>
                    @if (session('error'))
                        <div class="alert alert-danger mt-4">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>
