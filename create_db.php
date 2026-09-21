<?php
try {
    $conn = new mysqli('localhost', 'root', '');
    
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    
    $sql = "CREATE DATABASE IF NOT EXISTS aula_coffe_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    
    if ($conn->query($sql) === TRUE) {
        echo "Database created successfully or already exists\n";
    } else {
        echo "Error creating database: " . $conn->error . "\n";
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
