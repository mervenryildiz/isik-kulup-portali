<?php
require_once __DIR__ . "/session_control.php";
$get_id = (int)@$_GET['id'];
$query = $db->prepare("SELECT id,event_title, event_date, event_time,created_date,e_poster, e_detail, event_location, all_view FROM events WHERE id = ?");
$query->execute(array(@$get_id));
// SELECT sorgularında mutlaka fetch olmalı
$get = $query->fetch(PDO::FETCH_ASSOC);
$page_title = @$get['event_title'];

require_once __DIR__."/header.php";
$logo = "assets/images/no-image.jpg";
if(!empty(@$get['e_poster'])) {
    $logo = '/uploads/files/500-650/'.@$get['e_poster'];
}

?>


<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-12">
                <div class="club-intro-img">
                    <img src="<?php echo $logo; ?>" alt="<?php echo @$get['event_title']; ?>">
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="club-intro-box">
                    <h1><?php echo @$get['event_title']; ?></h1>

                    <div class="card">
                        <ul class="list-group list-group-flush event-info-list">
                            <li class="list-group-item"><i class="bi bi-calendar3"></i> <?php echo date("d.m.Y", strtotime(@$get['event_date'])); ?></li>
                            <li class="list-group-item"><i class="bi bi-clock"></i> <?php echo date("H:i", strtotime(@$get['event_time'])); ?></li>
                            <li class="list-group-item"><i class="bi bi-geo-alt"></i> <?php echo @$get['event_location']; ?></li>
                        </ul>
                    </div>

                    <div class="mt-5">
                        <?php echo @$get['e_detail']; ?>
                    </div>
                    <div class="mt-5">
                        <h6><strong>ETKİNLİĞİ DÜZENLEYEN</strong></h6>
                        <div class="event-club-list">
                        <?php
                            $query = $db->prepare("SELECT clubs.id, clubs.club_name FROM clubs LEFT JOIN clubs_events ON clubs.id = clubs_events.club_id WHERE clubs_events.event_id = ? ");
                            $query->execute(array($get_id));
                            while($club_row = $query->fetch(PDO::FETCH_ASSOC)) { ?>

                                <a href="/club-detail.php?id=<?php echo @$club_row['id']; ?>" class="btn btn-sm btn-secondary"><?php echo @$club_row['club_name']; ?></a>

                        <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<?php require_once __DIR__."/footer.php"; ?>

</body>
</html>
