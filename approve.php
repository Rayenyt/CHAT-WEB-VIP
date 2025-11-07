<?php
include 'config.php';

if(!isset($_GET['user'])) die("رابط غير صالح!");
$username = $_GET['user'];

$stmt = $db->prepare("SELECT * FROM users WHERE username=?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$user) die("المستخدم غير موجود!");

$stmt = $db->prepare("UPDATE users SET approved=1 WHERE username=?");
$stmt->execute([$username]);

send_telegram($admin_telegram_id, "تمت الموافقة على $username بنجاح!");
echo "<h2>تمت الموافقة على $username بنجاح!</h2>";
echo "<p>يمكن الآن للمستخدم تسجيل الدخول إلى الدردشة.</p>";
