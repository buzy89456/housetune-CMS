<?php
session_start();
require_once("../db-connect.php");


require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;


if (isset($_POST["export_excel_btn"])) {
    $file_ext_name = $_POST['export_file_type'];
    $fileName = "product-list";

    $sql = "SELECT * FROM product";
    $result = $conn->query($sql);

    if (mysqli_num_rows($result) > 0) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Category Room');
        $sheet->setCellValue('D1', 'Category Product');
        $sheet->setCellValue('E1', 'Price');
        $sheet->setCellValue('F1', 'Img_1');
        $sheet->setCellValue('G1', 'Img_2');
        $sheet->setCellValue('H1', 'Description');
        $sheet->setCellValue('I1', 'Created At');
        $sheet->setCellValue('J1', 'Valid');

        $rowCount = 2;
        foreach ($result as $data) {
            $sheet->setCellValue('A' . $rowCount, $data['id']);
            $sheet->setCellValue('B' . $rowCount, $data['name']);
            $sheet->setCellValue('C' . $rowCount, $data['category_room']);
            $sheet->setCellValue('D' . $rowCount, $data['category_product']);
            $sheet->setCellValue('E' . $rowCount, $data['price']);
            $sheet->setCellValue('F' . $rowCount, $data['img_1']);
            $sheet->setCellValue('G' . $rowCount, $data['img_2']);
            $sheet->setCellValue('H' . $rowCount, $data['description']);
            $sheet->setCellValue('I' . $rowCount, $data['created_at']);
            $sheet->setCellValue('J' . $rowCount, $data['valid']);
            $rowCount++;
        }
        if ($file_ext_name == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
            $final_filename = $fileName . '.xlsx';
        } elseif ($file_ext_name == 'xls') {
            $writer = new Xls($spreadsheet);
            $final_filename = $fileName . '.xls';
        } elseif ($file_ext_name == 'csv') {
            $writer = new Csv($spreadsheet);
            $final_filename = $fileName . '.csv';
        }

        header('Content-Type: application/vnd.openexmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attactment; filename="' . urlencode($final_filename) . '"');
        $writer->save('php://output');
    } else {
        $_SESSION['message'] =  "沒有找到記錄";
        header('location: excel-product.php');
        exit;
    }
}




if (isset($_POST["save_excel_data"])) {
    $fileName = $_FILES["import_file"]["name"];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowed_ext = ['xls', 'csv', 'xlsx'];

    if (in_array($file_ext, $allowed_ext)) {
        $inputFileNamePath = $_FILES["import_file"]["tmp_name"];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = "0";
        foreach ($data as $row) {
            if ($count > 0) {
                $name = $row["0"];
                $category_room = $row["1"];
                $category_product = $row["2"];
                $price = $row["3"];
                $img_1 = $row["4"];
                $img_2 = $row["5"];
                $description = $row["6"];
                $valid = $row["7"];
                $now = date('Y-m-d H:i:s');


                $sql = "INSERT INTO product (name, category_room, category_product, price, img_1, img_2, description, created_at, updated_at, valid) VALUES ('$name', '$category_room', '$category_product', '$price', '$img_1', '$img_2', '$description', '$now', '$now', $valid)";
                $result = mysqli_query($conn, $sql);
                $msg = true;
            } else {
                $count = "1";
            }
        }

        if (isset($msg)) {
            $_SESSION['message'] =  "上傳成功";
            header('location: excel-product.php');
            exit;
        } else {
            $_SESSION['message'] =  "上傳失敗";
            header('location: excel-product.php');
            exit;
        }
    } else {
        $_SESSION['message'] =  "無效的檔案";
        header('location: excel-product.php');
        exit;
    }
}
