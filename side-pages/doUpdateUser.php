<?php
require_once("../db-connect.php");

$id=$_POST["id"];
$account=$_POST["account"];
$name=$_POST["name"];
$address=$_POST["address"];
$phone=$_POST["phone"];
$email=$_POST["email"];
$valid=$_POST["valid"];
$now=date('Y-m-d H:i:s');


$sql="UPDATE user SET name='$name', account='$account', address='$address', phone='$phone', email='$email', valid='$valid', last_modified='$now' WHERE id=$id ";

if ($conn->query($sql) === TRUE) {
    header("refresh:3; edit-user.php?id=".$id);
    print("更新資料完成，3秒後跳轉回會員資訊頁");
} else {
    echo "更新資料錯誤: " . $conn->error;
}

$conn->close();

?>