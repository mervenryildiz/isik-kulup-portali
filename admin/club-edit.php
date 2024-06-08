<?php
require_once __DIR__."/session_control.php";
require_once __DIR__."/header.php";
require_once __DIR__."/sidebar.php";

$get_id = @$_GET['id'];
$query = $db->prepare("SELECT id, club_name, email, c_status, created_date, detail, logo FROM clubs WHERE id = ?");
$query->execute(array(@$get_id));
// SELECT sorgularında mutlaka fetch olmalı  
$get = $query->fetch(PDO::FETCH_ASSOC);


$logo = "assets/img/no-image.jpg";
if(!empty(@$get['logo'])) {
    $logo = '../uploads/files/200-200/'.@$get['logo'];
}

$c_status = @$get['c_status'];

$users_query = $db->prepare("SELECT user_id FROM users_clubs WHERE club_id=?");
$users_query->execute(array(@$get_id));

$users_list = [];
while($user_row = $users_query->fetch(PDO::FETCH_ASSOC)) {
    $users_list[] = $user_row['user_id'];
}

?>

 <main id="main" class="main">


 <div class="pagetitle">
  <h1>Kulüp Listesi</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Ana Sayfa</a></li>
      <li class="breadcrumb-item">Kulüpler</li>
      <li class="breadcrumb-item active">Kulüp Ekle</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
      <div class="row">
        <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Kulüp Ekle</h5>
              <!-- Multi Columns Form -->
              <form class="row g-3 dataform" action="club-edit-save.php?id=<?php echo @$get_id; ?>" method="post" enctype="multipart/form-data">
                  <div class="col-md-12">
                      <label>Logo: (600x600px)</label><br>
                      <!-- Mevcut logo görüntüsü -->
                      <img src="<?php echo $logo; ?>" width="160" height="160" class="profile-img" alt="Profile">
                      <!-- Yeni logo yükleme alanı -->
                      <input class="profile-img-input" type="file" name="logo"  accept="image/*" id="formFile">
                      <!-- Logo silme butonu -->
                      <div class="pt-2">
                          <a href="javascript:void;" class="btn btn-danger btn-sm image-remove-btn" data-id="<?php echo @$get_id; ?>" title="Logo sil"><i class="bi bi-trash"></i></a>
                      </div>
                  </div>

                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Kulüp Adı *</label>
                  <input type="text" required class="form-control" name="club_adi" value="<?php echo @$get['club_name']; ?>" id="inputName5">
                </div>
                
                <div class="col-md-6">
                  <label for="inputEmail5" required class="form-label">Eposta</label>
                  <input type="email" required class="form-control" id="inputEmail5" name="club_email" value="<?php echo @$get['email']; ?>">
                </div>

                  <div class="col-md-6">
                      <label for="inputName5" class="form-label">Durum</label>
                      <select id="inputState" class="form-select" name="club_durum">
                          <option value="1">Aktif</option>
                          <option value="0"<?php if($c_status == 0) { echo 'selected'; } ?>>Pasif</option>
                      </select>
                  </div>



                  <div class="col-md-12">
                      <label for="inputName5" class="form-label">Kulüp Yöneticisi</label>
                      <input type="text" class="form-control user-search-input mb-2" placeholder="Kullanıcı ara" name="user-search-input">
                      <div class="users-list-table">
                          <?php
                          // Kullanıcıları adlarına göre sıralı bir şekilde veritabanından al
                          $user_query = $db->prepare("SELECT id, user_name, user_surname, email FROM users ORDER BY user_name ASC, user_surname ASC");
                          $user_query->execute();
                          // Her bir kullanıcı için döngü
                          while($user_row = $user_query->fetch(PDO::FETCH_ASSOC)) {
                              ?>
                              <div class="form-check" data-name="<?php echo mb_strtolower(@$user_row['user_name']." ".@$user_row['user_surname']); ?>" data-email="<?php echo mb_strtolower(@$user_row['email']); ?>">
                                  <input class="form-check-input" type="checkbox" id="user_list<?php echo @$user_row['id']; ?>"
                                         <?php
                                         // Eğer kullanıcı daha önce seçilmişse işaretle
                                         if(in_array(@$user_row['id'], $users_list)) {
                                             echo "checked";
                                         }
                                         ?>
                                         value="<?php echo @$user_row['id']; ?>" name="user_list[]">
                                   <!-- Kullanıcının adı, soyadı ve e-postasını listele -->
                                  <label class="form-check-label" for="user_list<?php echo @$user_row['id']; ?>">
                                      <?php echo @$user_row['user_name']." ".@$user_row['user_surname']." (".@$user_row['email'].")"; ?>
                                  </label>
                              </div>
                          <?php } ?>

                      </div>
                  </div>

                <div class="col-12">
                  <label for="inputAddress5" class="form-label">Detaylar</label>
                  <textarea class="tinymce-editor" name="club_detaylar"> <?php echo @$get['detail']; ?> </textarea>
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

 </main>

 <?php
require_once __DIR__."/footer.php";
require_once __DIR__."/footer_script.php";
?>

</body>

</html>
