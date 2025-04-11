<!DOCTYPE html>
<html>
<head>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <title>Signup to CheckMe</title>
  <style>
    /* Styling is same as before */
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
    .signup, .login {
      font-size: 1.25vw;
      text-decoration: none;
      text-align: center;
      font-family: 'Montserrat', sans-serif;
      background-color: #1f1f1f;
      color: white;
      border: 2px solid #1f1f1f;
      padding: 10px 20px;
      border-radius: 5px;
      display: block;
      width: 100%;
    }
    .button-container {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-top: 20px;
      width: 300px;
    }
    .textfield-container {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-top: 20px;
      margin-bottom: 60px;
      width: 600px;
    }
    .textarea-custom {
      font-size: 1.25vw;
      font-family: 'Montserrat', sans-serif;
      background-color: white;
      color: #1f1f1f;
      border: 2px solid #1f1f1f;
      padding: 10px;
      border-radius: 5px;
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
  <div class="textfield-container">
    <input class="textarea-custom" type="text" id="username" placeholder="Username" />
    <input class="textarea-custom" type="password" id="password" placeholder="Password" />
    <input class="textarea-custom" type="password" id="confirmpassword" placeholder="Confirm Password" />
  </div>
  <div class="button-container">
    <!-- Use button here for form submission -->
    <a href="Login.php" class="signup">Signup</a>
    <a href="Login.php" class="login">Login</a>
  </div>
</div>
<a href="javascript:history.back()" class="go-back">Go Back</a>

<script>
document.querySelector(".signup").addEventListener("click", async function(event) {
  event.preventDefault();

  const username = document.getElementById("username").value;
  const password = document.getElementById("password").value;
  const confirmpassword = document.getElementById("confirmpassword").value;

  // Check if password and confirm password match
  if (password !== confirmpassword) {
    alert("Passwords do not match.");
    return;
  }

  try {
    const response = await fetch("http://localhost:5001/signup", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ username, password, confirmpassword })
    });

    const data = await response.json();
    alert(data.message);

    if (data.status === "success") {
      window.location.href = "Login.php"; // Redirect on success
    }
  } catch (err) {
    alert("Server error. Please try again later.");
    console.error(err);
  }
});
</script>

</body>
</html>