<?php
session_start();

// 判斷使用者是否已經登入
$is_logged_in = isset($_SESSION['user_id']);

// 如果有登入，取得使用者的姓名 (從之前 login_process.php 存入的 session)
$user_name = $is_logged_in ? ($_SESSION['name'] ?? '會員') : '';
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>系統首頁</title>
</head>
<body>

    <div class="container">
        <h1>會員登入系統</h1>

        
        <?php if ($is_logged_in): ?>
            <p>你好，<strong><?= htmlspecialchars($user_name) ?></strong>！<br>很高興再次見到你。</p>
            <div class="btn-group">
                <a href="profile.php" class="btn btn-primary">進入個人資料</a>
                <a href="logout.php" class="btn btn-outline">登出系統</a>
            </div>

        <?php else: ?>
            <p>請選擇登入或註冊新帳號，以體驗完整功能。</p>
            <div class="btn-group">
                <a href="login.php" class="btn btn-primary">會員登入</a>
                <br>
                <a href="register.php" class="btn btn-secondary">註冊新帳號</a>
            </div>
        <?php endif; ?>
        
    </div>

</body>
</html>