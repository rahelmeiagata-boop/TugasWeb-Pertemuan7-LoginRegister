<?php
session_start();

$error = '';
$success = '';

$message = $_GET['msg'] ?? '';

if ($message === 'registered') {
    $success = 'Registrasi berhasil. Silakan login.';
} elseif ($message === 'logged_out') {
    $success = 'Anda berhasil logout.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Email dan password wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } else {
        $file = 'users.json';
        $users = [];

        if (file_exists($file)) {
            $json = file_get_contents($file);
            $users = json_decode($json, true) ?? [];
        }

        $foundUser = null;

        foreach ($users as $user) {
            if (strtolower($user['email']) === strtolower($email)) {
                $foundUser = $user;
                break;
            }
        }

        if ($foundUser && password_verify($password, $foundUser['password'])) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = $foundUser['id'];
            $_SESSION['username'] = $foundUser['nama'];
            $_SESSION['email'] = $foundUser['email'];

            header('Location: dashboard.php');
            exit;
        }

        $error = 'Email atau password salah.';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Login Register</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

    <main class="auth-page">
        <section class="auth-card">

            <div class="auth-heading">
                <p class="eyebrow">SELAMAT DATANG</p>
                <h1>Masuk ke akun</h1>
                <p>
                    Gunakan email dan password yang sudah terdaftar.
                </p>
            </div>

            <?php if ($success): ?>
                <div class="message success">
                    <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="message error">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="auth-form">

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Masukkan email"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                    >
                </div>

                <button type="submit" class="button">
                    Login
                </button>

            </form>

            <p class="auth-switch">
                Belum punya akun?
                <a href="register.php">Daftar sekarang</a>
            </p>

        </section>
    </main>

</body>
</html>