<?php
if (!isset($_GET["id"])) {
    echo "請循正常管道進入本頁";
    exit;
}
require_once("../ht-db-connect.php");

$id=$_GET["id"];

$sql="UPDATE order_list SET valid=0 WHERE id='$id'";


if ($conn->query($sql) === TRUE) {
    echo "刪除成功";
    header("location: order-list.php");
} else {
    echo "刪除資料錯誤: " . $conn->error;
}

?>

