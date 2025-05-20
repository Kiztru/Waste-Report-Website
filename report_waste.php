<?php // report_waste.php ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport"
        content="width=device-width,initial-scale=1"/>
  <title>Report Waste — Marine Waste Reporting</title>
  <link rel="stylesheet" href="style.css"/>
  <link rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"/>
</head>
<body class="report">

  <header>
    <nav class="navbar">
      <a href="index.html" class="logo-link">
        <img src="Images/Website Logo.png"
             class="logo-img"
             alt="Logo"/>
      </a>
      <ul>
        <li class="menu"><a href="index.html">Home</a></li>
        <li class="menu"><strong>Report Waste</strong></li>
        <li class="menu"><a href="about_us.html">About Us</a></li>
        <li class="menu"><a href="contact.html">Contact</a></li>
      </ul>
    </nav>
  </header>

  <h1 class="page-title">Waste Report Page</h1>

  <form id="waste-form"
        action="submit_report.php"
        method="POST"
        enctype="multipart/form-data">

    <label for="name">Your Name *</label>
    <input type="text" id="name"
           name="name" required/>

    <label for="email">Email Address *</label>
    <input type="email" id="email"
           name="email" required/>

    <label for="description">Site Description</label>
    <textarea id="description" name="description"
              placeholder="e.g. Shallow reef near pier"></textarea>

    <label for="waste_image">
      Upload Photo of Waste *
    </label>
    <input type="file" id="waste_image"
           name="waste_image"
           accept="image/*"
           required/>

    <label>Waste Types & Quantity: *</label>
    <div class="waste-types">
      <!-- Plastic -->
      <div class="waste-type-item">
        <img src="plastic.png" alt="Plastic"/>
        <span>Plastic</span>
        <input type="number" name="waste_plastic"
               min="0" placeholder="0" required/>
      </div>
      <!-- Metal -->
      <div class="waste-type-item">
        <img src="metal.png" alt="Metal"/>
        <span>Metal</span>
        <input type="number" name="waste_metal"
               min="0" placeholder="0"/>
      </div>
      <!-- Glass -->
      <div class="waste-type-item">
        <img src="glass.png" alt="Glass"/>
        <span>Glass</span>
        <input type="number" name="waste_glass"
               min="0" placeholder="0"/>
      </div>
      <!-- Organic -->
      <div class="waste-type-item">
        <img src="organic.png" alt="Organic"/>
        <span>Organic</span>
        <input type="number" name="waste_organic"
               min="0" placeholder="0"/>
      </div>
      <!-- Other -->
      <div class="waste-type-item">
        <img src="other.png" alt="Other"/>
        <span>Other</span>
        <input type="number" name="waste_other"
               min="0" placeholder="0"/>
      </div>
    </div>

    <label>Location *</label>
    <div id="map"></div>
    <input type="hidden" id="latitude"
           name="latitude" required/>
    <input type="hidden" id="longitude"
           name="longitude" required/>

    <label for="address">Address *</label>
    <input type="text" id="address"
           name="address"
           readonly required/>

    <div class="form-actions">
      <button type="submit">Submit Report</button>
      <button type="reset" class="reset-button">
        Reset
      </button>
    </div>
  </form>

  <footer>
    <div class="footer-container">
      <footer>
        <div class="footer-container">
          <div class="footer-column footer-about">
            <h3>About</h3>
            <p>Empowering volunteers and divers to report marine waste and coordinate clean-ups for healthier oceans.</p>
          </div>
          <div class="footer-column footer-links">
            <h3>Quick Links</h3>
            <ul>
              <li><a href="index.html">Home</a></li>
              <li><a href="Report waste.html">Report Waste</a></li>
              <li><a href="About us.html">About Us</a></li>
              <li><a href="Contact.html">Contact</a></li>
            </ul>
          </div>
          <div class="footer-column footer-contact">
            <h3>Contact</h3>
            <p><strong>Email:</strong></p>
            <p>musab.alhajeri@ccse.ku.edu.kw    abdulkader.binsafaa@cls.ku.edu.kw</p>
            
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
    </div>
  </footer>

  <script
    src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js">
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const defaultLoc = { lat:29.3759, lng:47.9774 };
      const map = L.map('map')
                  .setView(defaultLoc,8);
      L.tileLayer(
        'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
        { maxZoom:19, attribution:'© OSM contributors' }
      ).addTo(map);
      const marker = L.marker(defaultLoc,{
        draggable:true
      }).addTo(map);

      function updateLocation(latlng){
        document.getElementById('latitude')
                .value = latlng.lat;
        document.getElementById('longitude')
                .value = latlng.lng;
        fetch(
          `https://nominatim.openstreetmap.org/`+
          `reverse?format=jsonv2&lat=${latlng.lat}`+
          `&lon=${latlng.lng}`
        ).then(r=>r.json())
         .then(d=> {
           document.getElementById('address')
                   .value = d.display_name||'';
         });
      }

      map.on('click', e=>{
        marker.setLatLng(e.latlng);
        updateLocation(e.latlng);
      });
      marker.on('dragend', ()=>{
        updateLocation(marker.getLatLng());
      });

      const form = document.getElementById('waste-form');
      form.addEventListener('submit', async e=>{
        e.preventDefault();
        if (!document.getElementById('address')
                  .value.trim()) {
          alert('Pick a location first.');
          return;
        }
        const res = await fetch(form.action,{
          method:'POST',
          body: new FormData(form)
        });
        const result = await res.json();
        if (result.success) {
          window.location.href =
            `thankyou.php?report_id=`+
            result.report_id;
        } else {
          alert('Error: ' +
                (result.message||
                'Submission failed'));
        }
      });
    });
  </script>
</body>
</html>
