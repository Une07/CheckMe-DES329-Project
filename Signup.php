<!DOCTYPE html>
<html>
    <head>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
        <title>Login to CheckMe</title>
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
            h1 {
                font-size: 5vw;
                font-weight: bold;
                text-align: center;
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
            .signup {
                font-size: 1.25vw;
                text-decoration: none;
                text-align: center;
                font-family: 'Montserrat', sans-serif;
                background-color: #1f1f1f;
                color: white;
                border: none;
            }
            .button-container {
                display: flex;
                flex-direction: column;
                gap: 10px;
                margin-top: 20px;
                width: 300px;
            }
            .textfield-container{
                display: flex;
                flex-direction: column;
                gap: 10px;
                margin-top: 20px;
                margin-bottom: 60px;
                width: 600px;
            }
            .textarea-custom{
                font-size: 1.25vw;
                font-family: 'Montserrat', sans-serif;
                background-color: white;
                color: #1f1f1f;
                border: 2px solid #1f1f1f;
            }
        </style>
    </head>
    <body>
        <div class = "container">
            <div class = "textfield-container">
                <input class = "textarea-custom" type = "text" id = "username" placeholder = "Username"></input>
                <input class = "textarea-custom" type = "password" id = "password" placeholder = "Password"></input>
                <input class = "textarea-custom" type = "password" id = "confirmpassword" placeholder = "Confirm Password"></input>
            </div>
            <div class = "button-container">
                <a href="Login.php" class = "signup">Signup</a>
            </div>
        </div>
    </body>
    
</html>