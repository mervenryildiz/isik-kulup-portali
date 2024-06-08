<?php
require_once __DIR__."/session_control.php";
require_once __DIR__."/../connection.php";

$get_id     = (int)@$_GET['id'];
$e_adi      = @$_POST['event_title'];
$e_detail   = @$_POST['event_detail'];
$poster     = @$_FILES['poster'];
$e_date     = @$_POST['event_date'];
$e_time     = @$_POST['event_time'];
$e_location = @$_POST['event_location'];
$club_list      = @$_POST['club_list'];
$all_view       = (int)@$_POST['all_view'];

$get_id = @$_GET['id'];
$query = $db->prepare("SELECT id, event_title, e_detail, e_poster, event_date, event_time, event_location,created_date FROM events WHERE id = ?");
$query->execute(array(@$get_id));
// SELECT sorgularında mutlaka fetch olmalı
$get = $query->fetch(PDO::FETCH_ASSOC);

require_once __DIR__."/assets/plugins/class-upload/vendor/autoload.php"; // Dosya yükleme sınıfı

if(empty($e_adi)){
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Lütfen zorunlu alanları doldurunuz! (*)
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    die();
}
if(empty($club_list) || count($club_list) == 0){
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Lütfen etkinliğin ait olduğu kulübü seçiniz! (*)
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    die();
}

$handle = new \Verot\Upload\Upload($poster, 'tr_TR');
$image_original_name = NULL;
if ($handle->uploaded) {
    // Eski logoları sil
    if(!empty(@$get['e_poster'])) {
        unlink("../uploads/files/500-650/".@$get['e_poster']);
        unlink("../uploads/files/300-390/".@$get['e_poster']);
        unlink("../uploads/files/200-200/".@$get['e_poster']);
    }


    $file_extention = pathinfo($poster['name'], PATHINFO_EXTENSION);
    $file_name      = pathinfo($poster['name'], PATHINFO_FILENAME);
    $new_file_name = $file_name."-".RAND(100000,999999);

    $handle->file_new_name_body   = $new_file_name;
    $handle->image_resize         = true;
    $handle->image_x              = 500;
    $handle->image_y              = 650;
    $handle->allowed = array('image/*');
    $handle->image_ratio_crop = true;
    $handle->process('../uploads/files/500-650/');
    if ($handle->processed) {
        $image_original_name = $handle->file_dst_name;
    }

    $handle->file_new_name_body   = $new_file_name;
    $handle->image_resize         = true;
    $handle->image_x              = 300;
    $handle->image_y              = 390;
    $handle->allowed = array('image/*');
    $handle->image_ratio_crop = true;
    $handle->process('../uploads/files/300-390/');
    if ($handle->processed) {
        $image_original_name = $handle->file_dst_name;
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
        $handle->clean();
    }
    $query = $db->prepare("
        UPDATE events SET 
        e_poster=?
        WHERE id=?
    ");
    // Poster güncelleme sorgusunu çalıştır
    $update = $query->execute(array(@$image_original_name, @$get_id));
}
$query = $db->prepare("
        UPDATE events SET 
        event_title=?, 
        e_detail=?,
        event_date=?,
        event_time=?,
        event_location=?,
        all_view=?
        WHERE id=?
    ");
// Kulüp bilgilerini güncelleme sorgusunu çalıştır
$update = $query->execute(array(@$e_adi, @$e_detail, @$e_date, @$e_time,$e_location, @$all_view, @$get_id));

if(!empty($e_date)) {
    $e_date = strtotime($e_date);
    $e_date = date('Y-m-d', $e_date);
}

if(!empty($e_time)) {
    $e_time = strtotime($e_time);
    $e_time = date('H:i', $e_time);
}

if ($update) {

    $clubs_clear = $db->prepare("DELETE FROM clubs_events WHERE event_id=?");
    $clubs_clear->execute(array(@$get_id));

    if(is_array(@$club_list)) {
        foreach($club_list as $club_item) {
            // Yeni kullanıcı-kulüp ilişkisi ekle
            $club_insert = $db->prepare("INSERT INTO clubs_events SET club_id=?, event_id=?");
            $club_insert->execute(array(@$club_item, @$get_id));
        }
    }

    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Kayıt başarıyla gerçekleşti!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';


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