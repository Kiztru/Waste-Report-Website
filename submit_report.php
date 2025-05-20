<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD']!=='POST') {
  http_response_code(405);
  exit(json_encode(['success'=>false]));
}

// 1) Collect & validate
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$desc    = trim($_POST['description'] ?? '');
$plast   = intval($_POST['waste_plastic'] ?? 0);
$metal   = intval($_POST['waste_metal']   ?? 0);
$glass   = intval($_POST['waste_glass']   ?? 0);
$organic = intval($_POST['waste_organic'] ?? 0);
$other   = intval($_POST['waste_other']   ?? 0);
$lat     = trim($_POST['latitude']  ?? '');
$lng     = trim($_POST['longitude'] ?? '');
$addr    = trim($_POST['address']   ?? '');

if (!$name||!$email||$plast<0||!$lat||!$lng||!$addr){
  http_response_code(400);
  exit(json_encode([
    'success'=>false,
    'message'=>'Missing required fields'
  ]));
}

// 2) Image upload
if (!isset($_FILES['waste_image'])
 || $_FILES['waste_image']['error']!==UPLOAD_ERR_OK){
  http_response_code(400);
  exit(json_encode([
    'success'=>false,
    'message'=>'Image upload error'
  ]));
}

$uploadDir = __DIR__.'/uploads';
if (!is_dir($uploadDir)) mkdir($uploadDir,0755,true);

$ext      = pathinfo(
  $_FILES['waste_image']['name'],
  PATHINFO_EXTENSION
);
$filename = uniqid('waste_',true).".".$ext;
$dest     = "$uploadDir/$filename";

if (!move_uploaded_file(
      $_FILES['waste_image']['tmp_name'],
      $dest
    )){
  http_response_code(500);
  exit(json_encode([
    'success'=>false,
    'message'=>'Failed to save image'
  ]));
}
$imagePath = "uploads/$filename";

// 3) Insert into MySQL
try {
  $pdo = new PDO(
    'mysql:host=127.0.0.1;'.
    'dbname=marine_waste;charset=utf8mb4',
    'root','',
    [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]
  );
} catch (PDOException $e){
  http_response_code(500);
  exit(json_encode([
    'success'=>false,
    'message'=>'DB connection failed'
  ]));
}

$sql = "INSERT INTO reports
 (name,email,description,image_path,
  waste_plastic,waste_metal,waste_glass,
  waste_organic,waste_other,
  latitude,longitude,address)
 VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  $name,$email,$desc,$imagePath,
  $plast,$metal,$glass,$organic,$other,
  $lat,$lng,$addr
]);

$reportId = $pdo->lastInsertId();
echo json_encode([
  'success'=>true,
  'report_id'=> $reportId
]);
