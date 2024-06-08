<?php
require_once __DIR__ . "/session_control.php";
$q = @$_GET['q'];
$page_title = "Arama Sonuç";
require_once __DIR__."/header.php";
?>

<?php
$c_query = $db->prepare("SELECT id, club_name, logo FROM clubs WHERE c_status=1 && club_name LIKE '%$q%' ORDER BY club_name ASC"); // Veritabanından kulüp bilgilerini al
$c_query->execute();
$c_row_count = $c_query->rowCount();

$where_state = "";
if(empty($login_user)) {
    $where_state = " && events.all_view = 1";
}
$e_query = $db->prepare("SELECT 
                    events.id,
                    events.event_title, 
                    events.e_detail, 
                    events.e_poster, 
                    events.event_date, 
                    events.event_time, 
                    events.event_location 
                FROM events
                WHERE events.event_date >= CURDATE() && event_title LIKE '%$q%' $where_state ORDER BY events.event_date ASC, events.event_time ASC");
$e_query->execute();
$e_row_count = $e_query->rowCount();

$total_count = $c_row_count + $e_row_count;
?>

<div class="title-box">
    <div class="container">
        <h1>Arama Sonuç</h1>
        <p><?php echo @$total_count." sonuç bulundu"; ?></p>
    </div>
</div>


<main role="main">
    <div class="album py-5 bg-light">
        <div class="container">
            <ol class="list-group">
                <?php
                // Her bir kulüp bilgisi için döngü
                while($c_row = $c_query->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <li class="list-group-item d-flex justify-content-between align-items-start pt-4 pb-4">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold"><a href="/club-detail.php?id=<?php echo @$c_row['id']; ?>" target="_blank"><?php echo @$c_row['club_name']; ?></a></div>
                    </div>
                    <span class="badge text-bg-primary rounded-pill">Kulüp</span>
                </li>
                <?php } ?>

                <?php
                // Her bir kulüp bilgisi için döngü
                while($e_row = $e_query->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <li class="list-group-item d-flex justify-content-between align-items-start pt-4 pb-4">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold"><a href="/club-detail.php?id=<?php echo @$e_row['id']; ?>" target="_blank"><?php echo @$e_row['event_title']; ?></a></div>
                        </div>
                        <span class="badge text-bg-warning rounded-pill">Etkinlik</span>
                    </li>
                <?php } ?>
            </ol>
        </div>
    </div>
</main>

<?php require_once __DIR__."/footer.php"; ?>

</body>
</html>
