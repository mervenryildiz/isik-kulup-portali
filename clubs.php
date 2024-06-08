<?php
require_once __DIR__ . "/session_control.php";
$page_title = "Kulüpler";
require_once __DIR__."/header.php";
?>


<div class="title-box">
    <div class="container">
        <h1>Kulüplerimiz</h1>
    </div>
</div>


<main role="main">
    <div class="album py-5 bg-light">
        <div class="container">
            <div class="row">
                <?php
                $query = $db->prepare("SELECT id, club_name, logo FROM clubs WHERE c_status=1 ORDER BY club_name ASC"); // Veritabanından kulüp bilgilerini al
                $query->execute();

                // Her bir kulüp bilgisi için döngü
                while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    // Varsayılan logo dosya yolu
                    $logo = !empty($row['logo']) ? '../uploads/files/300-300/'.$row['logo'] : 'assets/images/no-image.jpg';
                    $club_name = @$row['club_name'];
                    ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                        <div class="card mb-4 box-shadow club-item">
                            <div class="club-image">
                                <a href="/club-detail.php?id=<?php echo @$row['id']; ?>" class="mb-4">
                                    <img class="card-img-top" src="<?php echo htmlspecialchars($logo); ?>" alt="Club Logo">
                                </a>
                            </div>
                            <div class="card-body">
                                <a href="/club-detail.php?id=<?php echo @$row['id']; ?>">
                                    <h5 class="card-text text-center"><?php echo $club_name; ?></h5>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__."/footer.php"; ?>

</body>
</html>
