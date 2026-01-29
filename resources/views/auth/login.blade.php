<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - T-Store</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 450px;
            animation: slideUp 0.5s ease;
        }
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo h1 {
            color: #667eea;
            font-size: 32px;
            margin-bottom: 5px;
        }
        .logo p {
            color: #999;
            font-size: 14px;
        }
        h2 {
            color: #333;
            margin-bottom: 10px;
            font-size: 24px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin-right: 8px;
        }
        .checkbox-group label {
            margin: 0;
            font-weight: normal;
            font-size: 14px;
        }
        .error {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        .link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .link a:hover {
            text-decoration: underline;
        }
        .demo-accounts {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            font-size: 12px;
        }
        .demo-accounts h4 {
            margin-bottom: 10px;
            color: #333;
        }
        .demo-accounts p {
            margin: 5px 0;
            color: #666;
        }
        .demo-accounts strong {
            color: #667eea;
        }
        .back {
            width: content-fit;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .back:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .back img{
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background= transparent;
        }

    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('home') }}" class="back">
    <img src="/Images/button/back.png">
</a>

        <div class="logo">
            <h1>🛍️ T-Store</h1>
            <p>Electronics Shopping Made Easy</p>
        </div>

        <h2>Selamat Datang Kembali!</h2>
        <p class="subtitle">Login ke akun T-Store Anda</p>

        @if($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Ingat Saya</label>
            </div>

            <button type="submit" class="btn">Login Sekarang</button>
        </form>

        <div class="link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
        </div>

        
    </div>
</body>
</html>