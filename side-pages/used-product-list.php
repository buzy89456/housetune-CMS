<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("location: ../background-login.php");
}
require_once("../db-connect-used.php");
// 圖片路徑 sqlImg
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
}
$per_page = 5;
$page_start = ($page - 1) * $per_page; //要嘛page 由$_GET得到 要嘛page ＝1 所以第二頁是從第六筆[5]開始

if (isset($_GET["search"])) {
    $search = $_GET["search"];
    $sql = "SELECT used_product.*,category_room.name AS category_name FROM used_product JOIN category_room on used_product.category_room = category_room.id WHERE used_product.name LIKE '%$search%' ORDER BY created_at DESC ";
    $sql1 = "SELECT used_product.*,category_room.name AS category_name FROM used_product JOIN category_room on used_product.category_room = category_room.id WHERE used_product.name LIKE '%$search%' ORDER BY created_at DESC LIMIT $page_start, $per_page";
} else if (isset($_GET["minPrice"])) {
    $minPrice = $_GET["minPrice"];
    $maxPrice = $_GET["maxPrice"];
    if (empty($minPrice)) $minPrice = 0;
    if (empty($maxPrice)) $maxPrice = 99999;
    $sql = "SELECT used_product.*,category_room.name AS category_name FROM used_product JOIN category_room on used_product.category_room = category_room.id WHERE used_product.price>=$minPrice AND used_product.price<=$maxPrice ORDER BY created_at DESC ";
    $sql1 = "SELECT used_product.*,category_room.name AS category_name FROM used_product JOIN category_room on used_product.category_room = category_room.id WHERE  used_product.price>= $minPrice AND used_product.price <=$maxPrice ORDER BY created_at DESC LIMIT $page_start, $per_page";
} else if (isset($_GET["bought_time"])) {
    $bought_time = $_GET["bought_time"];
    $now = date("Y");
    if ($bought_time == 1) {
        $sql = "SELECT used_product.*,category_room.name AS category_name FROM used_product JOIN category_room on used_product.category_room =category_room.id WHERE $now-used_product.bought_time<=3 ORDER BY created_at DESC ";
        $sql1 = "SELECT used_product.*,category_room.name AS category_name FROM used_product JOIN category_room on used_product.category_room = category_room.id WHERE $now-used_product.bought_time<=3 ORDER BY created_at DESC LIMIT $page_start, $per_page";
    } else if ($bought_time == 2) {
        $sql = "SELECT used_product.*,category_room.name AS category_name FROM used_product JOIN category_room on used_product.category_room = category_room.id WHERE $now-used_product.bought_time>3 AND $now-used_product.bought_time<=6 ORDER BY created_at DESC ";
        $sql1 = "SELECT used_product.*,category_room.name AS category_name FROM used_product JOIN category_room on used_product.category_room = category_room.id WHERE $now-used_product.bought_time>3 AND $now-used_product.bought_time<=6 ORDER BY created_at DESC LIMIT $page_start, $per_page";
    } else if ($bought_time == 3) {
        $sql = "SELECT used_product.*,category_room.name AS category_name FROM used_product JOIN category_room on used_product.category_room = category_room.id WHERE $now-used_product.bought_time>6 AND $now-used_product.bought_time<=10 ORDER BY created_at DESC ";
        $sql1 = "SELECT used_product.*,category_room.name AS category_name FROM used_product JOIN category_room on used_product.category_room = category_room.id WHERE $now-used_product.bought_time>6 AND $now-used_product.bought_time<=10 ORDER BY created_at DESC LIMIT $page_start, $per_page";
    } else {
        $sql = "SELECT used_product.*,category_room.name AS category_name FROM used_product JOIN category_room on used_product.category_room = category_room.id WHERE $now-used_product.bought_time>10 ORDER BY created_at DESC";
        $sql1 = "SELECT used_product.*,category_room.name AS category_name FROM used_product JOIN category_room on used_product.category_room = category_room.id WHERE $now-used_product.bought_time>10 ORDER BY created_at DESC LIMIT $page_start, $per_page";
    }
} else {
    $sql = "SELECT used_product.*,category_room.name AS category_name FROM used_product JOIN category_room on used_product.category_room = category_room.id  ORDER BY created_at DESC";
    $sql1 = "SELECT used_product.*,category_room.name AS category_name FROM used_product JOIN category_room on used_product.category_room = category_room.id ORDER BY created_at DESC LIMIT $page_start, $per_page";
}

$result = $conn->query($sql);
$productCount = $result->num_rows;

