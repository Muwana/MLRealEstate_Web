<?php
session_start();
header("Content-Type: text/html; charset=UTF-8");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - ML RealEstate</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .logout-container {
            background: white;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logout-icon {
            font-size: 4rem;
            color: #e74c3c;
            margin-bottom: 20px;
            animation: bounce 1s infinite alternate;
        }

        @keyframes bounce {
            from { transform: translateY(0); }
            to { transform: translateY(-10px); }
        }

        .logout-title {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .logout-message {
            color: #7f8c8d;
            font-size: 1.1rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .login-btn {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            width: 100%;
            max-width: 200px;
        }

        .login-btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(52, 152, 219, 0.3);
        }

        .home-btn {
            display: inline-block;
            background: #95a5a6;
            color: white;
            padding: 12px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 15px;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 200px;
        }

        .home-btn:hover {
            background: #7f8c8d;
            transform: translateY(-2px);
        }

        .button-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: center;
        }

        /* Mobile-specific styles */
        @media (max-width: 480px) {
            .logout-container {
                padding: 30px 20px;
                margin: 10px;
            }

            .logout-icon {
                font-size: 3rem;
            }

            .logout-title {
                font-size: 1.5rem;
            }

            .logout-message {
                font-size: 1rem;
            }

            .login-btn, .home-btn {
                padding: 12px 25px;
                font-size: 1rem;
                width: 100%;
            }

            body {
                padding: 10px;
                align-items: flex-start;
                padding-top: 50px;
            }
        }

        @media (max-width: 360px) {
            .logout-container {
                padding: 25px 15px;
            }

            .logout-title {
                font-size: 1.3rem;
            }

            .logout-message {
                font-size: 0.9rem;
            }
        }

        /* Loading animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .auto-redirect {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="logout-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        
        <h1 class="logout-title">Logged Out Successfully</h1>
        
        <p class="logout-message">
            You have been successfully logged out of your ML RealEstate account.
        </p>

        <div class="button-group">
            <a href="login.php" class="login-btn" id="loginBtn">
                <span id="btnText">Login Again</span>
            </a>
            
            <a href="index.html" class="home-btn">
                Back to Home
            </a>
        </div>

        <div class="auto-redirect">
            <i class="bi bi-info-circle"></i>
            You will be redirected to the login page in <span id="countdown">5</span> seconds...
        </div>
    </div>

    <script>
        // Auto-redirect after 5 seconds
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');
        const countdownInterval = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                window.location.href = 'login.php';
            }
        }, 1000);

        // Clear localStorage on logout
        localStorage.removeItem('authToken');
        localStorage.removeItem('userData');

        // Add loading state to login button
        document.getElementById('loginBtn').addEventListener('click', function(e) {
            const btnText = document.getElementById('btnText');
            btnText.innerHTML = '<div class="loading"></div>Redirecting...';
        });
    </script>
</body>
</html>

<?php
// PHP session destruction (keeping your original logic)
session_destroy();
setcookie(session_name(), '', time() - 3600, '/');
?>