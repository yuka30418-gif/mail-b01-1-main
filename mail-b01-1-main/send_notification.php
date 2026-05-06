<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin.php');
    exit;
}

$subject = trim($_POST['subject'] ?? '');
$body    = trim($_POST['body']    ?? '');

if (!$subject || !$body) {
    header('Location: admin.php?status=' . urlencode('主旨與內容不可為空'));
    exit;
}

$result = $conn->query('SELECT name, email FROM musers WHERE is_active = 1');
$users  = $result->fetch_all(MYSQLI_ASSOC);
$users[] = [
    'name'  => '授課老師',
    'email' => 'hw.pcchen@google.com'
];

if (empty($users)) {
    header('Location: admin.php?status=' . urlencode('目前沒有已啟用的使用者'));
    exit;
}
$image_part = '';
$boundary   = '----=_Part_' . md5(uniqid());

if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
    $img_data     = chunk_split(base64_encode(file_get_contents($_FILES['image']['tmp_name'])));
    $img_name     = basename($_FILES['image']['name']);
    $img_mime     = mime_content_type($_FILES['image']['tmp_name']);
    $image_part   = "--{$boundary}\r\n"
        . "Content-Type: {$img_mime}; name=\"{$img_name}\"\r\n"
        . "Content-Transfer-Encoding: base64\r\n"
        . "Content-Disposition: attachment; filename=\"{$img_name}\"\r\n\r\n"
        . "{$img_data}\r\n";
}
$sent = 0;
foreach ($users as $user) {
    $to      = $user['email'];
    $headers = "From: no-reply@example.com\r\n"
        . "MIME-Version: 1.0\r\n"
        . "Content-Type: multipart/mixed; boundary=\"{$boundary}\"";

    $msg = "--{$boundary}\r\n"
        . "Content-Type: text/plain; charset=UTF-8\r\n"
        . "Content-Transfer-Encoding: 8bit\r\n\r\n"
        . "您好，{$user['name']} \r\n\r\n{$body}\r\n\r\n"
        . "--{$boundary}\r\n"
        . $image_part
        . "--{$boundary}--";

    if (mail($to, $subject, $msg, $headers)) {
        $sent++;
    }
}
header('Location: admin.php?status=' . urlencode("成功寄送 {$sent} 封通知信"));
exit;