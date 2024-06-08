<?php
require_once __DIR__."/session_control.php";
require_once __DIR__."/../connection.php";

$c_adi = @$_POST['club_adi'];
$c_email = @$_POST['club_email'];
$c_password = @$_POST['club_password'];
$c_durum = @$_POST['club_durum'];
$c_detaylar = @$_POST['club_detaylar'];
$user_list      = @$_POST['user_list'];
$logo           = @$_FILES['logo'];


require_once __DIR__."/assets/plugins/class-upload/vendor/autoload.php";


$query = $db->prepare("SELECT id FROM clubs WHERE email=? LIMIT 1");
$query->execute(array($c_email));

$clubs_count= $query->rowCount();

if(empty($c_adi) || empty($c_email)){
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Lütfen zorunlu alanları doldurunuz! (*)
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    die();
}

if($clubs_count > 0 ) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
               Bu kulüp sistemde mevcut!
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
           </div>';

    die();
}


if (filter_var($c_email, FILTER_VALIDATE_EMAIL)) {
    $domain = substr(strrchr($c_email, "@"), 1);
    if ($domain === "isik.edu.tr" || $domain === "isikun.edu.tr") {

    } else {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Geçerli bir eposta adresi giriniz!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        die();
    }
} else {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Geçerli bir eposta adresi giriniz!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        die();
}

$handle = new \Verot\Upload\Upload($logo, 'tr_TR');

$image_original_name = NULL;
if ($handle->uploaded) {
    $file_extention = pathinfo($logo['name'], PATHINFO_EXTENSION);
    $file_name      = pathinfo($logo['name'], PATHINFO_FILENAME);
    $new_file_name = $file_name."-".RAND(100000,999999);

    $handle->file_new_name_body   = $new_file_name;
    $handle->image_resize         = true;
    $handle->image_x              = 600;
    $handle->image_y              = 600;
    $handle->allowed = array('image/*');
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
}




$query = $db->prepare( "
    INSERT INTO clubs SET 
    club_name=?,
    email=?,
    c_status=?,
    detail=?,
    logo=?");
$insert = $query->execute(array($c_adi, $c_email, $c_durum, $c_detaylar, $image_original_name));

if ($insert) {
    $last_id = $db->lastInsertId();

    if(is_array(@$user_list)) {
        foreach($user_list as $user_item) {
            //Kulüp yöneticisi atama.
            $user_insert = $db->prepare("INSERT INTO users_clubs SET user_id=?, club_id=?");
            $user_insert->execute(array(@$user_item, @$last_id));

            //Kulüp yöneticisi atandığında otomatik olarak kulüp üyesi olarak ta atamaktadır.
            $user_insert = $db->prepare("INSERT INTO clubs_users SET user_id=?, club_id=?, user_status=1");
            $user_insert->execute(array(@$user_item, @$last_id));
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