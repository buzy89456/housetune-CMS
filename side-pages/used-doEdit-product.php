<?php
require_once("../db-connect-used.php");


$id = $_POST["id"];
$name = $_POST["name"];
$category_room = $_POST["category_room"];
$original_price = $_POST["original_price"];
$price = $_POST["price"];
$description = $_POST["description"];
$now = date('Y-m-d H:i:s');
$valid = $_POST["posted"];

$new_img= $_FILES["new_img"]["name"];

$old_img= $_POST["old_img"];

var_dump($id);

$sql = "SELECT * FROM category_room";
$categoryRoomResult = $conn->query($sql);
foreach ($categoryRoomResult as $item) {
    if ($_POST["category_room"] == $item['id']) {
        $categoryRoomName = $item['name'];
    }
}

if ($_FILES['new_img']['error'] == 0) {
    $file = explode(".", $new_img);
    $new_name =  $file[0] . "-" . date('ymdhis') . "-" . rand(0, 10);
    move_uploaded_file($_FILES["new_img"]["tmp_name"], "./used/" . $new_name . "." . $file[1]);
    $new_img = $new_name . "." . $file[1];
} else {
    $new_img= $old_img;
}

// if ($_FILES['img_2']['error'] == 0) {
//     $file = explode(".", $img_2);
//     $new_name =  $file[0] . "-" . date('ymdhis') . "-" . rand(0, 10);
//     move_uploaded_file($_FILES["img_2"]["tmp_name"], "./images/$categoryRoomName/" . $new_name . "." . $file[1]);
//     $img_2 = $new_name . "." . $file[1];
// } else {
//     $img_2 = $old_img_2;
// }



$sql1 = "UPDATE used_product SET name='$name', category_room ='$category_room', original_price='$original_price', price='$price', img='$new_img', description='$description', updated_at='$now', valid=$valid WHERE id='$id'";

if ($conn->query($sql1) === TRUE) {
    echo "更新成功";
} else {
    echo "更新資料錯誤: " . $conn->error;
}
header("location: used-product-list.php");