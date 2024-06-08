<?php
require_once __DIR__."/session_control.php";
require_once __DIR__."/header.php";
require_once __DIR__."/sidebar.php";
?>

<main id="main" class="main">

<div class="pagetitle">
  <h1>Kullanıcı Listesi</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Ana Sayfa</a></li>
      <li class="breadcrumb-item">Kullanıcılar</li>
      <li class="breadcrumb-item active">Kullanıcı Ekle</li>
    </ol>
  </nav>
</div><!-- End Page Title -->


<section class="section">
      <div class="row">
        <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Kullanıcı Ekle</h5>
              <!-- Multi Columns Form -->
              <form class="row g-3 dataform" action="user-create-save.php" method="post" enctype="multipart/form-data">
                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Kullanıcı Adı *</label>
                  <input type="text" required class="form-control" name="user_adi" id="inputName5">
                </div>

                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Kullanıcı Soyadı *</label>
                  <input type="text" required class="form-control" name="user_soyadi" id="inputName5">
                </div>

                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Durum</label>
                  <select id="inputState" class="form-select" name="user_durum">
                    <option value="1">Aktif</option>
                    <option value="0">Pasif</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Yetki</label>
                  <select id="inputState" class="form-select" name="user_yetki">
                  <?php
                      $query = $db->prepare("SELECT id,rolename FROM roles ORDER BY id ASC");
                      $query->execute();
                        
                      while($row = $query->fetch(PDO::FETCH_ASSOC)) {

                    ?>
                  
                  <option value="<?php echo @$row['id']; ?>"><?php echo @$row['rolename']; ?></option>
                  <?php } ?>
                    
                  </select>
                </div>

                <div class="col-md-6">
                  <label for="inputEmail5" required class="form-label">Eposta *</label>
                  <input type="email" class="form-control" id="inputEmail5" name="user_email">
                </div>
                <div class="col-md-6">
                  <label for="inputPassword5" required class="form-label">Şifre *</label>
                  <input type="password" class="form-control" id="inputPassword5" name="user_password">
                </div>
                <div class="col-12">
                  <label for="inputAddress5" class="form-label">Detaylar</label>
                  <textarea class="tinymce-editor" name="user_detaylar"></textarea>
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

</main><!-- End #main -->

  <?php
require_once __DIR__."/footer.php";
require_once __DIR__."/footer_script.php";
?>

</body>

</html>
