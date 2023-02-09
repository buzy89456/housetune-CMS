<?php
require_once("../db-connect.php");

$id = $_GET["id"];
$sql = "DELETE FROM category_room WHERE id='$id'";

// echo $sql;
if ($conn->query($sql) === TRUE) {
    echo "刪除成功";
    header("location: category-list.php");
} else {
    echo "刪除資料錯誤: " . $conn->error;
}
