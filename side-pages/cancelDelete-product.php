<?php
require_once("../db-connect.php");

$id=$_GET["id"];
// $sql="DELETE FROM users WHERE id='$id'";

// cancel delete
$sql="UPDATE product SET valid=1 WHERE id='$id'";

echo $sql;

if ($conn->query($sql) === TRUE) {
    echo "上架成功";
    header("location: product-list.php");
}else {
    echo "資料錯誤: " . $conn->error;
}
?>