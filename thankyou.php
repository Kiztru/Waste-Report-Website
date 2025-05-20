<?php
// thankyou.php

// 1) Grab & validate the incoming report ID
$reportId = isset($_GET['report_id']) ? intval($_GET['report_id']) : 0;
if (!$reportId) {
    exit('Invalid report ID.');
}

// 2) Connect to your database and fetch the record
$pdo = new PDO(
    'mysql:host=127.0.0.1;dbname=marine_waste;charset=utf8mb4',
    'root', '',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$stmt = $pdo->prepare("SELECT * FROM reports WHERE id = ?");
$stmt->execute([$reportId]);
$report = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$report) {
    exit('Report not found.');
}

// 3) If the user clicked “Download”, build and echo the .txt, then exit
if (isset($_GET['download'])) {
    header('Content-Type: text/plain; charset=UTF-8');
    header('Content-Disposition: attachment; filename="report_' . $reportId . '.txt"');

    $txt  = "=== Marine Waste Report #{$report['id']} ===\n";
    $txt .= "Submitted On: " . date('Y-m-d H:i:s', strtotime($report['created_at'])) . "\n";
    $txt .= str_repeat('=', 50) . "\n\n";

    $txt .= "Name        : {$report['name']}\n";
    $txt .= "Email       : {$report['email']}\n";
    $txt .= "Description : " . ($report['description'] ?: '—none—') . "\n\n";

    $txt .= "Location:\n";
    $txt .= "  Address   : {$report['address']}\n";
    $txt .= "  Latitude  : {$report['latitude']}\n";
    $txt .= "  Longitude : {$report['longitude']}\n\n";

    $txt .= "Waste Quantities:\n";
    $txt .= "  • Plastic : {$report['waste_plastic']}\n";
    $txt .= "  • Metal   : {$report['waste_metal']}\n";
    $txt .= "  • Glass   : {$report['waste_glass']}\n";
    $txt .= "  • Organic : {$report['waste_organic']}\n";
    $txt .= "  • Other   : {$report['waste_other']}\n\n";

    $txt .= "Photo Path : {$report['photo_path']}\n\n";
    $txt .= "Thank you for helping keep our oceans clean!\n";

    echo $txt;
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Thank You — Marine Waste Reporting</title>
  <link rel="stylesheet" href="style.css"/>
</head>
<body class="report">
  <header>
    <nav class="navbar">
      <a href="index.html" class="logo-link">
        <img src="marine_waste\Images\Website Logo.png" alt="Logo" class="logo-img"/>
      </a>
      <ul class="nav-menu">
        <li class="menu"><a href="index.html">Home</a></li>
        <li class="menu"><a href="report_waste.php">Report Waste</a></li>
        <li class="menu"><a href="about_us.html">About Us</a></li>
        <li class="menu"><a href="contact.html">Contact</a></li>
      </ul>
      <button class="nav-toggle" aria-label="Toggle menu">
        <span></span><span></span><span></span>
      </button>
    </nav>
  </header>

  <main class="thankyou-content">
    <h1>Thank You!</h1>
    <p>Your report has been submitted successfully.</p>
    <p>
      <a href="thankyou.php?report_id=<?php echo $reportId ?>&amp;download=1"
        class="cta-button">
        Download Full Report
      </a>
    </p>
    <p><a href="index.html">Return Home</a></p>
  </main>

  <footer>
    <div class="footer-container">
      <div class="footer-column">
        <h3>About</h3>
        <p>Empowering volunteers and divers to report marine waste and coordinate clean-ups for healthier oceans.</p>
      </div>
      <div class="footer-column">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="report_waste.php">Report Waste</a></li>
          <li><a href="about_us.html">About Us</a></li>
          <li><a href="contact.html">Contact</a></li>
        </ul>
      </div>
      <div class="footer-column">
        <h3>Contact</h3>
        <p>
          <strong>Email:</strong><br/>
          musab.alhajeri@ccse.ku.edu.kw<br/>
          abdulkader.binsafaa@cls.ku.edu.kw
        </p>
      </div>
      <div class="footer-column footer-social">
        <h3>Follow Us</h3>
        <div class="social-icons">
        <a href="#" aria-label="Facebook"><img src="facebook.png" alt="Facebook"/></a>
              <a href="#" aria-label="Instagram"><img src="instagram.png" alt="Instagram"/></a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>© 2025 Marine Waste Reporting. All rights reserved.</p>
    </div>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', ()=>{
      const btn  = document.querySelector('.nav-toggle');
      const menu = document.querySelector('.nav-menu');
      btn.addEventListener('click', ()=> menu.classList.toggle('open') );
    });
  </script>
</body>
</html>
