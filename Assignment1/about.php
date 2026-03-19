<!DOCTYPE html>
<html>
<head>
  <title>About - Smart Weather Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --bg-gradient: linear-gradient(to right, #2c3e50, #4ca1af);
      --text-color: #fff;
      --card-bg: #333;
      --btn-bg: #444;
      --btn-hover: #00e676;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: var(--bg-gradient);
      color: var(--text-color);
      margin: 0;
      padding: 30px;
      transition: background 0.3s ease, color 0.3s ease;
    }

    .about-container {
      max-width: 900px;
      margin: 0 auto;
      background: var(--card-bg);
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.3);
    }

    h1, h2 {
      color: #00e676;
      text-align: center;
    }

    .about-logo {
      display: flex;
      justify-content: center;
      margin-bottom: 20px;
    }

    .about-logo i {
      font-size: 60px;
      color: #00e676;
    }

    .about-text {
      font-size: 16px;
      line-height: 1.7;
      text-align: justify;
      margin-top: 20px;
    }

    ul {
      margin-left: 40px;
      margin-top: 10px;
    }

    .btn-back {
      display: inline-block;
      margin-top: 30px;
      padding: 10px 20px;
      background-color: var(--btn-bg);
      color: var(--text-color);
      border-radius: 8px;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }

    .btn-back:hover {
      background-color: var(--btn-hover);
      color: #000;
    }
  </style>
</head>
<body>

  <div class="about-container">
    <div class="about-logo">
      <i class="fas fa-cloud-sun-rain"></i>
    </div>

    <h1>About This Project</h1>

    <div class="about-text">
      <p><strong>Smart Weather Dashboard</strong> is a PHP-based project built to demonstrate core programming concepts and provide users with weather-related information such as temperature, humidity, and weather conditions.</p>

      <p>It is designed to be user-friendly, educational, and completely offline — making it perfect for school or assignment environments without needing a live server or external APIs.</p>

      <h2>🎯 Project Goals</h2>
      <ul>
        <li>Demonstrate PHP fundamentals (if-else, loops, arrays, constants).</li>
        <li>Implement 5 types of Object-Oriented Programming (OOP) principles.</li>
        <li>Design 10 unique and interactive pages.</li>
        <li>Provide a dashboard-style interface with no database dependency.</li>
      </ul>

      <h2>⚙️ Features</h2>
      <ul>
        <li>Account login simulation</li>
        <li>Device manager for weather sensors</li>
        <li>Weather manager with temperature toggle (°C/°F)</li>
        <li>Beautifully designed instruction and about pages</li>
        <li>Modular code with OOP classes</li>
      </ul>

      <h2>💻 Technologies Used</h2>
      <ul>
        <li>PHP (Object-Oriented)</li>
        <li>HTML & CSS</li>
        <li>Font Awesome for icons</li>
        <li>No database – fully static/local</li>
      </ul>
    </div>

    <a class="btn-back" href="index.php"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
  </div>

</body>
</html>
