<?php

header('Content-Type: text/html');

$host = 'localhost:3306';
$db_name = 'rest_api';
$username = 'root';
$password = 'galaxys23';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Menghitung total tanah kering (status = 1)
    $stmtDry = $conn->prepare("SELECT COUNT(*) as dry_count FROM soil_data WHERE status = 1");
    $stmtDry->execute();
    $dryCount = $stmtDry->fetch(PDO::FETCH_ASSOC)['dry_count'];

    // Menghitung total tanah basah (status = 0)
    $stmtWet = $conn->prepare("SELECT COUNT(*) as wet_count FROM soil_data WHERE status = 0");
    $stmtWet->execute();
    $wetCount = $stmtWet->fetch(PDO::FETCH_ASSOC)['wet_count'];

    // Mengambil data kelembapan terakhir yang terdeteksi
    $stmtLastMoisture = $conn->prepare("SELECT moisture_level, status FROM soil_data ORDER BY timestamp DESC LIMIT 1");
    $stmtLastMoisture->execute();
    $lastMoistureData = $stmtLastMoisture->fetch(PDO::FETCH_ASSOC);
    $lastMoisture = $lastMoistureData['moisture_level'];
    $lastStatus = $lastMoistureData['status'];

} catch(PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
    die();
}

// Mendapatkan tanggal hari ini
$dateToday = date('Y-m-d');

// Mengatur warna berdasarkan status terakhir
$statusColor = $lastStatus ? '#4CAF50' : '#9C27B0'; // Hijau untuk kering, ungu untuk basah

?>

<!DOCTYPE html>
<html>
<head>
    <title>Ringkasan Kondisi Tanah Hari Ini</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }
        .box {
            border: 1px solid #ddd;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        h1, h2, p {
            margin: 0 0 10px;
        }
        h1 {
            color: #333;
        }
        h2 {
            color: #666;
        }
        p {
            font-size: 1.2em;
            color: #333;
        }
        .status {
            font-size: 1.5em;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Ringkasan Kondisi Tanah Hari Ini</h1>
    <p><?= $dateToday ?></p>

    <div class="box">
        <h2>Total Tanah Kering</h2>
        <p><?= $dryCount ?> kali</p>
    </div>

    <div class="box">
        <h2>Total Tanah Basah</h2>
        <p><?= $wetCount ?> kali</p>
    </div>

    <div class="box">
        <h2>Kelembapan Terakhir Terdeteksi</h2>
        <p><?= $lastMoisture ?></p>
        <div class="status" style="background-color: <?= $statusColor ?>">
            <?= $lastStatus ? 'Kering' : 'Basah' ?>
        </div>
    </div>
</div>

</body>
</html>
