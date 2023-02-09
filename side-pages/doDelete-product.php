<?php
require_once("../db-connect.php");

$id=$_GET["id"];
$sql="DELETE FROM product WHERE id='$id'";

// soft delete
// $sql="UPDATE product SET valid=0 WHERE id='$id'";

echo $sql;

if ($conn->query($sql) === TRUE) {
    echo "刪除成功";
    header("location: product-list.php");
}else {
    echo "刪除資料錯誤: " . $conn->error;
}
?>