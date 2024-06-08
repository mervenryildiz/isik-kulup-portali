<?php
require_once __DIR__ . "/session_control.php";
if(empty(@$login_user) || @$login_user < 1) {
    header("Location: /error.php");
    die();
}
$page_title = "Kulüplerim";
require_once __DIR__."/header.php";
?>


<div class="title-box">
    <div class="container">
        <h1>Kulüplerim</h1>
    </div>
</div>


<main role="main">
    <div class="album py-5 bg-light">
        <div class="container">
            <div class="row">
                <?php
                $query = $db->prepare("
                SELECT 
                    clubs.id,
                    clubs.club_name, 
                    clubs.email, 
                    clubs.logo,
                    clubs_users.user_status
                    FROM clubs
                    LEFT JOIN clubs_users ON clubs.id = clubs_users.club_id
                    WHERE clubs_users.user_id =? ORDER BY clubs_users.user_status DESC, clubs.club_name ASC
             ");
                $query->execute(array(@$login_user));


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
                            <div class="card-body text-center">
                                <a href="/club-detail.php?id=<?php echo @$row['id']; ?>">
                                    <h5 class="card-text text-center"><?php echo $club_name; ?></h5>
                                </a>

                                <?php if(@$row['user_status'] == 2) {
                                    echo "<span class='badge text-bg-warning'>Onay Bekliyor</span>";
                                } ?>
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
