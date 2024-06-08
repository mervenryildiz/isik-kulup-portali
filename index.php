<?php
require_once __DIR__."/header.php";
?>

<div class="intro">
    <div class="intro-content">
        <div>
            <h1>Işık Üniversitesi<br>Kulüp Portalına Hoşgeldiniz!</h1>
            <p>Işık Üniversitesi Kulüp Portalı ile kulüplerimizin etkinlikleri ve çalışma alanları hakkında bilgiler alabilirsiniz....</p>
        </div>
    </div>
</div>

<!-- Albüm başlangıç-->
<main role="main">
<div class="album py-5 bg-light">
  <div class="container">
      <h2 class="mb-5 text-center">Kulüplerimiz</h2>
      <div class="row">
          <?php
          $query = $db->prepare("SELECT id, club_name, logo FROM clubs  WHERE c_status=1 ORDER BY club_name ASC LIMIT 12"); // Veritabanından kulüp bilgilerini al
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
      <div class="row mt-5">
          <div class="col-12 align-center">
              <a href="/clubs.php" class="btn btn-light btn-lg">Tüm Kulüpler</a>
          </div>
      </div>
  </div>
</div>

    <?php
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
                WHERE events.event_date >= CURDATE() $where_state ORDER BY events.event_date ASC, events.event_time ASC LIMIT 10
             ");
    $query->execute();
    $event_count = $query->rowCount();
    if($event_count > 0) {
    ?>
    <div class="container">
        <div class="row mt-5">
            <div class="col-12 text-center">
                <h2 class="sub-title">YAKLAŞAN ETKİNLİKLER</h2>
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
    </div>
    <?php } ?>

</main>

<?php require_once __DIR__."/footer.php"; ?>

</body>
</html>