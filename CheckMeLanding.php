<!DOCTYPE html>
<html lang = "en">
    <head>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                width: 100vw;
                background-color: #fff;
                font-family: 'Montserrat', sans-serif;
                margin: 0;
            }
            .container {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }
            .logo-container {
                width: 30vw;
                height: 30vw;
                max-width: 150px;
                max-height: 150px;
                border: 2px solid black;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 20px;
            }
            .logo-container img {
                width: 100%;
                height: 100%;
                object-fit: contain;
            }
            h1 {
                font-size: 5vw;
                font-weight: bold;
                text-align: center;
            }
            .button-container {
                display: flex;
                flex-direction: column;
                gap: 10px;
                margin-top: 20px;
                width: 80%;
                max-width: 300px;
            }
            .a {
                width: 100%;
                max-width: 350px;
                padding: 12px;
                text-align: center;
                border-radius: 5px;
                font-size: 4vw;
                cursor: pointer;
                text-decoration: none;
                display: inline-block;
            }
            .login {
                text-align: center;
                font-family: 'Montserrat', sans-serif;
                background-color: #1f1f1f;
                color: white;
                border: none;
            }
            .signup {
                text-align: center;
                font-family: 'Montserrat', sans-serif;
                background-color: white;
                color: #1f1f1f;
                border: 2px solid #1f1f1f;
            }
        </style>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CheckMe Landing Page</title>
    </head>

    <body>
        <div class = "container">
            <div class="logo-container">
                <img src="logo.png" alt="CheckMeLogo">
            </div>
            <h1>CheckMe</h1>
            <div class="button-container">
                <a href = "Login.php" class="login" >Login</a>
                <a href = "Signup.php" class="signup">Sign-up</a>
            </div>
        </div>
</body>
</html>