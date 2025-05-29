<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Billing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), #0056b3);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,186.7C384,213,480,235,576,213.3C672,192,768,128,864,128C960,128,1056,192,1152,208C1248,224,1344,192,1392,176L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-repeat: no-repeat;
            background-position: bottom;
            background-size: cover;
            opacity: 0.4;
        }

        .feature-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .cta-section {
            background-color: #f8f9fa;
            padding: 80px 0;
        }

        .btn-hero {
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .navbar {
            padding: 1rem 0;
            background-color: rgba(255, 255, 255, 0.95) !important;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color) !important;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-water me-2"></i>
                Water Billing System
            </a>
            <div class="d-flex">
                <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
            </div>
        </div>
    </nav>

    Hero Section
    <section class="hero-section">
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Smart Water Billing Management</h1>
                    
                 
                </div>
            </div>
        </div>
    

   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 