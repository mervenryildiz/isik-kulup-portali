<?php
require_once __DIR__."/session_control.php";
require_once __DIR__."/header.php";
require_once __DIR__."/sidebar.php";
?>

<main id="main" class="main">
<div class="pagetitle">
  <h1>Kulüp Listesi</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Ana Sayfa</a></li>
      <li class="breadcrumb-item">Kulüpler</li>
      <li class="breadcrumb-item active">Kulüp Listesi</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <!-- Table with stripped rows -->
          <table class="table datatable">
            <thead>
              <tr>
                  <th></th>
                <th>
                  Kulüp Adı
                </th>
                <th>Email</th>
                <th>Durum</th>
                <th data-type="date" data-format="dd.mm.YYYY H:i">Kayıt Tarihi</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php
                $query = $db->prepare("SELECT id,club_name,email,c_status,created_date,logo FROM clubs"); // Veritabanından kulüp bilgilerini al
                $query->execute();

                // Her bir kulüp bilgisi için döngü  
                while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  // Kulübün durumunu al
                  $c_status = $row['c_status'];
                  // Varsayılan logo dosya yolu
                    $logo = "assets/img/no-image.jpg"; 
                  // Eğer kulübün bir logosu varsa, logo dosya yolunu güncelle
                    if(!empty(@$row['logo'])) {
                        $logo = '../uploads/files/200-200/'.@$row['logo'];
                    }

              ?>
              <tr>
                
                  <td><img src="<?php echo $logo; ?>" class="border-radius" width="40" height="40" /></td><!-- Kulübün logosunu göster -->
                  <td><?php echo @$row['club_name']; ?></td><!-- Kulübün adını göster -->
                  <td><?php echo @$row['email']; ?></td><!-- Kulübün mailini göster -->
                  
                  <td><?php 
                    if ($c_status == '1') {
                      echo "Aktif";
                    } elseif($c_status == '2') {
                      echo "Başvuru";
                    } else {
                      echo "Pasif";
                    } // Kulübün durumunu göster
               
                ?></td>
                <!-- Kulübün oluşturulma tarihini göster -->
                  <td><?php echo date("d.m.Y H:i", strtotime(@$row['created_date'])); ?></td>
                  <!-- Düzenle ve Sil bağlantıları -->
                  <td>
                    <a href="club-edit.php?id=<?php echo @$row['id']; ?>">Düzenle</a> - 
                    <a href="club-delete.php?id=<?php echo @$row['id']; ?>">Sil</a>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>

          <!-- End Table with stripped rows -->

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
