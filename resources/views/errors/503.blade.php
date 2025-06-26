<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sedang Dalam Perawatan | Under Maintenance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --primary: #2e59d9;
            --secondary: #6c757d;
            --light: #f8f9fc;
            --dark: #343a40;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', 'Helvetica Neue', sans-serif;
            background: linear-gradient(135deg, #e9f0ff, #f8f9fc);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            color: var(--dark);
        }

        .gear-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin-bottom: 30px;
        }

        .gear, .gear2 {
            border: 10px solid var(--primary);
            border-radius: 50%;
            width: 100%;
            height: 100%;
            position: absolute;
            animation: rotate 4s linear infinite;
        }

        .gear2 {
            width: 80px;
            height: 80px;
            top: 20px;
            left: 20px;
            animation-direction: reverse;
        }

        .gear::before,
        .gear::after,
        .gear2::before,
        .gear2::after {
            content: '';
            position: absolute;
            background: var(--primary);
        }

        .gear::before,
        .gear2::before {
            width: 16px;
            height: 8px;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
        }

        .gear::after,
        .gear2::after {
            width: 8px;
            height: 16px;
            left: -14px;
            top: 50%;
            transform: translateY(-50%);
        }

        @keyframes rotate {
            0%   { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        h1 {
            font-size: 28px;
            margin: 0 10px 10px;
            color: var(--primary);
        }

        h2 {
            font-size: 20px;
            font-weight: normal;
            margin-bottom: 20px;
            color: var(--secondary);
        }

        p {
            max-width: 500px;
            font-size: 16px;
            color: var(--dark);
            margin: 0 20px;
            line-height: 1.6;
        }

        footer {
            position: absolute;
            bottom: 15px;
            font-size: 13px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="gear-container">
        <div class="gear"></div>
        <div class="gear2"></div>
    </div>

    <h1>Sedang Dalam Perawatan</h1>
    <h2>Under Maintenance</h2>

    <p>
        Kami sedang melakukan perawatan sistem untuk peningkatan layanan.<br>
        Silakan coba lagi dalam beberapa saat.<br><br>
        We are currently performing system maintenance to improve your experience.<br>
        Please check back again shortly.
    </p>

    <footer>&copy; {{ date('Y') }} PetroPlan. All rights reserved.</footer>
</body>
</html>
