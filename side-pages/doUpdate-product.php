<?php
require_once("../db-connect.php");

if (!isset($_POST["name"])) {
    echo "請循正常管道進入本頁";
    exit;
}

$id = $_POST["id"];
$name = $_POST["name"];
$category_room = $_POST["category_room"];
$category_product = $_POST["category_product"];
$price = $_POST["price"];
$description = $_POST["description"];
$now = date('Y-m-d H:i:s');
$valid = $_POST["posted"];

$img_1 = $_FILES["img_1"]["name"];
$img_2 = $_FILES["img_2"]["name"];
$old_img_1 = $_POST["old_img_1"];
$old_img_2 = $_POST["old_img_2"];

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
} else {
    $img_1 = $old_img_1;
}

if ($_FILES['img_2']['error'] == 0) {
    $file = explode(".", $img_2);
    $new_name =  $file[0] . "-" . date('ymdhis') . "-" . rand(0, 10);
    move_uploaded_file($_FILES["img_2"]["tmp_name"], "./images/$categoryRoomName/" . $new_name . "." . $file[1]);
    $img_2 = $new_name . "." . $file[1];
} else {
    $img_2 = $old_img_2;
}



$sql = "UPDATE product SET name='$name', category_room='$category_room', category_product='$category_product', price='$price', img_1='$img_1', img_2='$img_2', description='$description', updated_at='$now', valid=$valid WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    echo "更新成功";
} else {
    echo "更新資料錯誤: " . $conn->error;
}
header("location: product-list.php");