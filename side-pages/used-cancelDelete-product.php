<?php
require_once("../db-connect-used.php");

$id=$_GET["id"];

//$sql="DELETE FROM users WHERE id='$id'";
$sql= "UPDATE used_product SET valid=1 WHERE id='$id'";

// echo $sql;

if ($conn->query($sql) === TRUE) {
    echo "上架成功";
    header("location: used-product-list.php");
}else {
    echo "上架資料錯誤: " . $conn->error;
}
?>