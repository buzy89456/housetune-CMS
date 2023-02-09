<?php
$servername = "localhost";
$username = "admin";
$password = "12345";
$dbname = "housetune_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// 檢查連線
if ($conn->connect_error) { //用 "->" 代表是一個物件
    die("連線失敗: " . $conn->connect_error);
}

