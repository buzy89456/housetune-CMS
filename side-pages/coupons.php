<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("location: ../background-login.php");
}
require_once("../db-connect.php");

if (isset($_GET["search"]) && $_GET["search"] != "") {
    $search = $_GET["search"];

    $sql = "SELECT * FROM coupons WHERE coupon_name LIKE '%$search%' OR discount LIKE '%$search%' AND valid=1";
    $result = $conn->query($sql);
    $couponsCount = $result->num_rows;
} else if (isset($_GET["min"])) {
    $min = $_GET["min"];
    $max = $_GET["max"];

    if (empty($min)) $min = 0;
    if (empty($max)) $max = 9999999;
    $sql = "SELECT * FROM coupons WHERE discount >= $min AND discount <=$max AND valid=1";
    $result = $conn->query($sql);
    $couponsCount = $result->num_rows;
} else if (isset($_GET["expense_min"])) {
    $expense_min = $_GET["expense_min"];
    $expense_max = $_GET["expense_max"];

    if (empty($expense_min)) $expense_min = 0;
    if (empty($expense_max)) $expense_max = 9999999;
    $sql = "SELECT * FROM coupons WHERE min_expense >= $expense_min AND min_expense <=$expense_max AND valid=1";
    $result = $conn->query($sql);
    $couponsCount = $result->num_rows;
} else if (isset($_GET["date_start"])) {
    $date_start = $_GET["date_start"];
    $date_end = $_GET["date_end"];

    $sql = "SELECT * FROM coupons WHERE start_date >= '$date_start' AND end_date <= '$date_end' AND valid=1";
    $result = $conn->query($sql);
    $couponsCount = $result->num_rows;
} else if (isset($_GET["sort_by"])) {
    $sort_by = $_GET["sort_by"];
    $order_by = $_GET["order_by"];
    $sql = "SELECT * FROM coupons  WHERE valid=1 ORDER BY $sort_by $order_by";
    $result = $conn->query($sql);
    $couponsCount = $result->num_rows;
} else {
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
    } else {
        $page = 1;
    }
    $sqlAll = "SELECT * FROM coupons WHERE valid=1";
    $resultAll = $conn->query($sqlAll);
    $couponsCount = $resultAll->num_rows;

    $per_page = 5;
    $page_start = ($page - 1) * $per_page;
    $sql = "SELECT * FROM coupons WHERE valid=1 LIMIT $page_start,$per_page";
    $result = $conn->query($sql);
    $totalPage = ceil($couponsCount / $per_page);
}
$rows = $result->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="en">

