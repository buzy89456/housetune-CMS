<?php
require_once("../db-connect.php");

if (!isset($_POST["name"])) {
    echo "請循正常管道進入本頁";
    exit;
}


$name = $_POST["name"];
$category_room = $_POST["category_room"];
$category_product = $_POST["category_product"];
$price = $_POST["price"];
$img_1 = $_FILES["img_1"]["name"];
$img_2 = $_FILES["img_2"]["name"];
$description = $_POST["description"];
$now = date('Y-m-d H:i:s');
$valid = $_POST["posted"];

$sql = "SELECT * FROM category_room";
$categoryRoomResult = $conn->query($sql);
foreach ($categoryRoomResult as $item) {
    if ($_POST["category_room"] == $item['id']) {
        $categoryRoomName = $item['name'];
    }
}

if ($_FILES['img_1']['error'] == 0) {
    $file = explode(".", $img_1);
    $new_name =  $file[0] . "-" . date('ymdhis') . "-" . rand(0, 10);
    move_uploaded_file($_FILES["img_1"]["tmp_name"], "./images/$categoryRoomName/" . $new_name . "." . $file[1]);
    $img_1 = $new_name . "." . $file[1];
}

if ($_FILES['img_2']['error'] == 0) {
    $file = explode(".", $img_2);
    $new_name =  $file[0] . "-" . date('ymdhis') . "-" . rand(0, 10);
    move_uploaded_file($_FILES["img_2"]["tmp_name"], "./images/$categoryRoomName/" . $new_name . "." . $file[1]);
    $img_2 = $new_name . "." . $file[1];
}


$sql = "INSERT INTO product (name, category_room, category_product, price, img_1, img_2, description, created_at, updated_at, valid)	
VALUES ('$name', '$category_room', '$category_product', '$price', '$img_1', '$img_2', '$description', '$now', '$now', $valid)";

if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
    echo "新增資料完成, id: $last_id";
} else {
    echo "新增資料錯誤: " . $conn->error;
}

$conn->close();

header("location: product-list.php");