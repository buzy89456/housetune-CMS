<?php
session_start();
?>
<!doctype html>
<html lang="en">

<head>
    <title>Housetune後臺管理系統理系統</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.2.1 -->
    <link rel="stylesheet" href="./scss/all.css">
    <link rel="stylesheet" href="./css/login.css">
</head>

<body>
    <div class="vh-100 d-flex justify-content-center align-items-center">
        <div class="login-panel text-center">
            <img class="logo mb-3" src="https://hartley-bespoke.co.uk/wp-content/uploads/h.png" alt="">
            <h3>Housetune後臺管理系統</h3>
            <!-- 登入失敗超過五次 -->
            <?php if (isset($_SESSION["error"]) && $_SESSION["error"]["times"] >= 5) : ?>
                <div class="text-center h3">
                    您已超過登入錯誤次數，請稍後再登入!
                </div>
            <?php else : ?>
                <form action="doLogin.php" method="post">
                    <div class="text-start mt-3">
                        <div class="form-floating floating-top">
                            <input type="text" class="form-control" id="floatingInput" placeholder="account" name="account">
                            <label for="floatingInput">Account</label>
                        </div>
                        <div class="form-floating floating-bottom">
                            <input type="password" class="form-control" id="floatingPassword" placeholder="password" name="password">
                            <label for="floatingPassword">Password</label>
                        </div>
                        <!-- 登入錯誤 -->
                        <?php if (isset($_SESSION["error"])) : ?>
                            <div class="py-2 text-danger">
                                <?= $_SESSION["error"]["message"] ?>
                                <?php
                                $_SESSION["error"]["message"] = "";
                                ?>
                            </div>
                        <?php endif; ?>
                        <div class="py-3 d-grid mb-3  mt-3">
                            <button class="btn btn-primary" type="submit">登入</button>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
            <div class="mt-5 text-secondary">©housetune 2022–2023</div>
        </div>
    </div>
</body>

</html>