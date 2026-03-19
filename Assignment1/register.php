<!DOCTYPE html>
<html>
<head>
  <title>Register - Smart Weather Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #4ca1af, #2c3e50);
      color: #fff;
      margin: 0;
      padding: 30px;
    }
    .register-box {
      max-width: 400px;
      margin: 40px auto;
      background: #333;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    .register-box h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .register-box input {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      margin-bottom: 15px;
      border: none;
      border-radius: 6px;
      background-color: #555;
      color: white;
    }
    .register-box button {
      width: 100%;
      padding: 10px;
      background: #444;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    .register-box button:hover {
      background: #00e676;
      color: #000;
    }
    .login-link {
      text-align: center;
      margin-top: 15px;
    }
    .login-link a {
      color: #00e676;
      text-decoration: none;
      font-weight: bold;
    }
    .login-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="register-box">
    <h2><i class="fas fa-user-plus"></i> Register</h2>
    <form method="post" action="account.php">
      <label>Username</label>
      <input type="text" name="username" placeholder="Choose username" required>

      <label>Password</label>
      <input type="password" name="password" placeholder="Choose password" required>

      <label>Email</label>
      <input type="email" name="email" placeholder="Enter email" required>

      <button type="submit"><i class="fas fa-user-plus"></i> Register</button>
    </form>
    <div class="login-link">
      <p>Already have an account? <a href="account.php">Login here</a></p>
    </div>
  </div>
</body>
</html>
