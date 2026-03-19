<?php
session_start();
define("BRAND", "SmartWeather+ Monitor");

if (!isset($_SESSION["unit"])) $_SESSION["unit"] = "C";
if (isset($_POST["unit"])) $_SESSION["unit"] = $_POST["unit"];
$currentUnit = $_SESSION["unit"];

if (!isset($_SESSION["auto_refresh"])) $_SESSION["auto_refresh"] = false;
if (isset($_POST["refresh_toggle"])) $_SESSION["auto_refresh"] = !$_SESSION["auto_refresh"];

class WeatherData {
    public $type, $value;

    function __construct($type) {
        $this->type = $type;
        $this->value = $this->generate();
    }

    private function generate() {
        switch ($this->type) {
            case "Temperature":
                $c = rand(22, 38);
                return ($_SESSION["unit"] == "F") ? round(($c * 9/5) + 32) : $c;
            case "Humidity": return rand(40, 90);
            case "Wind": return rand(5, 30);
            case "Rain": return rand(0, 50);
            default: return 0;
        }
    }

    function getUnit() {
        return [
            "Temperature" => $_SESSION["unit"] == "F" ? "°F" : "°C",
            "Humidity" => "%",
            "Wind" => "km/h",
            "Rain" => "mm"
        ][$this->type];
    }

    function display() {
        return "{$this->value} {$this->getUnit()}";
    }
}

$temp = new WeatherData("Temperature");
$humidity = new WeatherData("Humidity");
$wind = new WeatherData("Wind");
$rain = new WeatherData("Rain");

function feelsLike($t, $h, $w, $unit) {
    $adjusted = $t + ($h * 0.05) - ($w * 0.2);
    return $unit == "F" ? round(($adjusted * 9/5) + 32) : round($adjusted);
}

function getWeatherCondition($temp, $rain) {
    if ($rain > 25) return ["Thunderstorm", "ri-thunderstorms-line"];
    if ($rain > 10) return ["Rainy", "ri-rainy-line"];
    if ($temp > 30) return ["Sunny", "ri-sun-line"];
    if ($temp >= 25) return ["Partly Cloudy", "ri-sun-cloudy-line"];
    return ["Cloudy", "ri-cloudy-line"];
}
$condition = getWeatherCondition($temp->value, $rain->value);

$bg = "default";
if ($condition[0] == "Sunny") $bg = "sunny";
elseif ($condition[0] == "Partly Cloudy") $bg = "partly";
elseif ($condition[0] == "Cloudy") $bg = "cloudy";
elseif ($condition[0] == "Rainy") $bg = "rainy";
elseif ($condition[0] == "Thunderstorm") $bg = "stormy";

$showAlert = false;
$alertMsg = "";
if ($_SESSION["unit"] == "C" && $temp->value > 35) {
    $showAlert = true;
    $alertMsg .= "⚠️ Extreme Heat Alert<br>";
}
if ($rain->value > 30) {
    $showAlert = true;
    $alertMsg .= "⚠️ Heavy Rainfall Warning";
}