$result1 = $conn->query($sql1);
$rows = $result1->fetch_all(MYSQLI_ASSOC);
$totalPage = ceil($productCount / $per_page);

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

        .pic {
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
            <!-- <div class="py-2 d-flex justify-content-end">
        <a href="users.php" class="btn btn-info">Add User</a>
    </div> -->

            <div class="py-2 d-flex">
                <form action="used-product-list.php" class="d-flex ">
                    <!-- // 提交表单时，发送表单数据到名为 "product-list.php" 的文件（处理输入）： -->
                    <input type="number" class="form-control mx-2" name="minPrice" value="<?php if (isset($_GET["minPrice"])) echo $minPrice ?>">
                    <div>~</div>
                    <div class="input-group">
                        <input type="number" class="form-control ms-2" name="maxPrice" value="<?php if (isset($_GET["maxPrice"])) echo $maxPrice ?>">
                        <button class="btn btn-info me-2" type="submit">篩選</button>
                    </div>

                </form>
                <form action="used-product-list.php" method="get">
                    <div class="input-group">
                        <select name="bought_time" id="" class="p-2">
                            <option value="">依年份篩選</option>
                            <option value="1" <?php if (isset($bought_time) && $bought_time == '1') echo "selected" ?>>三年內</option>
                            <option value="2" <?php if (isset($bought_time) && $bought_time == '2') echo "selected" ?>>三～六年內</option>
                            <option value="3" <?php if (isset($bought_time) && $bought_time == '3') echo "selected" ?>>六～十年內</option>
                            <option value="4" <?php if (isset($bought_time) && $bought_time == '4') echo "selected" ?>>10年以上</option>
                        </select>
                        <button class="btn btn-info me-2" type="submit">篩選</button>
                    </div>
                </form>
                <form action="">

                </form>
                <form action="used-product-list.php" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search">
                        <button type="submit" class="btn btn-info">搜尋</button>
                    </div>
                </form>

            </div>

            <?php if (isset($_GET["search"]) || isset($_GET["minPrice"]) || isset($_GET["bought_time"])) : ?>
                <div class="py-2">
                    <a class="btn btn-info" href="used-product-list.php">回商品列表</a>
                </div>
                <h1>搜尋結果</h1>
            <?php endif; ?>

            <div class="py-2">
                共<?= $productCount ?> 個
            </div>
            <?php //var_dump($rows)
            ?>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr class="table-active">
                        <th>id</th>
                        <th>name</th>
                        <th>原價</th>
                        <th>二手價</th>
                        <th>照片</th>
                        <th>房間</th>
                        <td>購買年份</td>
                        <th>上架日期</th>
                        <th>更新日期</th>
                        <th></th>
                    </tr>
                </thead>
                <?php if ($productCount > 0) : ?>
                    <tbody>
                        <?php foreach ($rows as $row) : ?>
                            <!-- foreach($rows as $row) 語法會對陣列 array_expression 做迴圈，並將目前所指元素的值放到 $value 變數裡，然後陣列裡的指標會跟著移到下一個元素的位置。設定新的變數$row 從＄rows 裡面掉出的資料都是＄ｒｏｗ＄ｒｏｗ -->
                            <tr>
                                <td><?= $row["id"] ?></td>
                                <td><?= $row["name"] ?></td>
                                <td><?= $row["original_price"] ?></td>
                                <td><?= $row["price"] ?></td>
                                <td>
                                    <figure class="ratio ratio-4x3">
                                        <img class="object-cover" src="./used/<?= $row["img"] ?>" alt="<?= $row["img"] ?>">
                                    </figure>
                                </td>
                                <td><?= $row["category_name"] ?></td>
                                <td><?= $row["bought_time"] ?></td>
                                <td><?= $row["created_at"] ?></td>
                                <td><?= $row["updated_at"] ?></td>
                                <td>
                                    <a class="btn btn-primary" href="used-edit-product.php?id=<?= $row["id"] ?>&name=<?= $row["name"] ?>">編輯</a>
                                    <?php if ($row["valid"] == 1) : ?>
                                        <a class="btn btn-warning " href="used-softDelete-product.php?id=<?= $row["id"] ?>" id="postUnpost" onclick="toggle()">下架</a>
                                    <?php elseif ($row["valid"] == 0) : ?>
                                        <a class="btn btn-success" href="used-cancelDelete-product.php?id=<?= $row["id"] ?>">上架</a>
                                    <?php endif; ?>
                                    <!-- <a class="btn btn-warning" href="used-softDelete-product.php?id=<?= $row["id"] ?>" id="postUnpost" onclick="toggle()">下架</a> -->
                                    <a class="btn btn-danger text-light" href="used-hardDelete-product.php?id=<?= $row["id"] ?>">刪除</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                <?php endif; ?>
            </table>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <!-- <li class="page-item"><a class="page-link" href="users.php?page=1">1</a></li> -->
                    <!-- <li class="page-item"><a class="page-link" href="users.php?page=2">2</a></li> -->
                    <!-- <li class="page-item"><a class="page-link" href="users.php?page=3">3</a></li> -->
                    <!-- <li class="page-item"><a class="page-link" href="users.php?page=4">4</a></li> -->
                    <?php for ($i = 1; $i <= $totalPage; $i++) : ?>
                        <li class="page-item <?php if ($i == $page) echo "active"; ?>">
                            <a class="page-link" href="used-product-list.php?page=<?= $i ?><?php if (isset($search)) echo "&search=$search"; ?><?php if (isset($bought_time)) echo "&bought_time=$bought_time"; ?><?php if (isset($minPrice)) echo "&minPrice=$minPrice"; ?><?php if (isset($maxPrice)) echo "&maxPrice=$maxPrice"; ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
</body>
</div>

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
        let seconds = today.getSeconds()
        let minutes = today.getMinutes()
        let hours = today.getHours()
        if (seconds < 10) seconds = "0" + seconds
        if (minutes < 10) minutes = "0" + minutes
        if (hours < 10) hours = "0" + hours
        let now = today.getFullYear() + "/" + month + "/" + today.getDate() + "(" + weekday[today.getDay()] + ") " + hours + ":" + minutes + ":" + seconds;
        // console.log(now);
        clockContent.innerText = now;
    }

    ticktick();
    setInterval(ticktick, 1000)
</script>
</body>

</html>