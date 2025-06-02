<?php
session_start();
require_once "config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Şifreyi hash'le, sonra karşılaştır
    if ($user && hash('sha256', $password) === $user['password']) {
         $_SESSION['admin_logged_in'] = true; 
        header("Location: dashboard.php");

        
        exit;
    } else {
        $error = "❌ Kullanıcı adı veya şifre hatalı.";
    }
}
?>

<!-- Basit Bootstrap giriş formu -->
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
<div class="card p-4 shadow" style="min-width: 300px;">
    <h3 class="mb-3 text-center">Yönetici Girişi</h3>
    <form method="POST">
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Kullanıcı adı" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Şifre" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
        <?php if (!empty($error)) echo "<div class='mt-2 text-danger text-center'>$error</div>"; ?>
    </form>
</div>
<?php include 'footer.php'; ?>

</body>
</html>
