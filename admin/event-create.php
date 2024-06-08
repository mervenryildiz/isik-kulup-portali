<?php
require_once __DIR__."/session_control.php";
require_once __DIR__."/header.php";
require_once __DIR__."/sidebar.php";

$poster = "assets/img/no-image.jpg";

?>
<main id="main" class="main">
<div class="pagetitle">
<h1>Etkinlik Listesi</h1>
<nav>
    <ol class="breadcrumb">
    <li class="breadcrumb-item"> <a href="index.html">Ana Sayfa</a>   </li>
    <li class="breadcrumb-item"> <a href="index.html">Etkinlikler </a>  </li>
    <li class="breadcrumb-item"> <a href="index.html">Etkinlik Ekle </a>  </li>
    </ol>
</nav>

</div> <!-- End Page Title-->

<section class="section">
      <div class="row">
        <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Etkinlik Ekle</h5>
              <form class="row g-3 dataform" action="event-create-save.php" method="post" enctype="multipart/form-data">
                  <div class="col-md-12">
                      <label>Poster: (500x650px)</label><br>
                      <img src="<?php echo $poster; ?>" width="160" height="160" class="profile-img" alt="Profile">
                      <input class="profile-img-input" type="file" name="poster"  accept="image/*" id="formFile">
                      <div class="pt-2">
                          <a href="" class="btn btn-danger btn-sm image-remove-btn" data-id="0" title="Afiş sil"><i class="bi bi-trash"></i></a>
                      </div>
                  </div>

                  <div class="col-md-12">
                      <label for="inputName5" class="form-label">Etkinlik Başlığı *</label>
                      <input type="text" required class="form-control" name="event_title" id="inputName5">
                  </div>


                <div class="col-md-6">
                  <label for="inputDate5" required class="form-label">Etkinlik Tarihi </label>
                  <input type="date" class="form-control" id="inputDate5" name="event_date">
                </div>

                <div class="col-md-6">
                  <label for="inputTime5" required class="form-label">Etkinlik Saati </label>
                  <input type="time" class="form-control" id="inputTime5" name="event_time">
                </div>
                <div class="col-md-6">
                  <label for="inputLocation5" class="form-label">Etkinlik Konumu </label>
                  <input type="text" class="form-control" name="event_location" id="inputLocation5">
                </div>

                  <div class="col-md-6">
                      <label for="inputName5" class="form-label">Görüntülenme</label>
                      <select id="inputState" class="form-select" name="all_view">
                          <option value="1">Herkese Açık</option>
                          <option value="0">Sadece Kullanıcı</option>
                      </select>
                  </div>

                  <div class="col-md-12">
                      <label for="inputName5" class="form-label">Kulüp Seç</label>
                      <input type="text" class="form-control user-search-input mb-2" placeholder="Kulüp ara" name="user-search-input">
                      <div class="users-list-table">
                          <?php
                          // Kulüpleri adlarına göre sıralı bir şekilde veritabanından al
                          $club_query = $db->prepare("SELECT id, club_name, email FROM clubs WHERE c_status=1 ORDER BY club_name ASC");
                          $club_query->execute();
                          // Her bir kulüp için döngü
                          while($club_row = $club_query->fetch(PDO::FETCH_ASSOC)) {
                              ?>
                              <div class="form-check" data-name="<?php echo mb_strtolower(@$club_row['club_name']); ?>" data-email="<?php echo mb_strtolower(@$club_row['email']); ?>">
                                  <input class="form-check-input" type="checkbox" id="club_list<?php echo @$club_row['id']; ?>"
                                         value="<?php echo @$club_row['id']; ?>" name="club_list[]">
                                  <!-- listele -->
                                  <label class="form-check-label" for="club_list<?php echo @$club_row['id']; ?>">
                                      <?php echo @$club_row['club_name']." (".@$club_row['email'].")"; ?>
                                  </label>
                              </div>
                          <?php } ?>

                      </div>
                  </div>


            
                <div class="col-12">
                  <label for="inputAddress5" class="form-label">Etkinlik Detayları</label>
                  <textarea class="tinymce-editor" name="event_detail"></textarea>
                </div>

                <div class="col-12" id="formresult">
                  
                  </div>
                  
                  <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg formsubmitbutton"name="kaydet">Kaydet</button>
                  </div>
                </form><!-- End Multi Columns Form -->
                </div>
             </div>
         </div>
      </div>
</section>




</main><!-- End Main-->

<?php
require_once __DIR__."/footer.php";
require_once __DIR__."/footer_script.php";
?>

</body>
</html>