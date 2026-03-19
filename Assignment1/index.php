<?php
session_start();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$role = isset($_SESSION['role']) ? $_SESSION['role'] : "guest";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Smart Weather Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <style>
    :root {
      --bg-gradient: linear-gradient(to right, #2c3e50, #4ca1af);
      --text-color: #fff;
      --card-bg: #333;
      --nav-bg: #222;
      --btn-bg: #444;
      --btn-hover: #00e676;
    }
    body.light-mode {
      --bg-gradient: linear-gradient(to right, #ece9e6, #ffffff);
      --text-color: #333;
      --card-bg: #f0f0f0;
      --nav-bg: #ddd;
      --btn-bg: #ccc;
      --btn-hover: #00bfa5;
    }
    body {margin: 0;font-family: 'Poppins', sans-serif;background: var(--bg-gradient);color: var(--text-color);transition: background 0.3s, color 0.3s;}
    header {display: flex;justify-content: space-between;align-items: center;flex-wrap: wrap;padding: 20px;background: var(--nav-bg);}
    .search-bar {display: flex;gap: 10px;}
    .search-bar input {padding: 10px;border-radius: 8px;border: none;width: 250px;}
    .btn-location {background: #00e676;border: none;padding: 10px 20px;border-radius: 8px;cursor: pointer;font-weight: bold;}
    .toggle-darkmode {display: flex;align-items: center;gap: 8px;}
    .nav-links {width: 100%;display: flex;justify-content: center;flex-wrap: wrap;gap: 10px;margin-top: 10px;}
    .nav-links a.btn {background: var(--btn-bg);padding: 10px 15px;border-radius: 8px;color: var(--text-color);text-decoration: none;font-weight: bold;}
    .nav-links a.btn:hover {background: var(--btn-hover);color: #000;}
    .greeting {text-align: center;font-size: 20px;font-weight: bold;margin: 20px 0;}
    .container {max-width: 1200px;margin: auto;display: grid;grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));gap: 20px;padding: 20px;}
    .card {background: var(--card-bg);border-radius: 20px;padding: 20px;box-shadow: 0 4px 12px rgba(0,0,0,0.2);}
    .temp-large {font-size: 36px;font-weight: bold;}
    .sub-info {font-size: 14px;color: #ccc;}
    .forecast, .hourly {display: flex;gap: 16px;overflow-x: auto;}
    .forecast div, .hourly div {background: var(--btn-bg);border-radius: 12px;padding: 10px;text-align: center;min-width: 100px;}
    #weatherMap {height: 300px;border-radius: 12px;}
    .zone-box {margin-top: 15px;}
    select, button {padding: 8px;border-radius: 6px;}
    .time-box {margin-top: 10px;font-size: 18px;font-weight: bold;}
  </style>
</head>
<body>
  <header>
    <div class="toggle-darkmode">
      <label for="darkMode">🌙 Dark Mode</label>
      <input type="checkbox" id="darkMode" onclick="toggleMode()">
    </div>
    <div class="search-bar">
      <input type="text" placeholder="Search for your preferred city...">
      <button class="btn-location">📍 Current Location</button>
    </div>
  </header>

  <section class="nav-links">
    <a class="btn" href="account.php"><i class="fas fa-user"></i> Account</a>
    <a class="btn" href="device_manager.php"><i class="fas fa-microchip"></i> Devices</a>
    <a class="btn" href="weather_manager.php"><i class="fas fa-cloud-sun-rain"></i> Weather</a>
    <a class="btn" href="instruction.php"><i class="fas fa-book"></i> Instructions</a>
    <a class="btn" href="about.php"><i class="fas fa-info-circle"></i> About</a>
  </section>

  <div class="greeting" id="greeting">
    <?php if ($username): ?>
      Welcome, <?= htmlspecialchars($username) ?> 👋 | Role: <?= ucfirst($role) ?>
    <?php else: ?>
      Hello!
    <?php endif; ?>
  </div>

  <div class="container">
    <div class="card">
      <h2 id="cityName">Kuala Lumpur</h2>
      <div class="temp-large" id="clock">--:--</div>
      <div class="sub-info" id="dateText">--</div>
    </div>

    <div class="card">
      <div class="temp-large">28°C</div>
      <div class="sub-info">Feels like: 30°C</div>
      <p>⛈️ Thunderstorm Clouds</p>
      <p>🌅 Sunrise: 6:45 AM | 🌇 Sunset: 7:15 PM</p>
      <p>💧 Humidity: 84% | 💨 Wind: 7.9 km/h</p>
      <p>☀️ UV Index: 5.5 | 🌫 Visibility: 3 km</p>
    </div>

    <div class="card">
      <h3>Today's Highlights</h3>
      <p>💨 Wind Speed: 7.9 km/h</p>
      <p>☀️ UV Index: 5.5</p>
      <p>🌅 Sunrise: 6:45 AM</p>
      <p>🌇 Sunset: 7:15 PM</p>
    </div>

    <div class="card">
      <h3>5-Day Forecast</h3>
      <div class="forecast">
        <div>🌤️<br>29°C<br>Tue</div>
        <div>⛅<br>28°C<br>Wed</div>
        <div>🌧️<br>25°C<br>Thu</div>
        <div>☀️<br>31°C<br>Fri</div>
        <div>🌦️<br>26°C<br>Sat</div>
      </div>
    </div>

    <div class="card">
      <h3>Hourly Forecast</h3>
      <div class="hourly">
        <div>12:00<br>☀️<br>28°C</div>
        <div>15:00<br>☀️<br>30°C</div>
        <div>18:00<br>⛅<br>29°C</div>
        <div>21:00<br>🌙<br>26°C</div>
        <div>00:00<br>🌙<br>24°C</div>
      </div>
    </div>

    <div class="card">
      <h3>Weather Map</h3>
      <div id="weatherMap"></div>

      <!-- 区域选择 -->
      <div class="zone-box">
        <form id="zoneForm">
          <label for="zone">Select Zone:</label>
          <select id="zone">
            <option value="Asia/Kuala_Lumpur" data-lat="3.139" data-lng="101.6869">Kuala Lumpur</option>
            <option value="Asia/Singapore" data-lat="1.3521" data-lng="103.8198">Singapore</option>
            <option value="Asia/Tokyo" data-lat="35.6762" data-lng="139.6503">Tokyo</option>
            <option value="Europe/London" data-lat="51.5072" data-lng="-0.1276">London</option>
            <option value="America/New_York" data-lat="40.7128" data-lng="-74.0060">New York</option>
          </select>
          <button type="submit">Update Zone</button>
        </form>
        <div class="time-box">🕒 Local Time: <span id="localTime">--:--:--</span></div>
      </div>
    </div>
  </div>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <!-- Firebase -->
  <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-app-compat.js"></script>
  <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-database-compat.js"></script>
  <script>
    // Greeting
    function showGreeting() {
      <?php if (!$username): ?>
      const hour = new Date().getHours();
      let greeting;
      if (hour >= 5 && hour < 12) greeting = "Good Morning ☀️";
      else if (hour >= 12 && hour < 17) greeting = "Good Afternoon 🌤️";
      else if (hour >= 17 && hour < 21) greeting = "Good Evening 🌇";
      else greeting = "Good Night 🌙";
      document.getElementById("greeting").textContent = greeting;
      <?php endif; ?>
    }
    showGreeting();

    // Dark mode
    function toggleMode() {
      document.body.classList.toggle('light-mode');
    }

    // Leaflet Map
    const map = L.map('weatherMap').setView([3.139, 101.6869], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors',
      maxZoom: 18,
    }).addTo(map);
    const marker = L.marker([3.139, 101.6869]).addTo(map);

    // Firebase config (换成你的)
    const firebaseConfig = {
      apiKey: "YOUR_API_KEY",
      authDomain: "YOUR_PROJECT.firebaseapp.com",
      databaseURL: "https://YOUR_PROJECT.firebaseio.com",
      projectId: "YOUR_PROJECT",
      storageBucket: "YOUR_PROJECT.appspot.com",
      messagingSenderId: "SENDER_ID",
      appId: "APP_ID"
    };
    firebase.initializeApp(firebaseConfig);
    const db = firebase.database();

    // 当前时区（默认）
    let currentZone = "Asia/Kuala_Lumpur";

    // 主时钟 + 日期
    function updateClock() {
      const now = new Date();
      const optClock = { timeZone: currentZone, hour: "2-digit", minute: "2-digit" };
      const optDate  = { timeZone: currentZone, weekday: "long", month: "long", day: "numeric" };
      document.getElementById('clock').textContent = new Intl.DateTimeFormat([], optClock).format(now);
      document.getElementById('dateText').textContent = new Intl.DateTimeFormat([], optDate).format(now);
    }

    // Local Time
    function updateLocalTime() {
      const now = new Date();
      const opt = { timeZone: currentZone, hour: "2-digit", minute: "2-digit", second:"2-digit" };
      document.getElementById("localTime").textContent = new Intl.DateTimeFormat([], opt).format(now);
    }

    setInterval(() => {
      updateClock();
      updateLocalTime();
    }, 1000);

    // 提交更新 Zone
    document.getElementById("zoneForm").addEventListener("submit", function(e){
      e.preventDefault();
      const select = document.getElementById("zone");
      const zone = select.value;
      const lat = parseFloat(select.selectedOptions[0].dataset.lat);
      const lng = parseFloat(select.selectedOptions[0].dataset.lng);
      const city = select.selectedOptions[0].textContent;
      db.ref("deviceZone").set({
        zone: zone, lat: lat, lng: lng, city: city, updatedAt: new Date().toISOString()
      });
    });

    // 监听 Firebase → 更新地图和时间
    db.ref("deviceZone").on("value", snap => {
      if(snap.exists()){
        const d = snap.val();
        currentZone = d.zone;
        map.setView([d.lat, d.lng], 10);
        marker.setLatLng([d.lat, d.lng]).bindPopup(d.city).openPopup();
        document.getElementById("cityName").textContent = d.city;
        updateClock();
        updateLocalTime();
      }
    });
  </script>
</body>
</html>
