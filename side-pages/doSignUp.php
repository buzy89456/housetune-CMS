<?php

require_once("../db-connect.php");
$account=$_POST["account"];
$name=$_POST["name"];
$phone=$_POST["phone"];
$email=$_POST["email"];
$address=$_POST["address"];
$password=$_POST["password"];
$repassword=$_POST["repassword"];
// $birthYear=$_POST["birthYear"];
// $birthMonth=$_POST["birthMonth"];
// $birthDate=$_POST["birthDate"];

if(empty($account)){
    header("refresh:3; sign-up-ui.php");
    print("註冊會員失敗，請輸入帳號 <br> 3秒後跳轉回註冊頁面");
    exit;
}

$accountLength=strlen($account);
if($accountLength<4 || $accountLength>20){
    header("refresh:3; sign-up-ui.php");
    print("註冊會員失敗，請輸入4-20位數密碼 <br> 3秒後跳轉回註冊頁面");
    exit;
}
if(empty($name)){
    header("refresh:3; sign-up-ui.php");
    print("註冊會員失敗，請輸入姓名 <br> 3秒後跳轉回註冊頁面");
    exit;
}
if(empty($phone)){
    header("refresh:3; sign-up-ui.php");
    print("註冊會員失敗，請輸入電話 <br> 3秒後跳轉回註冊頁面");
    exit;
}
if(empty($email)){
    header("refresh:3; sign-up-ui.php");
    print("註冊會員失敗，請輸入email <br> 3秒後跳轉回註冊頁面");
    exit;
}
if(empty($address)){
    header("refresh:3; sign-up-ui.php");
    print("註冊會員失敗，請輸入地址 <br> 3秒後跳轉回註冊頁面");
    exit;
}
if($password !=$repassword){
    header("refresh:3; sign-up-ui.php");
    print("註冊會員失敗，密碼前後不一致 <br> 3秒後跳轉回註冊頁面");
    exit;
}

$sql="SELECT * FROM user WHERE account='$account'";
$result= $conn ->query($sql);
$userCount=$result->num_rows;
if($userCount>0){
    header("refresh:3; sign-up-ui.php");
    print("註冊會員失敗，該帳號已經存在 <br> 5秒後跳轉回註冊頁面");
    exit;
}
$password=md5($password);


//md5() 簡易加密方法 但不推薦
$now=date('Y-m-d H:i:s');

$sqlCreate="INSERT INTO user (account, password,name,phone,email,address, created_at, last_modified, valid)

VALUES ('$account', '$password','$name','$phone','$email','$address','$now', '$now', 1)";

if ($conn->query($sqlCreate) === TRUE) {
    $last_id = $conn->insert_id;
    header("refresh:5; sign-up-ui.php");
    print("註冊會員成功，會員id為".$last_id."<br> 5秒後跳轉回註冊頁面");
} else {
    echo "新增資料表錯誤: " . $conn->error;
}
$conn->close();