<head>
    <title>Housetune後臺管理系統理系統</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.2.0-beta1 -->
    <link rel="stylesheet" href="../scss/all.css">
    <link rel="stylesheet" href="../css/coupon.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.2.0/css/all.css" crossorigin="anonymous">
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
            <h2>優惠券管理</h2>
        </div>
        <div class="container">
            <div class="py-2 row justify-content-center">
                <div class="col-auto"><a href="add-coupon.php" class="btn btn-info">新增優惠券</a></div>
                <div class="col-auto"><a href="reset-coupon.php" class="btn btn-info">更新優惠券</a></div>
                <div class="col-auto"><a href="coupons-invalid.php" class="btn btn-danger text-light">查看失效優惠券</a></div>
            </div>
            <div class="py-2">
                <form action="coupons.php" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="請輸入欲查詢之優惠券名稱或折扣" value="<?php if (isset($_GET["search"]) && $_GET["search"] != "") echo $search ?>">
                        <button class="btn btn-info" type="submit">搜尋</button>
                    </div>
                </form>
            </div>
            <div class="py-2">
                <div class="row align-items-center g-2">
                    <div class="col-auto">
                        <select class="form-select select1" name="select">
                            <option disabled selected value="">請選擇篩選方式</option>
                            <option value="1">折扣</option>
                            <option value="2">最低花費</option>
                            <option value="3">日期</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <div class="inputarea">
                            <form action="coupons.php" method="GET">
                                <div class="input input1 row g-2">
                                    <div class="col-auto">
                                        <input type="number" class="form-control" name="min" value="">
                                    </div>
                                    <div class="col-auto">~</div>
                                    <div class="col-auto">
                                        <input type="number" class="form-control" name="max" value="">
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-info" type="submit">篩選</button>
                                    </div>
                                </div>
                            </form>
                            <form action="coupons.php" method="GET">
                                <div class="input input2 row g-2">
                                    <div class="col-auto">
                                        <input type="number" class="form-control" name="expense_min" value="">
                                    </div>
                                    <div class="col-auto">~</div>
                                    <div class="col-auto">
                                        <input type="number" class="form-control" name="expense_max" value="">
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-info" type="submit">篩選</button>
                                    </div>
                                </div>
                            </form>
                            <form action="coupons.php" method="GET">
                                <div class="input input3 row g-2">
                                    <div class="col-auto">
                                        <input type="datetime-local" class="form-control" name="date_start" value="">
                                    </div>
                                    <div class="col-auto">~</div>
                                    <div class="col-auto">
                                        <input type="datetime-local" class="form-control" name="date_end" value="">
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-info" type="submit">篩選</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (isset($_GET["search"]) && $_GET["search"] != "") : ?>
                <div class="py-2">
                    <a href="coupons.php" class="btn btn-info">回優惠券列表</a>
                </div>
                <h3><?= $_GET["search"] ?> 的搜尋結果</h3>
            <?php endif ?>
            <?php if (isset($_GET["sort_by"])) : ?>
                <div class="py-2">
                    <a href="coupons.php" class="btn btn-info">回優惠券列表</a>
                </div>
            <?php endif ?>
            <?php if (isset($min) || isset($expense_min) || isset($date_start)) : ?>
                <div class="py-2">
                    <a href="coupons.php" class="btn btn-info">回優惠券列表</a>
                </div>
                <?php if (isset($min)) : ?>
                    <h3>折扣篩選結果</h3>
                <?php endif ?>
                <?php if (isset($expense_min)) : ?>
                    <h3>最低花費篩選結果</h3>
                <?php endif ?>
                <?php if (isset($date_start)) : ?>
                    <h3>日期篩選結果</h3>
                <?php endif ?>
            <?php endif ?>
            <div class="py-2">
                共 <?= $couponsCount ?> 張優惠券
            </div>
            <table class="table table-bordered table-hover">
                <thead class="table-active">
                    <tr>
                        <th>id</th>
                        <th>優惠券名稱</th>
                        <th>折扣</th>
                        <th>類型</th>
                        <th>最低花費</th>
                        <th>
                            <div class="row align-items-end">
                                <label for="" class="col-auto">開始日期</label>
                                <div class="col-auto">
                                    <select class="form-select select2" name="" id="">
                                        <option disabled selected>排序</option>
                                        <option value="startNear">由近到遠</option>
                                        <option value="startFar">由遠到近</option>
                                    </select>
                                </div>
                            </div>
                        </th>
                        <th>
                            <div class="row align-items-end">
                                <label for="" class="col-auto">結束日期</label>
                                <div class="col-auto">
                                    <select class="form-select select3" name="" id="">
                                        <option disabled selected>排序</option>
                                        <option value="endNear">由近到遠</option>
                                        <option value="endFar">由遠到近</option>
                                    </select>
                                </div>
                            </div>
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($couponsCount > 0) : ?>
                        <?php foreach ($rows as $row) : ?>
                            <tr>
                                <td><?= $row["id"] ?></td>
                                <td><?= $row["coupon_name"] ?></td>
                                <td><?= $row["discount"] ?></td>
                                <td><?= $row["type"] ?></td>
                                <td><?= $row["min_expense"] ?></td>
                                <td><?= $row["start_date"] ?></td>
                                <td><?= $row["end_date"] ?></td>
                                <td>
                                    <a href="edit-coupon.php?id=<?= $row["id"] ?>" class="btn btn-info">編輯</a>
                                    <a href="delete-coupon.php?id=<?= $row["id"] ?>" class="btn btn-danger text-light">下架</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
            <?php if (!isset($search) && !isset($min) && !isset($expense_min) && !isset($date_start) && !isset($_GET["sort_by"])) : ?>
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $totalPage; $i++) : ?>
                            <li class="page-item <?php if ($i == $page) echo "active" ?>">
                                <a class="page-link" href="coupons.php?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor ?>
                    </ul>
                </nav>
            <?php endif ?>
        </div>

    </main>



    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous">
    </script>
    <script>
        const options = document.querySelector(".select1");
        const inputs = document.querySelectorAll(".input");
        const selectText = document.querySelector("#selectText")
        const sortStart = document.querySelector(".select2")
        const sortEnd = document.querySelector(".select3")

        options.addEventListener("change", (event) => {
            hideAllInputs()
            switch (options.value) {
                case ("1"):
                    document.querySelector(".input1").classList.add("inputShow")
                    break
                case ("2"):
                    document.querySelector(".input2").classList.add("inputShow")
                    break
                case ("3"):
                    document.querySelector(".input3").classList.add("inputShow")
                    break
                default:
                    break
            }
        })

        function hideAllInputs() {
            for (input of inputs) {
                input.classList.remove("inputShow")
            }
        }

        sortStart.addEventListener("change", (event) => {
            switch (sortStart.value) {
                case ("startNear"):
                    window.location.assign("coupons.php?sort_by=start_date&order_by=ASC")
                    break;
                case ("startFar"):
                    window.location.assign("coupons.php?sort_by=start_date&order_by=DESC")
                    break;
                default:
                    break
            }
        })
        sortEnd.addEventListener("change", (event) => {
            switch (sortEnd.value) {
                case ("endNear"):
                    window.location.assign("coupons.php?sort_by=end_date&order_by=ASC")
                    break;
                case ("endFar"):
                    window.location.assign("coupons.php?sort_by=end_date&order_by=DESC")
                    break;
                default:
                    break
            }
        })
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