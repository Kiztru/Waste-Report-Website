# Marine Waste Reporting Website 🌊

This web application enables users to **report marine waste**, The project was developed to raise awareness of marine pollution and support environmental monitoring efforts.

---

## Features

- **Interactive map:** Powered by [Leaflet.js](https://leafletjs.com/) for pinpointing waste locations.  
- **Report form:** Submit detailed information including waste type, location coordinates, and additional notes.  
- **Server-side database:** Reports are stored using PHP and MySQL for later analysis.  
- **Responsive design:** Accessible on both desktop and mobile browsers.

---

## Tech Stack

- **Frontend:** HTML, CSS, JavaScript, Leaflet.js  
- **Backend:** PHP for form handling and validation  
- **Database:** MySQL (or any PHP-compatible database)  

---

## Project Structure

project-root/
│

├── index.html # Main page with map and form

├── style.css # User interface styling

├── script.js # Leaflet map integration + client-side form logic

├── classify.js # Connects Teachable Machine model for image classification

├── submit.php # Processes and validates form submissions

├── db_config.php # Database connection credentials

├── uploads/ # (Optional) Stores uploaded images if enabled

└── README.md # Project documentation

yaml

Copy

Edit

---

## How It Works

1. **Select waste location on the map.**  
2. **Upload an image** (optional) or choose waste type manually.  
3. **ML model runs classification** if an image is provided.  
4. **Form is validated client-side and server-side** via PHP.  
5. **Data is stored in the database** for environmental tracking and analysis.

---

## Installation

1. **Clone the repository**  
   ```bash
   git clone https://github.com/your-username/marine-waste-report.git
   cd marine-waste-report
Set up a PHP server (XAMPP, WAMP, or built-in PHP server):

bash
Copy
Edit
php -S localhost:8000
Configure your database in db_config.php:

php
Copy
Edit
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "marine_waste";
Import the database schema (if included):

bash
Copy
Edit
mysql -u root -p marine_waste < database.sql
Open in browser

arduino
Copy
Edit
http://localhost:8000
