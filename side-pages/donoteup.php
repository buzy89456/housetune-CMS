<?php

require_once("../db-connect.php");


// if(!isset($_POST["id"])){
//    echo"請循正常管道進入本頁";
//    exit;
// }

$note=$_POST["note"];
$id=$_POST["id"];
$page= $_GET["page"];


$sql="UPDATE order_list SET note='$note' WHERE id=$id";

if ($conn->query($sql) === TRUE) {
   echo "新增備註成功";
   header("location: order-list.php?page=$page");
} else {
   echo "新增資料錯誤: " . $conn->error;
}

