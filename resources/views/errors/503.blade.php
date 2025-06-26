<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sedang Dalam Perawatan | Under Maintenance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --primary: #2e59d9;
            --dark: #343a40;
            --light: #f8f9fc;
            --gray: #6c757d;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: var(--light);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: var(--dark);
            text-align: center;
            overflow: hidden;
        }

        .gear-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin-bottom: 25px;
        }

        .gear {
            width: 100%;
            height: 100%;
            border: 10px solid var(--primary);
            border-radius: 50%;
            position: absolute;
            animation: spin 2.5s linear infinite;
        }

        .gear:before,
        .gear:after {
            content: "";
            position: absolute;
            background: var(--primary);
            border-radius: 2px;
        }

        .gear:before {
            width: 20px;
            height: 8px;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
        }

        .gear:after {
            width: 8px;
            height: 20px;
            left: -14px;
            top: 50%;
            transform: translateY(-50%);
        }

        @keyframes spin {
            0%   { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        h1 {
            font-size: 28px;
            margin: 10px 0 5px;
        }

        p {
            font-size: 16px;
            color: var(--gray);
            max-width: 460px;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .refresh-btn {
            padding: 10px 20px;
            font-size: 14px;
            color: white;
            background-color: var(--primary);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .refresh-btn:hover {
            background-color: #1e45b5;
        }

        footer {
            position: absolute;
            bottom: 20px;
            font-size: 12px;
            color: #999;
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 22px;
            }
            p {
                font-size: 14px;
                padding: 0 15px;
            }
        }
    </style>
</head>
<body>
    <div class="gear-container">
        <div class="gear"></div>
    </div>
    <h1>Sedang Dalam Perawatan</h1>
    <h1>Under Maintenance</h1>
    <p>
        Aplikasi kami sedang dalam proses perawatan dan peningkatan.<br>
        Silakan kembali beberapa saat lagi.<br><br>
        Our application is currently undergoing maintenance and improvements.<br>
        Please check back shortly.
    </p>
    <button class="refresh-btn" onclick="window.location.reload();">Muat Ulang Halaman / Refresh Page</button>
    <footer>&copy; {{ date('Y') }} PetroPlan</footer>
</body>
</html>
