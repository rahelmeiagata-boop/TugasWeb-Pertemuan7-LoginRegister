<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$nama = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Login Register</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

    <main class="dashboard-page">

        <section class="dashboard-card">

            <div class="dashboard-top">

                <div>
                    <p class="eyebrow">DASHBOARD</p>

                    <h1>
                        Selamat datang, <?= $nama ?>!
                    </h1>

                    <p class="dashboard-subtitle">
                        Anda berhasil login. Berikut informasi akun yang sedang digunakan.
                    </p>
                </div>

                <a href="logout.php" class="button button-outline">
                    Logout
                </a>

            </div>

            <div class="profile-grid">

                <div class="info-card">
                    <span class="info-label">Nama Lengkap</span>
                    <strong><?= $nama ?></strong>
                </div>

                <div class="info-card">
                    <span class="info-label">Email</span>
                    <strong><?= $email ?></strong>
                </div>

                <div class="info-card">
                    <span class="info-label">Status</span>
                    <strong class="status">● Aktif</strong>
                </div>

            </div>

        </section>

    </main>

</body>

</html>