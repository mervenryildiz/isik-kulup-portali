<?php
require_once __DIR__."/session_control.php";
require_once __DIR__."/../connection.php";

$get_id         = (int)@$_GET['id'];
$c_adi          = @$_POST['club_adi'];
$c_durum        = @$_POST['club_durum'];
$c_email        = @$_POST['club_email'];
$c_detaylar     = @$_POST['club_detaylar'];
$user_list      = @$_POST['user_list'];
$logo           = @$_FILES['logo']; // Logo dosyası $_FILES üzerinden alınır

$get_id = @$_GET['id'];
$query = $db->prepare("SELECT id, club_name, email, c_status, created_date, detail, logo FROM clubs WHERE id = ?");
$query->execute(array(@$get_id));
// SELECT sorgularında mutlaka fetch olmalı
$get = $query->fetch(PDO::FETCH_ASSOC);


require_once __DIR__."/assets/plugins/class-upload/vendor/autoload.php"; // Dosya yükleme sınıfı

if(empty($c_adi) || empty($c_email) ) {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Lütfen zorunlu alanları doldurunuz! (*)
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    die();
}
 
$handle = new \Verot\Upload\Upload($logo, 'tr_TR');

$image_original_name = NULL;
if ($handle->uploaded) { // Dosya yükleme işlemi başlıyorsa
    // Eski logoları sil
    if(!empty(@$get['logo'])) { 
        unlink("../uploads/files/600-600/".@$get['logo']);
        unlink("../uploads/files/300-300/".@$get['logo']);
        unlink("../uploads/files/200-200/".@$get['logo']);
    }
    // Dosya adını ve uzantısını al
    $file_extention = pathinfo($logo['name'], PATHINFO_EXTENSION);
    $file_name      = pathinfo($logo['name'], PATHINFO_FILENAME);
    // Yeni dosya adı oluştur
    $new_file_name = $file_name."-".RAND(100000,999999);

    // Dosya yükleme ve boyutlandırma
    $handle->file_new_name_body   = $new_file_name;
    $handle->image_resize         = true;
    $handle->image_x              = 600;
    $handle->image_y              = 600;
    $handle->allowed = array('image/*'); // sadece belirli türdeki dosya türüne izin verir
    $handle->image_ratio_crop = true;
    $handle->process('../uploads/files/600-600/');
    if ($handle->processed) {
        $image_original_name = $handle->file_dst_name;
    }

    $handle->file_new_name_body   = $new_file_name;
    $handle->image_resize         = true;
    $handle->image_x              = 300;
    $handle->image_y              = 300;
    $handle->allowed = array('image/*');
    $handle->image_ratio_crop = true;
    $handle->process('../uploads/files/300-300/');
    if ($handle->processed) {
        $image_original_name = $handle->file_dst_name; // dosya işleme başarılı olursa hedef dosya adı image_original_name değişkenine atanır ve dosyanın kaydedildiği yeni adı alır.
    }

    $handle->file_new_name_body   = $new_file_name;
    $handle->image_resize         = true;
    $handle->image_x              = 200;
    $handle->image_y              = 200;
    $handle->allowed = array('image/*');
    $handle->image_ratio_crop = true;
    $handle->process('../uploads/files/200-200/');
    if ($handle->processed) {
        $image_original_name = $handle->file_dst_name;
        $handle->clean(); // Geçici dosyaları temizle
    }
    // Veritabanında clubs tablosundaki logo bilgisini güncelle
    $query = $db->prepare("
        UPDATE clubs SET 
        logo=?
        WHERE id=?
    ");
    // Logo güncelleme sorgusunu çalıştır
    $update = $query->execute(array(@$image_original_name, @$get_id));
}
// Veritabanında clubs tablosundaki diğer kulüp bilgilerini güncelle
$query = $db->prepare("
        UPDATE clubs SET 
        club_name=?, 
        email=?,
        c_status=?,
        detail=?
        WHERE id=?
    ");
// Kulüp bilgilerini güncelleme sorgusunu çalıştır
$update = $query->execute(array(@$c_adi, @$c_email, @$c_durum, @$c_detaylar, @$get_id));


if ($update) { // Güncelleme başarılıysa
    // users_clubs tablosundaki mevcut kullanıcı-kulüp ilişkilerini sil
    $users_clear = $db->prepare("DELETE FROM users_clubs WHERE club_id=?");
    $users_clear->execute(array(@$get_id));

    if(is_array(@$user_list)) {
       foreach($user_list as $user_item) {
        // Yeni kullanıcı-kulüp ilişkisi ekle
           $user_insert = $db->prepare("INSERT INTO users_clubs SET user_id=?, club_id=?");
           $user_insert->execute(array(@$user_item, @$get_id));

           //Kulüp yöneticisi atandığında otomatik olarak kulüp üyesi olarak ta atamaktadır.
           $u_query = $db->prepare("SELECT id FROM clubs_users WHERE user_id=? && club_id=?");
           $u_query->execute(array(@$user_item, @$get_id));
           $u_count = $u_query->rowCount();
           if($u_count == 0) {
               $user_insert = $db->prepare("INSERT INTO clubs_users SET user_id=?, club_id=?, user_status=1");
               $user_insert->execute(array(@$user_item, @$get_id));
           }
       }
    }

    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Kayıt başarıyla gerçekleşti!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
// Belirli bir süre sonra sayfayı yenilemek için setTimeout fonksiyonu
    echo '
    <script>
    setTimeout(function() {
        location.reload(); // Sayfayı yenile
    }, 1000);
    </script>
    ';

} else {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Kayıt işlemi başarısız!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
}
?>
