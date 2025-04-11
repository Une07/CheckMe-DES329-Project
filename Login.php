


<!DOCTYPE html>
<html>
<head>
    <title>Login to CheckMe</title>
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
        h1 {
            font-size: 6vw;
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
        .login {
            font-size: 2vw;
            background-color: #1f1f1f;
            color: white;
            border: 2px solid #1f1f1f;
            padding: 15px 30px;
            border-radius: 10px;
            cursor: pointer;
        }
        .button-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 30px;
            width: 350px;
        }
        .textfield-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 30px;
            margin-bottom: 80px;
            width: 700px;
        }
        .textarea-custom {
            font-size: 2vw;
            font-family: 'Montserrat', sans-serif;
            background-color: white;
            color: #1f1f1f;
            border: 3px solid #1f1f1f;
            padding: 15px;
            border-radius: 8px;
        }
        .forgot-password, .signup {
            font-size: 1.8vw;
            text-decoration: none;
            color: #1f1f1f;
        }
        .forgot-password {
            margin-top: 15px;
        }
        .signup {
            margin-top: 10px;
        }
        .go-back, .home-button {
            position: absolute;
            bottom: 20px;
            font-size: 1.8vw;
            text-decoration: none;
            color: white;
            background-color: #1f1f1f;
            padding: 10px 20px;
            border-radius: 8px;
        }
        .go-back {
            left: 20px;
        }
        .home-button {
            right: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="textfield-container">
            <input class="textarea-custom" type="text" id="username" placeholder="Username" required>
            <input class="textarea-custom" type="password" id="password" placeholder="Password" required>
        </div>
        <div class="button-container">
            <button type="button" class="login" onclick="handleLogin()">Login</button>
            <a href="forgotpassword.php" class="forgot-password">Forgot Password?</a>
            <a href="signup.php" class="signup">Sign Up</a>
        </div>
    </div>
    <a href="javascript:history.back()" class="go-back">Go Back</a>
    <a href="CheckMeLanding.php" class="home-button">Home</a>

    <script>
        async function handleLogin() {
            const username = document.getElementById("username").value.trim();
            const password = document.getElementById("password").value.trim();

            if (!username || !password) {
                alert("Please enter both username and password.");
                return;
            }

            try {
                const response = await fetch("http://localhost:5001/login", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ username, password })
                });

                const data = await response.json();

                if (data.status === "success") {
                    // 👉 Store token in localStorage
                    localStorage.setItem("token", data.token);
                    console.log("Token saved to localStorage:", data.token);

                    // (Optional) Store token in PHP session
                    const saveToken = await fetch("SetToken.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({ token: data.token, username })
                    });

                    const saveResponse = await saveToken.json();

                    if (saveResponse.status === "success") {
                        console.log("Token saved to session.");
                        window.location.href = "FeedPage-foryou.php"; // Redirect
                    } else {
                        alert("Failed to save token in session.");
                    }
                }
            } catch (error) {
                console.error("Login error:", error);
                alert("An error occurred during login. Please try again.");
            }
        }
    </script>
</body>
</html>