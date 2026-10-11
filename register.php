<?php
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($nama) || empty($email) || empty($password)) {
        $error = 'Semua field wajib diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } else {
        $nama = htmlspecialchars($nama, ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

        $file = 'users.json';
        $users = [];

        if (file_exists($file)) {
            $json = file_get_contents($file);
            $users = json_decode($json, true) ?? [];
        }

        foreach ($users as $user) {
            if (strtolower($user['email']) === strtolower($email)) {
                $error = 'Email sudah terdaftar!';
                break;
            }
        }

        if (empty($error)) {
            $newUser = [
                'id' => empty($users) ? 1 : max(array_column($users, 'id')) + 1,
                'nama' => $nama,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s')
            ];

            $users[] = $newUser;

            file_put_contents(
                $file,
                json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                LOCK_EX
            );

            header('Location: login.php?msg=registered');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Login Register</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

    <main class="auth-page">
        <section class="auth-card">

            <div class="auth-heading">
                <p class="eyebrow">BUAT AKUN</p>
                <h1>Daftar sekarang.</h1>
                <p>
                    Buat akun untuk mulai menggunakan sistem.
                </p>
            </div>

            <?php if ($error): ?>
                <div class="message error">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="auth-form">

                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        placeholder="Masukkan nama lengkap"
                        required
                    >
                </div>

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
                        placeholder="Minimal 6 karakter"
                        required
                    >
                </div>

                <button type="submit" class="button">
                    Daftar
                </button>

            </form>

            <p class="auth-switch">
                Sudah punya akun?
                <a href="login.php">Login di sini</a>
            </p>

        </section>
    </main>

</body>
</html>