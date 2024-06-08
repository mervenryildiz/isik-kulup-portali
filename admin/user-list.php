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
      <li class="breadcrumb-item active">Kullanıcı Listesi</li>
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
                <th>
                  Ad Soyad
                </th>
                <th>Email</th>
                <th>Yetki</th>
                <th>Durum</th>
                <th data-type="date" data-format="dd.mm.YYYY H:i">Kayıt Tarihi</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php
                $query = $db->prepare("SELECT id,user_name,user_surname,email,role_id,s_status,created_date FROM users");
                $query->execute();
                  
                while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  
                  $s_status = $row['s_status'];
                  $role_id = $row['role_id'];

              ?>
                <tr>
                  <td><?php echo @$row['user_name']." ".@$row['user_surname']; ?></td>
                  <td><?php echo @$row['email']; ?></td>
                  <td>
                    <?php 
                    $query_role = $db->prepare("SELECT rolename FROM roles WHERE id=?");
                    $query_role->execute(array(@$role_id));
                    $role_get = $query_role->fetch(PDO::FETCH_ASSOC);
                    $role_name = @$role_get['rolename'];
                    
                    echo $role_name;
                    ?>
                  
                  </td>
                  
                  <td><?php 
                    if ($s_status == '1') {
                      echo "Aktif";
                    } elseif($s_status == '2') {
                      echo "Başvuru";
                    } else {
                      echo "Pasif";
                    }
                
                ?></td>
                  <td><?php echo date("d.m.Y H:i", strtotime(@$row['created_date'])); ?></td>
                  <td>
                    <a href="user-edit.php?id=<?php echo @$row['id']; ?>">Düzenle</a> - 
                    <a href="user-delete.php?id=<?php echo @$row['id']; ?>">Sil</a>
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
