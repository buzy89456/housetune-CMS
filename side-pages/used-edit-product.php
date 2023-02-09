<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("location: ../background-login.php");
}
require_once("../db-connect-used.php");
// 圖片路徑 sqlImg
$id=$_GET["id"];

$sql= "SELECT * FROM used_product WHERE used_product.id = $id";
$result=$conn->query($sql);
//$productCount=$conn->query($sql); Notice: Object of class mysqli_result could not be converted to int in /opt/lampp/htdocs/project-hw/edit-product-used.php on line 44 用result->num_rows才知道有幾行
$productCount = $result->num_rows;
$row= $result->fetch_assoc();
// var_dump($row);

$sql1 = "SELECT * FROM category_room";
$categoryRoomResult = $conn->query($sql1);
$categoryRoom =$categoryRoomResult->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="en">

<head>
    <title>Housetune後臺管理系統理系統</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.2.0-beta1 -->
    <link rel="stylesheet" href="assets/bootstrap-table.min.css">
    <link rel="stylesheet" href="../scss/all.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.2.0/css/all.css" crossorigin="anonymous">
    <style>
        .form-control {
            width: 90px !important;

        }
     
        .pic{
            height: 200px !important;
            width: 200px;
        }
    </style>
</head>

<body>
    <nav class="navbar fixed-top shadow bg-dark">
        <div class="container-fluid d-flex justify-content-between align-items-center mx-4">
            <div class="d-flex align-items-center">
                <a class="text-decoration-none fs-5 text-white m-2" href="dashboard.php">Housetune logo</a>
                <div class="text-white text-decoration-none ms-5">歡迎使用 Housetune 後臺管理系統</div>
            </div>
            <a class="btn btn-dark text-white" href="logout.php">Log out</a>
        </div>
    </nav>
    <aside class="left-aside position-fixed border-end bg-secondary d-flex flex-column justify-content-between">
        <nav class="aside-menu">
            <div class="pt-1 pb-2 px-3">
                Hi,<?= $_SESSION["user"]["account"] ?>
            </div>
            <ul class="list-unstyled ">
                <li>
                    <a href="dashboard.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M2 13.5V7h1v6.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V7h1v6.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5zm11-11V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z" />
                            <path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z" />
                        </svg>
                        主頁</a>
                </li>
                <li>
                    <a href="order-list.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard" viewBox="0 0 16 16">
                            <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z" />
                            <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z" />
                        </svg>
                        訂單管理</a>
                </li>
                <li>
                    <a href="product-list.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                        商品管理</a>
                </li>
                <li>
                    <a href="category-list.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filter-left" viewBox="0 0 16 16">
                            <path d="M2 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z" />
                        </svg>
                        分類管理</a>
                </li>
                <li>
                    <a href="user-list.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                            <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z" />
                        </svg>
                        使用者管理</a>
                </li>
                <li>
                    <a href="coupons.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ticket" viewBox="0 0 16 16">
                            <path d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5V6a.5.5 0 0 1-.5.5 1.5 1.5 0 0 0 0 3 .5.5 0 0 1 .5.5v1.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5V10a.5.5 0 0 1 .5-.5 1.5 1.5 0 1 0 0-3A.5.5 0 0 1 0 6V4.5ZM1.5 4a.5.5 0 0 0-.5.5v1.05a2.5 2.5 0 0 1 0 4.9v1.05a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-1.05a2.5 2.5 0 0 1 0-4.9V4.5a.5.5 0 0 0-.5-.5h-13Z" />
                        </svg>
                        優惠券</a>
                </li>
                <li>
                    <a href="ratings.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                            <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                        </svg>
                        評價中心</a>
                </li>
                <li class="px-3 pb-1  d-flex justify-content-between fw-bold mt-2">
                    二手專區
                </li>
                <li>
                    <a href="used-product-list.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                        商品管理</a>
                </li>
            </ul>
        </nav>
        <div class="time bg-info text-white pt-2">
            <h6 class="text-center" style="font-size: 15px;">現在時間</h6>
            <h6 id="time" class="text-center" style="font-size: 15px;"></h6>
        </div>
    </aside>

    <main class="main-content">
        <div class="d-flex justify-content-between mt-3">
            <h2>商品管理</h2>
        </div>
        <div class="container">
    <?php if ($productCount == 0) : ?>
            商品不存在
        <?php  else : ?>
            <div class="card mt-4">
                <div class="card-header">
                    <h4>商品編輯</h4>
                </div>
                <div class="card-body">
                    <form action="used-doEdit-product.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?=$row["id"]?>">  
                        <!--把id 傳給doEdit-product-used.php 但無須顯示出來 -->
                        <div class="row">
                            <!-- <input type="hidden" name="id" value=""> -->
                            <div class="mb-2">
                                <label for="name">商品名稱</label>
                                <input type="text" placeholder="請輸入" class="form-control" value="<?=$row["name"] ?>" name="name">
                            </div>
                            <div class="mb-2">
                                <label for="category">房間分類</label>
                                <select class="form-select" name="category_room">
                                    <option selected class="text-black-50">請選擇商品分類</option>
                                    <?php foreach ($categoryRoom as $item) : ?>
                                        <option value="<?= $item['id'] ?>" <?php if ($row["category_room"] == $item['id']) echo ("selected") ?>><?= $item['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="number">商品原價格</label>
                                <input type="text" placeholder="請輸入" class="form-control" value="<?= $row["original_price"] ?>" name="original_price">
                            </div>
                            <div class="mb-2">
                                <label for="number">商品價格</label>
                                <input type="text" placeholder="請輸入" class="form-control" value="<?= $row["price"] ?>" name="price">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">圖片</label>
                                <input type="hidden" name="old_img" value="<?= $row["img"] ?>">
                                <input class="form-control mb-3" type="file" name="new_img">
                                <!-- <input type="hidden" name="old_img_2" value=" ">
                                <input class="form-control" type="file" name="img_2"> -->
                            </div>
                            <div class="mb-2">
                                <label class="form-label">目前圖片</label>
                                <?php foreach ($categoryRoomResult as $item) : ?>
                                    <?php if ($row["category_room"] == $item['id']) : ?>
                                        <img src="../project-hw/used/<?=$row["img"]?>" class="m-4" alt="Product Image" height="150px">
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <div class="mb-2">
                                <label for="">是否發布</label>
                                <input class="form-check-input ms-3" type="radio" name="posted" value="1" <?php if ($row["valid"] == 1) echo ("checked") ?>>
                                <label class="form-check-label" for="">是</label>
                                <input class="form-check-input ms-2" type="radio" name="posted" value="0" <?php if ($row["valid"] == 0) echo ("checked") ?>>
                                <label class="form-check-label" for="">否</label>
                            </div>

                            <div class="mb-2">
                                <label for="FormControlTextarea" class="form-label">商品描述</label>
                                <textarea class="form-control" name="description" rows="5" placeholder="請輸入商品描述"><?= $row["description"] ?></textarea>
                            </div>
                        </div>
                        <button class="btn btn-info" type="submit">保存</button>
                        <a class="btn btn-secondary" href="used-product-list.php">返回</a>
                    </form>
                </div>
            </div>
        <?php  endif ?>
    </div>
</body>

    </main>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <!-- Bootstrap Table JS -->
    <script src="assets/bootstrap-table.min.js"></script>

    <script>
        const deleteAll = document.querySelector("#deleteAll");
        const cancelDelete = document.querySelector("#cancelDelete")

        //全選
        function selectAllProduct() {
            const selectAll = document.querySelector("#select-all");
            const allChecked = selectAll.checked;
            let checkboxes = document.getElementsByClassName("check-product")
            if (allChecked) {
                for (var i = 0; i < checkboxes.length; i++) {
                    checkboxes[i].checked = true;
                }
            } else {
                for (var i = 0; i < checkboxes.length; i++) {
                    checkboxes[i].checked = false;

                }
            }
        }
    </script>


    <script>
        $('#table').bootstrapTable({
            onPageChange: function(currentPage, pageSize) {
                console.log("目前頁數:" + currentPage + ",一頁顯示:" + pageSize + "筆");
            },
            formatRecordsPerPage: function(pageSize) {
                return '&nbsp;&nbsp;每頁顯示' + pageSize + '筆';
            },
            pageSize: 50, //一頁顯示幾筆
            pageList: [10, 20, 50, 100], //一頁顯示幾筆的選項

            formatShowingRows: function(fromIndex, toIndex, totalSize) {
                //目前第幾頁
                var currentPage = Math.ceil(fromIndex / this.pageSize);
                //總共幾頁
                var totalPageCount = Math.ceil(totalSize / this.pageSize);
                return '第' + currentPage + '頁&nbsp;&nbsp;共' + totalPageCount + '頁';
            }
        });
        let clockContent = document.querySelector("#time")
        function ticktick() {
            let today = new Date();

            let weekday = ["日", "一", "二", "三", "四", "五", "六"];
            let month = today.getMonth() + 1;
            let seconds= today.getSeconds()
            let minutes=today.getMinutes()
            let hours=today.getHours()
            if(seconds<10)seconds="0"+seconds
            if(minutes<10)minutes="0"+minutes
            if(hours<10)hours="0"+hours
            let now = today.getFullYear() + "/" + month + "/" + today.getDate() + "(" + weekday[today.getDay()] + ") " + hours + ":" + minutes + ":" + seconds;
            // console.log(now);
            clockContent.innerText = now;
        }

        ticktick();
        setInterval(ticktick, 1000)
    </script>
</body>

</html>