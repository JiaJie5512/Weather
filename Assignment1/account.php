<?php
session_start();

// 数据库连接
$servername = "localhost";
$username_db = "root"; 
$password_db = ""; 
$dbname = "weatherdb";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// 注册功能
if (isset($_POST["register"])) {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role = $_POST["role"]; // ✅ 新增：从表单获取角色

    $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $email, $password, $role);
    if ($stmt->execute()) {
        echo "<script>alert('Account created successfully! Please login.');</script>";
    } else {
        echo "<script>alert('Error: Email already exists!');</script>";
    }
    $stmt->close();
}

// 登录功能
if (isset($_POST["login"])) {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["username"] = $user["username"];
        $_SESSION["role"] = $user["role"];
        header("Location: device_manager.php"); // ✅ 登录成功跳转设备管理
        exit;
    } else {
        echo "<script>alert('Invalid email or password!');</script>";
    }
    $stmt->close();
}

// 游客模式
if (isset($_POST["guest"])) {
    $_SESSION["username"] = "Guest";
    $_SESSION["role"] = "guest";
    header("Location: device_manager.php");
    exit;
}

// 如果已经登录，取出用户信息
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$role = isset($_SESSION['role']) ? $_SESSION['role'] : "guest";
?>
<!DOCTYPE html>
<html>
<head>
  <title>Account - Smart Weather Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #4ca1af, #2c3e50);
      color: #fff;
      margin: 0;
      padding: 40px;
    }
    .container {
      max-width: 400px;
      margin: auto;
      background: #333;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    input, select {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      border: none;
      border-radius: 6px;
    }
    button {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      margin-top: 10px;
    }
    .btn-login { background: #00e676; color: #000; }
    .btn-register { background: #3498db; color: #fff; }
    .btn-guest { background: #e67e22; color: #fff; }
    .btn:hover { opacity: 0.8; }
    .toggle {
      text-align: center;
      margin-top: 15px;
    }
    .toggle a {
      color: #00e676;
      text-decoration: none;
      font-weight: bold;
    }
    .toggle a:hover { text-decoration: underline; }
    .welcome {
      text-align: center;
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 15px;
      color: #ffd700;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2><i class="fas fa-user-circle"></i> Account</h2>

    <!-- 显示欢迎信息 -->
    <?php if ($username): ?>
      <div class="welcome">
        Welcome, <?= htmlspecialchars($username) ?> 👋 | Role: <?= ucfirst($role) ?>
      </div>
    <?php endif; ?>

    <!-- 登录表单 -->
    <form method="post">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button class="btn-login" type="submit" name="login">Login</button>
    </form>

    <!-- 注册表单 -->
    <form method="post">
      <input type="text" name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <!-- ✅ 新增角色选择 -->
      <select name="role" required>
        <option value="user">User</option>
        <option value="company">Company</option>
      </select>
      <button class="btn-register" type="submit" name="register">Register</button>
    </form>

    <!-- 游客模式 -->
    <form method="post">
      <button class="btn-guest" type="submit" name="guest">Continue as Guest</button>
    </form>

    <div class="toggle">
      <a href="index.php"><i class="fas fa-arrow-left"></i> Back to Home</a>
    </div>
  </div>
</body>
</html>
