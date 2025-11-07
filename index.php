<?php
session_start();
include 'config.php';

$msg = '';

// HANDLE REGISTRATION
if(isset($_POST['register'])){
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    try{
        $stmt = $db->prepare("INSERT INTO users(username,password) VALUES (?,?)");
        $stmt->execute([$username,$password]);
        send_telegram($admin_telegram_id, "طلب جديد: $username يريد الدخول. الموافقة هنا: http://yourdomain.com/approve.php?user=$username");
        $msg = "تم إرسال طلبك للأدمن. انتظر الموافقة.";
    }catch(Exception $e){
        $msg = "اسم المستخدم موجود بالفعل!";
    }
}

// HANDLE LOGIN
if(isset($_POST['login'])){
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $stmt = $db->prepare("SELECT * FROM users WHERE username=?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($user && password_verify($password,$user['password'])){
        if($user['approved']==1){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: ?chat=1");
            exit;
        }else{
            $msg = "لم تتم الموافقة بعد من الأدمن.";
        }
    }else{
        $msg = "بيانات الدخول غير صحيحة!";
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CHAT WEB VIP</title>
<link rel="stylesheet" href="style.css">
<script src="script.js"></script>
</head>
<body>
<div class="container">
<h2>CHAT WEB VIP</h2>
<?php if($msg){echo "<p class='msg'>$msg</p>";} ?>

<?php if(!isset($_GET['chat'])): ?>
<form method="POST">
<input type="text" name="username" placeholder="اسم المستخدم" required>
<input type="password" name="password" placeholder="كلمة المرور" required>
<button type="submit" name="login">تسجيل الدخول</button>
<button type="submit" name="register">إنشاء حساب</button>
</form>
<?php else: ?>
<!-- Chat UI -->
<div class="chat-box" id="chat-box"></div>
<form id="chat-form">
<input type="text" id="chat-input" placeholder="اكتب رسالتك..." autocomplete="off">
<button type="submit">إرسال</button>
</form>
<script>
const userId = <?php echo $_SESSION['user_id']; ?>;
</script>
<?php endif; ?>
</div>
</body>
</html>
