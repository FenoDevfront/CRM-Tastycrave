@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card">
                <div class="card-header">Connexion</div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    <p>Veuillez vous connecter pour accéder au CRM.</p>
                    <a href="{{ route('login.google') }}" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                            <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.25C3.216 7.22 3 7.9 3 8.5s.216 1.28.508 1.834c.632 1.842 2.405 3.25 4.492 3.25 1.056 0 2.062-.37 2.866-.975l2.284 2.284A7.95 7.95 0 0 1 8 16Z"/>
                        </svg>
                        Se connecter avec Google
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection