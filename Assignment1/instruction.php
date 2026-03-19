<!DOCTYPE html>
<html>
<head>
  <title>Instruction - Smart Weather Dashboard</title>
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
      padding: 30px;
      margin: 0;
      background: var(--bg-gradient);
      color: var(--text-color);
    }

    .instruction-container {
      max-width: 900px;
      margin: 0 auto;
      background: var(--card-bg);
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.3);
    }

    h1, h2 {
      color: var(--btn-hover);
      text-align: center;
    }

    .instruction-step {
      margin: 20px 0;
      display: flex;
      align-items: start;
      gap: 15px;
    }

    .instruction-step i {
      font-size: 24px;
      color: var(--btn-hover);
      flex-shrink: 0;
    }

    .instruction-step p {
      font-size: 16px;
      margin: 0;
    }

    .faq-section {
      margin-top: 40px;
    }

    .faq-item {
      margin-bottom: 20px;
    }

    .faq-item h4 {
      margin-bottom: 6px;
      color: var(--text-color);
    }

    .faq-item p {
      font-size: 15px;
      color: #ccc;
    }

    .btn-back {
      display: inline-block;
      margin-top: 40px;
      padding: 10px 20px;
      background-color: var(--btn-bg);
      color: white;
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

  <div class="instruction-container">
    <h1><i class="fas fa-book"></i> Instructions</h1>

    <div class="instruction-step">
      <i class="fas fa-user-circle"></i>
      <p><strong>Account:</strong> Go to the <code>Account</code> page to log in and manage your user profile.</p>
    </div>

    <div class="instruction-step">
      <i class="fas fa-microchip"></i>
      <p><strong>Device Manager:</strong> Add, edit, or view all your connected weather sensors and IoT devices.</p>
    </div>

    <div class="instruction-step">
      <i class="fas fa-cloud-sun-rain"></i>
      <p><strong>Weather Manager:</strong> View real-time temperature, humidity, wind, and weather status. You can also switch between °C and °F.</p>
    </div>

    <div class="instruction-step">
      <i class="fas fa-info-circle"></i>
      <p><strong>General Usage:</strong> Use the dashboard to monitor conditions and manage device settings. This app works without a database connection.</p>
    </div>

    <div class="instruction-step">
      <i class="fas fa-code"></i>
      <p><strong>Technology Used:</strong> PHP, HTML, CSS, Font Awesome, and basic OOP techniques including classes, arrays, loops, if-else, constants, and more.</p>
    </div>

    <div class="faq-section">
      <h2><i class="fas fa-question-circle"></i> Frequently Asked Questions</h2>

      <div class="faq-item">
        <h4>💡 How do I switch temperature units?</h4>
        <p>Go to the Weather Manager page and use the °C or °F buttons at the top right to change the temperature display.</p>
      </div>

      <div class="faq-item">
        <h4>📶 Can I connect real devices?</h4>
        <p>This version is offline and uses simulated data. In the future, you can integrate with APIs or sensors using JSON or MQTT.</p>
      </div>

      <div class="faq-item">
        <h4>🔒 Do I need an account to use the dashboard?</h4>
        <p>No, the login page is for demonstration purposes only and doesn’t store real data.</p>
      </div>

      <div class="faq-item">
        <h4>🛠️ Can I extend this project?</h4>
        <p>Yes! You can connect to a database, fetch real weather API data, and enhance UI further. The structure is OOP-ready.</p>
      </div>
    </div>

    <a class="btn-back" href="index.php"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
  </div>

</body>
</html>