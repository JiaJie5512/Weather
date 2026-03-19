<?php
session_start();

// 如果没登录，跳转回账号页
if (!isset($_SESSION["username"])) {
    header("Location: account.php");
    exit;
}

// 数据库连接
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "weatherdb";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$username = $_SESSION["username"];
$role = $_SESSION["role"];

// 获取当前用户 ID
$sql = "SELECT id FROM users WHERE username=?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL error (Get User ID): " . $conn->error);
}
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user ? $user["id"] : 0;
$stmt->close();

// 添加设备
if (isset($_POST["add_device"])) {
    $name = trim($_POST["name"]);
    $type = trim($_POST["type"]);
    $status = "off";
    $location = trim($_POST["location"]);
    $unit = trim($_POST["unit"]);

    $sql = "INSERT INTO devices (user_id, name, type, status, location, unit) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL error (Add Device): " . $conn->error);
    }
    $stmt->bind_param("isssss", $user_id, $name, $type, $status, $location, $unit);
    $stmt->execute();
    $stmt->close();
}

// 删除设备
if (isset($_POST["delete_device"])) {
    $device_id = intval($_POST["device_id"]);
    $sql = "DELETE FROM devices WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL error (Delete Device): " . $conn->error);
    }
    $stmt->bind_param("ii", $device_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

// 切换设备状态
if (isset($_POST["toggle_device"])) {
    $device_id = intval($_POST["device_id"]);
    $new_status = $_POST["new_status"];

    $sql = "UPDATE devices SET status=? WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL error (Toggle Device): " . $conn->error);
    }
    $stmt->bind_param("sii", $new_status, $device_id, $user_id);
    $stmt->execute();
    $stmt->close();

    // 添加到 history
    $sql = "INSERT INTO history (user_id, device_id, action, timestamp) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL error (Insert History): " . $conn->error);
    }
    $action = "Set " . $new_status;
    $stmt->bind_param("iis", $user_id, $device_id, $action);
    $stmt->execute();
    $stmt->close();
}

// 修改设备名字（只有 company 可以操作）
if (isset($_POST["rename_device"]) && $role === "company") {
    $device_id = intval($_POST["device_id"]);
    $new_name = trim($_POST["new_name"]);

    $sql = "UPDATE devices SET name=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL error (Rename Device): " . $conn->error);
    }
    $stmt->bind_param("si", $new_name, $device_id);
    $stmt->execute();
    $stmt->close();

    // 记录历史
    $sql = "INSERT INTO history (user_id, device_id, action, timestamp) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL error (Insert Rename History): " . $conn->error);
    }
    $action = "Renamed device to " . $new_name;
    $stmt->bind_param("iis", $user_id, $device_id, $action);
    $stmt->execute();
    $stmt->close();
}

// 获取用户设备
$sql = "SELECT * FROM devices WHERE user_id=?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL error (Get Devices): " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$devices = $stmt->get_result();
$stmt->close();

?>
<!DOCTYPE html>
<html>
<head>
  <title>Device Manager - Smart Weather Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #1d4350, #a43931);
      color: #fff;
      margin: 0;
      padding: 40px;
    }
    .container {
      max-width: 900px;
      margin: auto;
      background: #2c3e50;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }
    h2 { text-align: center; margin-bottom: 20px; }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    table, th, td {
      border: 1px solid #444;
      padding: 10px;
      text-align: center;
    }
    th { background: #16a085; }
    tr:nth-child(even) { background: #34495e; }
    .btn {
      padding: 6px 12px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }
    .btn-on { background: #2ecc71; color: #fff; }
    .btn-off { background: #e74c3c; color: #fff; }
    .btn-delete { background: #c0392b; color: #fff; }
    .btn-add { background: #3498db; color: #fff; width: 100%; margin-top: 10px; }
    .btn-rename { background: #f39c12; color: #fff; margin-top: 5px; }
    .logout {
      text-align: center;
      margin-top: 15px;
    }
    .logout a {
      color: #f39c12;
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2><i class="fas fa-cogs"></i> Device Manager</h2>

    <table>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Type</th>
        <th>Status</th>
        <th>Location</th>
        <th>Unit</th>
        <th>Action</th>
      </tr>
      <?php while ($row = $devices->fetch_assoc()): ?>
      <tr>
        <td><?= $row["id"] ?></td>
        <td><?= htmlspecialchars($row["name"]) ?></td>
        <td><?= htmlspecialchars($row["type"]) ?></td>
        <td><?= htmlspecialchars($row["status"]) ?></td>
        <td><?= htmlspecialchars($row["location"]) ?></td>
        <td><?= htmlspecialchars($row["unit"]) ?></td>
        <td>
          <form method="post" style="display:inline;">
            <input type="hidden" name="device_id" value="<?= $row['id'] ?>">
            <input type="hidden" name="new_status" value="<?= $row['status']=='on'?'off':'on' ?>">
            <button type="submit" name="toggle_device" class="btn <?= $row['status']=='on'?'btn-off':'btn-on' ?>">
              <?= $row['status']=='on'?'Turn Off':'Turn On' ?>
            </button>
          </form>
          <form method="post" style="display:inline;">
            <input type="hidden" name="device_id" value="<?= $row['id'] ?>">
            <button type="submit" name="delete_device" class="btn btn-delete">Delete</button>
          </form>
          <?php if ($role === "company"): ?>
          <form method="post" style="margin-top:5px;">
            <input type="hidden" name="device_id" value="<?= $row['id'] ?>">
            <input type="text" name="new_name" placeholder="New Name" required>
            <button type="submit" name="rename_device" class="btn btn-rename">Rename</button>
          </form>
          <?php endif; ?>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>

    <h3>Add New Device</h3>
    <form method="post">
      <input type="text" name="name" placeholder="Device Name" required>
      <input type="text" name="type" placeholder="Device Type" required>
      <input type="text" name="location" placeholder="Location" required>
      <input type="text" name="unit" placeholder="Unit (e.g. °C, %, etc.)" required>
      <button type="submit" name="add_device" class="btn btn-add">Add Device</button>
    </form>

    <div class="logout">
      <a href="account.php"><i class="fas fa-sign-out-alt"></i> Back to Account</a>
    </div>
  </div>
</body>
</html>
