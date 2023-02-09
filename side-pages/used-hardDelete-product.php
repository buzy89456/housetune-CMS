<?php
require_once("../db-connect-used.php");

$id=$_GET["id"];

$sql="DELETE FROM used_product WHERE id='$id'";
//$sql= "UPDATE used_product SET valid=0 WHERE id='$id'";
// 軟刪除(soft delete) 將valid 值由1改成0 而不是完全從系統裡刪除資料  

//  echo $sql;
if ($conn->query($sql) === TRUE) {
    echo "硬刪除成功";
    header("location: used-product-list.php");
} else {
    echo "硬刪除資料錯誤: " . $conn->error;
}
