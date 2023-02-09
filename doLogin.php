<?php
session_start(); //加入session

// 避免重複登入
if(isset($_SESSION["user"])){
    header("location: side-pages/dashboard.php");
}

require_once("db-connect.php");

if(!isset($_POST["account"])){
    echo "請從正常管道進入";
    exit;
}

// 由於資料庫的資料已經混碼過 需要再次將post的資料混碼進行比對
$account=$_POST["account"];
$password=$_POST["password"];
// $password=md5($password);

$sql="SELECT * FROM manager WHERE account='$account' AND  password ='$password'";

$result = $conn->query($sql);
$userCount = $result->num_rows;
if($userCount>0){
    $row = $result->fetch_assoc();
    unset($_SESSION["error"]); //登入成功將session的錯誤訊息刪掉
    //登入成功 同時將資訊存到session裡面
    $_SESSION["user"]=[
        "account"=>$row["account"],
        "name"=>$row["name"],
        "email"=>$row["email"]
    ];
    // var_dump($_SESSION["user"]);
    header("location: side-pages/dashboard.php");
}
else{
    //計算
    if(!isset($_SESSION["error"]["times"])){
        $_SESSION["error"]["times"]=1;
    }else{
        $_SESSION["error"]["times"]++;
    }
    
    // echo "登入失敗，請確認帳號密碼";
    // 將失敗訊息存到session裡面
    $_SESSION["error"]["message"]="登入失敗，請確認帳號密碼";
    // echo $_SESSION["error"];
    // 輸入錯誤，重新跳轉
    header("location: background-login.php");
}
