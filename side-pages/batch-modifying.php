<?php
require_once("../db-connect.php");

$item = $_POST["item"];

// $sql = "SELECT * FROM product WHERE id=$id";
// $productExist = $result->num_rows;

if (!isset($item)) {
    header ('location: '.$_SERVER['HTTP_REFERER']);
}
$str = implode("','", $item);

if ($_REQUEST["solfDelete"]) {
    $sql = "UPDATE product SET  valid=0 WHERE id in('{$str}')";
    if ($conn->query($sql)) {
        echo "更新成功";
        header ('location: '.$_SERVER['HTTP_REFERER']);
    } else {
        echo "更新失敗";
    }
}
if ($_REQUEST["cancelDelete"]) {
    $sql = "UPDATE product SET valid=1 WHERE id in('{$str}')";
    if ($conn->query($sql)) {
        echo "更新成功";
        header ('location: '.$_SERVER['HTTP_REFERER']);
    } else {
        echo "更新失敗";
    }
}
if ($_REQUEST["deleteAll"]) {
    $sql="DELETE FROM product  WHERE id in('{$str}')";
    if ($conn->query($sql)) {
        echo "更新成功";
        header ('location: '.$_SERVER['HTTP_REFERER']);
    } else {
        echo "更新失敗";
    }
}
