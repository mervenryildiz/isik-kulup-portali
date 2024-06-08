<?php
require_once __DIR__ . "/session_control.php";
$page_title = "Etkinlikler";
require_once __DIR__."/header.php";
?>

<div class="title-box">
    <div class="container">
        <h1>Etkinlikler</h1>
    </div>
</div>
<section class="section">
    <div class="album py-5 bg-light">
        <div class="container">
            <div class="row">
                <?php
                $where_state = "";
                if(empty($login_user)) {
                    $where_state = " && events.all_view = 1";
                }
                $query = $db->prepare("SELECT 
                    events.id,
                    events.event_title, 
                    events.e_detail, 
                    events.e_poster, 
                    events.event_date, 
                    events.event_time, 
                    events.event_location 
                FROM events
                WHERE events.event_date >= CURDATE() $where_state ORDER BY events.event_date ASC, events.event_time ASC");
                $query->execute();

                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $event_title = $row['event_title'];
                    $e_detail = $row['e_detail'];
                    $e_poster = !empty($row['e_poster']) ? '../uploads/files/300-390/' . $row['e_poster'] : 'assets/images/no-image.jpg';
                    $event_date = $row['event_date'];
                    $event_time = $row['event_time'];
                    $event_location = $row['event_location'];
                    ?>
                    <div class="col-md-4">
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
        </div>
    </div>
</section>

<?php require_once __DIR__."/footer.php"; ?>

</body>
</html>
