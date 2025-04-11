<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <title>Reset Password</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color:rgb(255, 255, 255);
            font-family: 'Montserrat', sans-serif;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 400px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 14px;
            color: gray;
            margin-bottom: 20px;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .reset-button {
            background-color:rgb(0, 0, 0);
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .go-back {
                position: absolute;
                bottom: 20px;
                left: 20px;
                font-size: 1.8vw;
                text-decoration: none;
                color: white;
                background-color: #1f1f1f;
                padding: 10px 20px;
                border-radius: 8px;
            }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">Forgot Password</div>
        <div class="subtitle">Enter your username to reset your password.</div>
        <input type="email" class="input-field" placeholder="Your username">
        <button class="reset-button">Reset my Password</button>
    </div>
    <a href="javascript:history.back()" class="go-back">Go Back</a>

</body>
</html>