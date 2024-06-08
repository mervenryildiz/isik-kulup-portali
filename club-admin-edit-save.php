<?php
header('Content-type: application/json');
require_once __DIR__."/session_control.php";
require_once __DIR__."/connection.php";

$get_id         = (int)@$_GET['id'];
$c_adi          = @$_POST['club_adi'];
$c_detaylar     = @$_POST['club_detaylar'];
$logo           = @$_FILES['logo'];

$get_id = @$_GET['id'];
$query = $db->prepare("SELECT id, club_name, email, c_status, created_date, detail, logo FROM clubs WHERE id = ?");
$query->execute(array(@$get_id));
// SELECT sorgularında mutlaka fetch olmalı
$get = $query->fetch(PDO::FETCH_ASSOC);


require_once __DIR__."/admin/assets/plugins/class-upload/vendor/autoload.php"; // Dosya yükleme sınıfı

if(empty($c_adi)) {
    $response = ["response_message"=>"Lütfen kulüp adını boş bırakmayınız!", "status"=>"error"];
    $json = json_encode(@$response);
    echo $json;
    die();
}
 
$handle = new \Verot\Upload\Upload($logo, 'tr_TR');

$image_original_name = NULL;
if ($handle->uploaded) { // Dosya yükleme işlemi başlıyorsa
    // Eski logoları sil
    if(!empty(@$get['logo'])) { 
        unlink("uploads/files/600-600/".@$get['logo']);
        unlink("uploads/files/300-300/".@$get['logo']);
        unlink("uploads/files/200-200/".@$get['logo']);
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
    $handle->process('uploads/files/600-600/');
    if ($handle->processed) {
        $image_original_name = $handle->file_dst_name;
    }

    $handle->file_new_name_body   = $new_file_name;
    $handle->image_resize         = true;
    $handle->image_x              = 300;
    $handle->image_y              = 300;
    $handle->allowed = array('image/*');
    $handle->image_ratio_crop = true;
    $handle->process('uploads/files/300-300/');
    if ($handle->processed) {
        $image_original_name = $handle->file_dst_name; // dosya işleme başarılı olursa hedef dosya adı image_original_name değişkenine atanır ve dosyanın kaydedildiği yeni adı alır.
    }

    $handle->file_new_name_body   = $new_file_name;
    $handle->image_resize         = true;
    $handle->image_x              = 200;
    $handle->image_y              = 200;
    $handle->allowed = array('image/*');
    $handle->image_ratio_crop = true;
    $handle->process('uploads/files/200-200/');
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
        detail=?
        WHERE id=?
    ");
// Kulüp bilgilerini güncelleme sorgusunu çalıştır
$update = $query->execute(array(@$c_adi, @$c_detaylar, @$get_id));


if ($update) { // Güncelleme başarılıysa
    $response = ["response_message"=>"Kulüp bilgileri başarıyla güncellendi!", "status"=>"success"];

} else {
    $response = ["response_message"=>"Güncelleme işlemi hatalı. Lütfen daha sonra tekrar deneyiniz!", "status"=>"error"];
}

$json = json_encode(@$response);
echo $json;
?>
