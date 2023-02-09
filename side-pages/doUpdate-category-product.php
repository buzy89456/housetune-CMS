<?php
require_once("../db-connect.php");

if (!isset($_POST["name"])) {
    echo "請循正常管道進入本頁";
    exit;
}

$id = $_POST["id"];
$newId = $_POST["newId"];
$name = $_POST["name"];

$sql = "UPDATE category_product SET name='$name', id='$newId' WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    echo "更新成功";
    header("location: category-list.php");
} else {
    echo "更新資料錯誤: " . $conn->error;
}
