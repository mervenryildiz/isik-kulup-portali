<?php
require_once __DIR__ . "/session_control.php";
if(empty($page_title)) {
    @$page_title = "Işık Üniversitesi - Kulüp Portalı";
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo @$page_title; ?></title>
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="assets/img/Isik-uni-logo.png" rel="icon" href="index.php">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/swiper@11.1.4/swiper-bundle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.11.1/dist/sweetalert2.min.css">

    <link rel="stylesheet" type="text/css" href="/assets/css/isik_style.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anasayfa</title>
</head>
<body>

<!-- Menü Kısmı Başlangıç -->

<header id="header" class="header">
    <nav class="navbar navbar-expand-lg">
        <div class="container-xxl">
            <a class="navbar-brand" href="/">
                <img src="https://www.isikun.edu.tr/assets/images/logo.svg" alt="Işık Üniversitesi" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Anasayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="clubs.php">Kulüpler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="hakkimizda.php">Hakkımızda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="events.php">Etkinlikler</a>
                    </li>
                </ul>
                <form class="d-flex" action="/search-result.php" method="get" enctype="multipart/form-data">
                    <input class="form-control me-2" type="search" name="q" placeholder="Kulüp, etkinlik v.s." aria-label="Search">
                    <button class="btn btn-light" type="submit"><i class="bi bi-search"></i></button>
                </form>

                <?php
                if(@$user_count == 0) {
                ?>
                <a href="/login.php" class="btn btn-outline-info" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Kaydol / Giriş Yap"><i class="bi bi-person-fill"></i></a>
                <?php } else { ?>
                    <a href="/user-announcements.php" class="btn btn-light announ-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Duyurular">
                        <i class="bi bi-bell"></i>

                        <?php
                        $alert_query = $db->prepare("SELECT
                announcements.id
            FROM
                announcements
                LEFT JOIN
                clubs_announcements
                ON 
                    announcements.id = clubs_announcements.announcement_id
                LEFT JOIN
                clubs
                ON 
                    clubs_announcements.club_id = clubs.id
                LEFT JOIN
                clubs_users
                ON 
                    clubs.id = clubs_users.club_id
                    WHERE clubs_users.user_id = ? && clubs_users.user_status = 1 && (SELECT COUNT(announcements_users.id) FROM announcements_users WHERE announcements_users.user_id=? && announcements_users.announcement_id = announcements.id LIMIT 1) = 0 LIMIT 1");
                        $alert_query->execute([$login_user, $login_user]);
                        $alert_count  = $alert_query->rowCount();
                        if($alert_count > 0) {
                            ?>

                            <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                        <span class="visually-hidden">New alerts</span>
                      </span>
                        <?php } ?>
                    </a>

                    <div class="dropdown btn btn-light">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $userget_name." ".@$userget_surname; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="user-clubs.php">Kulüplerim</a></li>
                            <li><a class="dropdown-item" href="/user-profile.php">Profilim</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if(@$club_user_count > 0) { ?>
                                <li><a class="dropdown-item" href="/club-admin.php">Kulüp Yönetimi</a></li>
                            <?php } ?>
                            <?php
                            if($userget_role_id == 3) {
                            ?>
                            <li><a class="dropdown-item" href="/admin/">Yönetim Paneli</a></li>
                            <?php } ?>
                            <li><a class="dropdown-item" href="/logout.php">Çıkış Yap</a></li>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </div>
    </nav>
    <!-- Menü Kısmı Bitiş -->
</header>