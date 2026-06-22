<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Core Standalone Background Configuration */
        body {
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, sans-serif;
        }

        /* Typography Header Sync */
        .page-header-wrapper {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #0f172a;
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            font-size: 0.88rem;
            color: #64748b;
            font-weight: 400;
        }

        /* Minimal UI Authentication Container Card */
        .login-card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.04);
            overflow: hidden;
        }

        /* Sleek Clean Form Controls */
        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 0.65rem 1rem;
            font-size: 0.95rem;
            color: #1e293b;
            background-color: #ffffff;
            transition: all 0.15s ease;
        }

        .form-control:focus {
            border-color: #024cab;
            box-shadow: 0 0 0 3px rgba(2, 76, 171, 0.1);
            background-color: #ffffff;
            color: #1e293b;
        }

        /* Modern Primary Action Button Alignment */
        .btn-action-primary {
            background-color: #024cab;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.92rem;
            padding: 0.7rem 1.25rem;
            border-radius: 12px;
            border: none;
            transition: background-color 0.2s ease;
        }

        .btn-action-primary:hover {
            background-color: #00367a;
            color: #ffffff;
        }

        /* Soft Validation Feedback Design System */
        .alert-danger-soft {
            background-color: #fef2f2;
            border: 1px solid rgba(239, 104, 104, 0.15);
            color: #ef4444;
            font-size: 0.88rem;
            font-weight: 500;
            border-radius: 10px;
        }

        .form-control.is-invalid {
            border-color: #ef4444;
            background-image: none;
        }
        
        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .invalid-feedback {
            font-size: 0.82rem;
            font-weight: 500;
            color: #ef4444;
            margin-top: 0.35rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                
                <div class="page-header-wrapper">
                    <h2 class="page-title">Admin Portal</h2>
                    <div class="page-subtitle">Sign in to access your administrative dashboard toolsets.</div>
                </div>

                <div class="card login-card border-0">
                    <div class="card-body p-4 p-sm-5">
                        
                        @if($errors->any())
                            <div class="alert alert-danger-soft alert-dismissible fade show mb-4 p-3" role="alert">
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                                <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" style="font-size: 0.75rem; padding: 1.15rem;"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.login') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" autocomplete="username" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" autocomplete="current-password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-action-primary w-100 mt-2">Login to Account</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>