$forecastDays = ["Tomorrow", "Day +2", "Day +3"];
$forecast = [];
foreach ($forecastDays as $day) {
    $fTemp = rand(24, 34);
    $fRain = rand(0, 40);
    $cond = getWeatherCondition($fTemp, $fRain);
    $forecast[] = ["day" => $day, "temp" => $fTemp, "icon" => $cond[1], "label" => $cond[0]];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Weather Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
  <style>
    :root {
      --bg-gradient: linear-gradient(to right, #1e3c72, #2a5298);
      --text-color: #ffffff;
      --card-bg: #2e3b4e;
      --nav-bg: #1f2a40;
      --btn-bg: #3a4d63;
      --btn-hover: #2ecc71;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: var(--bg-gradient);
      color: var(--text-color);
    }

    .sunny  { background: linear-gradient(to right, #f9d423, #ff4e50); }
    .partly { background: linear-gradient(to right, #74ebd5, #acb6e5); }
    .cloudy { background: linear-gradient(to right, #bdc3c7, #2c3e50); }
    .rainy  { background: linear-gradient(to right, #4b79a1, #283e51); }
    .stormy { background: linear-gradient(to right, #485563, #29323c); }

    .container {
      max-width: 1100px;
      margin: 40px auto;
      background: rgba(30, 30, 30, 0.7);
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
    }

    h1 {
      text-align: center;
      color: #ffe57f;
      font-size: 32px;
      margin-bottom: 10px;
    }

    .controls {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 30px;
      flex-wrap: wrap;
    }

    .controls select,
    .controls button {
      padding: 10px 16px;
      border-radius: 8px;
      font-size: 15px;
      border: none;
      cursor: pointer;
      background: var(--btn-bg);
      color: white;
      font-weight: bold;
      transition: background 0.3s;
    }

    .controls button:hover,
    .controls select:hover {
      background: var(--btn-hover);
      color: black;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
    }

    .card {
      background: var(--card-bg);
      border-radius: 16px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
      transition: transform 0.2s ease;
    }

    .card:hover {
      transform: scale(1.03);
    }

    .card i {
      font-size: 36px;
      color: #2ecc71;
    }

    .card h2 {
      margin-top: 10px;
      font-size: 20px;
      color: #ffffff;
    }

    .card p {
      font-size: 22px;
      font-weight: bold;
      color: #e0f7fa;
    }

    .alert-box {
      background: #e53935;
      color: white;
      padding: 15px;
      margin: 20px 0;
      border-radius: 12px;
      text-align: center;
      font-weight: bold;
    }

    .btn-back {
      display: inline-block;
      margin-top: 30px;
      padding: 10px 20px;
      background-color: var(--btn-bg);
      color: white;
      border-radius: 10px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s;
    }

    .btn-back:hover {
      background-color: var(--btn-hover);
      color: black;
    }

    .footer {
      text-align: center;
      margin-top: 30px;
      color: #ccc;
      font-size: 14px;
    }

    @media (max-width: 600px) {
      .grid { grid-template-columns: 1fr; }
      .controls { flex-direction: column; gap: 10px; }
    }
  </style>
  <?php if ($_SESSION["auto_refresh"]) echo '<meta http-equiv="refresh" content="10">'; ?>
</head>
<body class="<?= $bg ?>">
<div class="container">
  <h1><i class="ri-sun-cloudy-line"></i> Weather Manager</h1>

  <p style="text-align:center; font-size:18px; color:#aaa;">
    <i class="ri-map-pin-line"></i> Kuala Lumpur, Malaysia (MYT)
  </p>
  <p style="text-align:center; color:#ccc; margin-bottom:20px;">
    <i class="ri-time-line"></i> Last Updated: <?= date("Y-m-d H:i:s") ?>
  </p>

  <div class="controls">
    <form method="POST">
      <select name="unit" onchange="this.form.submit()">
        <option value="C" <?= $currentUnit == "C" ? "selected" : "" ?>>°C</option>
        <option value="F" <?= $currentUnit == "F" ? "selected" : "" ?>>°F</option>
      </select>
    </form>
    <form method="POST">
      <button name="refresh_toggle">
        <?= $_SESSION["auto_refresh"] ? "Stop Auto Refresh" : "Enable Auto Refresh" ?>
      </button>
    </form>
  </div>

  <?php if ($showAlert): ?>
    <div class="alert-box">
      <strong>Weather Alert:</strong><br><?= $alertMsg ?>
    </div>
  <?php endif; ?>

  <div class="grid">
    <div class="card">
      <i class="ri-thermometer-line"></i>
      <h2>Temperature</h2>
      <p><?= $temp->display(); ?></p>
    </div>
    <div class="card">
      <i class="ri-emotion-happy-line"></i>
      <h2>Feels Like</h2>
      <p><?= feelsLike($temp->value, $humidity->value, $wind->value, $currentUnit) . " " . $temp->getUnit(); ?></p>
    </div>
    <div class="card">
      <i class="ri-water-percent-line"></i>
      <h2>Humidity</h2>
      <p><?= $humidity->display(); ?></p>
    </div>
    <div class="card">
      <i class="ri-windy-line"></i>
      <h2>Wind Speed</h2>
      <p><?= $wind->display(); ?></p>
    </div>
    <div class="card">
      <i class="ri-rainy-line"></i>
      <h2>Rainfall</h2>
      <p><?= $rain->display(); ?></p>
    </div>
    <div class="card">
      <i class="<?= $condition[1] ?>"></i>
      <h2>Condition</h2>
      <p><?= $condition[0] ?></p>
    </div>
  </div>

  <h2 style="text-align:center; margin-top:40px;">3-Day Forecast</h2>
  <div class="grid" style="margin-top:15px;">
    <?php foreach ($forecast as $f): ?>
      <div class="card">
        <i class="<?= $f['icon'] ?>"></i>
        <h2><?= $f["day"] ?></h2>
        <p><?= $f["temp"] . "°" . $_SESSION["unit"] ?><br><small><?= $f["label"] ?></small></p>
      </div>
    <?php endforeach; ?>
  </div>

  <a class="btn-back" href="index.php"><i class="ri-arrow-left-line"></i> Back to Dashboard</a>

  <div class="footer">
    Powered by <?= BRAND ?> &copy; <?= date("Y") ?>
  </div>
</div>
</body>
</html>
