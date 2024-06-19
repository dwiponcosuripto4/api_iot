<?php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id_sensor = $_POST['id_sensor'] ?? null;
    $moisture_level = $_POST['moisture_level'] ?? null;
    $status = $_POST['status'] ?? null;
        
    if ($id_sensor !== null && $moisture_level !== null && $status !== null) {
            
        $host = 'localhost:3306';
        $db_name = 'rest_api';
        $username = 'root';
        $password = 'galaxys23';
            
        try {
            $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
            $stmt = $conn->prepare("INSERT INTO soil_data (id_sensor, moisture_level, status) VALUES (:id_sensor, :moisture_level, :status)");
                 
            $stmt->bindParam(':id_sensor', $id_sensor);
            $stmt->bindParam(':moisture_level', $moisture_level);
            $stmt->bindParam(':status', $status, PDO::PARAM_BOOL);

            $stmt->execute();
                
            echo json_encode(array('success' => true, 'message' => 'Data soil berhasil disimpan.'));
        } catch(PDOException $e) {
            echo json_encode(array('success' => false, 'message' => 'Gagal menyimpan data soil: ' . $e->getMessage()));
        }
            
    } else {
        echo json_encode(array('success' => false, 'message' => 'Data soil tidak lengkap.'));
    }
    
} else {
    echo json_encode(array('success' => false, 'message' => 'Metode yang digunakan tidak diizinkan.'));
}

?>
