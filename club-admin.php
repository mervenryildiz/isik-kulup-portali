<?php
require_once __DIR__ . "/session_control.php";
if(@$club_user_count == 0) {
    header("Location: /error.php");
    die();
}

$page_title = "Kulüp Yönetimi";
require_once __DIR__."/header.php";
?>


<div class="title-box">
    <div class="container">
        <h1>Kulüp Yönetimi</h1>
    </div>
</div>


<main role="main">
    <div class="album py-5 bg-light">
        <div class="container">

            <?php
            $query = $db->prepare("SELECT clubs.id, clubs.club_name, clubs.logo, clubs.email 
            FROM clubs 
            LEFT JOIN users_clubs ON users_clubs.club_id = clubs.id
            WHERE c_status=1  && users_clubs.user_id = ?
            ORDER BY club_name ASC"); // Veritabanından kulüp bilgilerini al
            $query->execute(array(@$login_user));
            $club_count = $query->rowCount();
            if($club_count > 0) {
            ?>

            <div class="row">
                <?php


                // Her bir kulüp bilgisi için döngü
                while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    // Varsayılan logo dosya yolu
                    $logo = !empty($row['logo']) ? '../uploads/files/300-300/'.$row['logo'] : 'assets/images/no-image.jpg';
                    $club_name = @$row['club_name'];
                    ?>
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="row g-0">
                                <div class="col-3">
                                    <a href="/club-admin-detail.php?id=<?php echo @$row['id']; ?>" class="mb-4">
                                        <img class="img-fluid rounded-start" src="<?php echo htmlspecialchars($logo); ?>" alt="Club Logo">
                                    </a>

                                </div>
                                <div class="col-9">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $club_name; ?></h5>
                                        <p class="card-text">E: <?php echo @$row['email']; ?></p>
                                        <a href="/club-admin-detail.php?id=<?php echo @$row['id']; ?>" class="btn btn-primary">Kulüp Yönet</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php } ?>
            </div>
            <?php } else { ?>
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning" role="alert">
                            Yöneticisi olduğunuz kulüp bulunmamaktadır!
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</main>

<?php require_once __DIR__."/footer.php"; ?>
</body>
</html>
