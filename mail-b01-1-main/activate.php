<?php
require 'db.php';
$token = trim($_GET['token'] ?? '');
if (!$token) {
    die('無效的啟用連結。');
}

$stmt = $conn->prepare('SELECT id FROM musers WHERE token = ? AND is_active = 0');
$stmt->bind_param('s', $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    die('連結無效或帳號已啟用。');
}

$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

$update_stmt = $conn->prepare('UPDATE musers SET is_active = 1, token = NULL WHERE id = ?');
$update_stmt->bind_param('i', $user_id);
$update_stmt->execute();
$update_stmt->close();  
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>帳號已啟用</title>
</head>
<body>
<h2>帳號啟用成功！</h2>
<p>您的帳號已開通，現在可以登入了。</p>
<p><a href="login.php">前往登入</a></p>
</body>
</html>
