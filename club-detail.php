<?php
require_once __DIR__ . "/session_control.php";
$get_id = (int)@$_GET['id'];
$query = $db->prepare("SELECT id, club_name, email, detail, logo FROM clubs WHERE id = ? && c_status=1");
$query->execute(array(@$get_id));
// SELECT sorgularında mutlaka fetch olmalı
$get = $query->fetch(PDO::FETCH_ASSOC);
$page_title = @$get['club_name']." - Kulüp Profili";

require_once __DIR__."/header.php";
$logo = "assets/images/no-image.jpg";
if(!empty(@$get['logo'])) {
    $logo = '/uploads/files/600-600/'.@$get['logo'];
}

?>


<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-12">
                <div class="club-intro-img">
                    <img src="<?php echo $logo; ?>" alt="<?php echo @$get['club_name']; ?>">
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-12">
                <div class="club-intro-box">
                    <h1><?php echo @$get['club_name']; ?></h1>
                    <p><strong>İletişim:</strong> <?php echo @$get['email']; ?></p>
                    <div>
                        <?php echo @$get['detail']; ?>
                    </div>
                    <div>
                        <?php
                        $sub_query = $db->prepare("SELECT * from clubs_users WHERE club_id=? && user_id=? LIMIT 1");
                        $sub_query->execute(array(@$get_id, @$login_user));
                        $sub_row = @$sub_query->fetch(PDO::FETCH_ASSOC);
                        $user_status = @$sub_row['user_status'];
                        $user_count = @$sub_query->rowCount();
                        if(@$user_status == 2) {
                        ?>
                            <h4><span class="badge text-bg-info">Başvuruldu</span></h4>
                        <?php
                        } elseif(@$user_count == 0) {
                        ?>
                            <button data-club-id="<?php echo @$get_id; ?>" class="btn btn-primary club_sign_btn">Kulübe Katıl</button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $event_alert = false;

        $where_state = "";
        if(empty($login_user)) {
            $where_state = " && events.all_view = 1";
        }

        $query = $db->prepare("
                SELECT 
                    events.id,
                    events.event_title, 
                    events.e_detail, 
                    events.e_poster, 
                    events.event_date, 
                    events.event_time, 
                    events.event_location 
                FROM events
                LEFT JOIN clubs_events ON events.id = clubs_events.event_id
                WHERE clubs_events.club_id = ? && events.event_date >= CURDATE() $where_state ORDER BY events.event_date ASC, events.event_time ASC
             ");
        $query->execute(array(@$get_id));
        $event_count = $query->rowCount();
        if($event_count > 0) {
            $event_alert = true;
        ?>
        <div class="row mt-5">
            <div class="col-12 text-center">
                <h2 class="sub-title">GÜNCEL ETKİNLİKLER</h2>
            </div>

            <div class="col-12">
                <div class="swiper main-event-slider">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
                        <?php
                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                        $event_title = $row['event_title'];
                        $e_detail = $row['e_detail'];
                        $e_poster = !empty($row['e_poster']) ? '/uploads/files/300-390/' . $row['e_poster'] : '/assets/images/no-image.jpg';
                        $event_date = $row['event_date'];
                        $event_time = $row['event_time'];
                        $event_location = $row['event_location'];
                        ?>
                        <div class="swiper-slide">

                            <div class="card mb-4 box-shadow event-item">
                                <a href="/event-detail.php?id=<?php echo @$row['id']; ?>">
                                    <img class="card-img-top" src="<?php echo htmlspecialchars($e_poster); ?>" alt="<?php echo $event_title; ?>">
                                </a>
                                <div class="card-body">
                                    <a href="/event-detail.php?id=<?php echo @$row['id']; ?>">
                                        <h4 class="card-title"><?php echo $event_title; ?></h4>
                                    </a>

                                    <div class="event-info">
                                <span>
                                    <i class="bi bi-calendar3"></i> <?php echo date("d.m.Y", strtotime($event_date)); ?>
                                </span>

                                        <span>
                                    <i class="bi bi-clock"></i> <?php echo date("H:i", strtotime($event_time)); ?>
                                </span>
                                    </div>
                                    <div class="event-location">
                                        <i class="bi bi-geo-alt"></i> <?php echo $event_location; ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <?php } ?>
                    </div>
                    <!-- If we need pagination -->
                    <div class="swiper-pagination"></div>

                    <!-- If we need navigation buttons -->
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php
        $query = $db->prepare("
                SELECT 
                    events.id,
                    events.event_title, 
                    events.e_detail, 
                    events.e_poster, 
                    events.event_date, 
                    events.event_time, 
                    events.event_location 
                FROM events
                LEFT JOIN clubs_events ON events.id = clubs_events.event_id
                WHERE clubs_events.club_id = ? && events.event_date < CURDATE() $where_state ORDER BY events.event_date ASC, events.event_time ASC
             ");
        $query->execute(array(@$get_id));
        $event_count = $query->rowCount();
        if($event_count > 0) {
            $event_alert = true;
        ?>
        <div class="row mt-5">
            <div class="col-12 text-center">
                <h2 class="sub-title">GEÇMİŞ ETKİNLİKLER</h2>
            </div>
            <div class="col-12">
                <div class="swiper old-event-slider">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
                        <?php
                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                            $event_title = $row['event_title'];
                            $e_detail = $row['e_detail'];
                            $e_poster = !empty($row['e_poster']) ? '/uploads/files/300-390/' . $row['e_poster'] : '/assets/images/no-image.jpg';
                            $event_date = $row['event_date'];
                            $event_time = $row['event_time'];
                            $event_location = $row['event_location'];
                            ?>
                            <div class="swiper-slide">

                                <div class="card mb-4 box-shadow event-item">
                                    <a href="/event-detail.php?id=<?php echo @$row['id']; ?>">
                                        <img class="card-img-top" src="<?php echo htmlspecialchars($e_poster); ?>" alt="<?php echo $event_title; ?>">
                                    </a>
                                    <div class="card-body">
                                        <a href="/event-detail.php?id=<?php echo @$row['id']; ?>">
                                            <h4 class="card-title"><?php echo $event_title; ?></h4>
                                        </a>

                                        <div class="event-info">
                                <span>
                                    <i class="bi bi-calendar3"></i> <?php echo date("d.m.Y", strtotime($event_date)); ?>
                                </span>

                                            <span>
                                    <i class="bi bi-clock"></i> <?php echo date("H:i", strtotime($event_time)); ?>
                                </span>
                                        </div>
                                        <div class="event-location">
                                            <i class="bi bi-geo-alt"></i> <?php echo $event_location; ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        <?php } ?>
                    </div>
                    <!-- If we need pagination -->
                    <div class="swiper-pagination"></div>

                    <!-- If we need navigation buttons -->
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
        </div>
        <?php } ?>


        <?php if(!$event_alert) { ?>
            <div class="row mt-5">
                <div class="col-12">
                    <div class="alert alert-warning" role="alert">
                        <strong><?php echo @$get['club_name']; ?></strong> için etkinlik bulunmamaktadır!
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</section>



<?php require_once __DIR__."/footer.php"; ?>

</body>
</html>
