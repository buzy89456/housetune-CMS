<?php
require_once("../db-connect.php");

if (!isset($_POST["name"])) {
    echo "請循正常管道進入本頁";
    exit;
}

$category = $_POST["category"];
$name = $_POST["name"];

var_dump($category);

if ($_POST["category"] == 1) {
    $sql = "INSERT INTO category_room (name) VALUES ('$name')";
} elseif ($_POST["category"] == 2) {
    $sql = "INSERT INTO category_product (name)	VALUES ('$name')";
}

if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
    echo "新增資料完成, id: $last_id";
    header("location: category-list.php");
} else {
    echo "新增資料錯誤: " . $conn->error;
}

$conn->close();

// header("location: category-list.php");