<?php
require_once __DIR__."/session_control.php";
require_once __DIR__."/../connection.php";

$o_adi = @$_POST['user_adi'];
$o_soyadi = @$_POST['user_soyadi'];
$o_durum = @$_POST['user_durum'];
$o_email = @$_POST['user_email'];
$o_password = @$_POST['user_password'];
$o_detaylar = @$_POST['user_detaylar'];
$o_yetki = @$_POST['user_yetki'];

$query = $db->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
$query->execute(array($o_email));

$users_count = $query->rowCount();


if(empty($o_adi) || empty($o_soyadi) || empty($o_email) || empty($o_password)) {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Lütfen zorunlu alanları doldurunuz! (*)
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    die();
}

if($users_count > 0 ) {
     echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Bu öğrenci sistemde mevcut!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';

     die();
}

$password_length = strlen($o_password);

if($password_length < 6) {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Şifre en az 6 karakter olmalıdır!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    die();
}


if (filter_var($o_email, FILTER_VALIDATE_EMAIL)) {
    $domain = substr(strrchr($o_email, "@"), 1);
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



$o_password = md5($o_password);


$query = $db->prepare("
    INSERT INTO users SET user_name=?, 
    user_surname=?,
    email=?,
    s_status=?,
    s_password=?,
    detail=?,
    role_id=?
");

$insert = $query->execute(array($o_adi, $o_soyadi, $o_email, $o_durum, $o_password, $o_detaylar, $o_yetki));

if ($insert) {
    $last_id = $db->lastInsertId();
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Kayıt başarıyla gerçekleşti!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';


    echo '<script>$(".dataform")[0].reset();</script>';

} else {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Kayıt işlemi başarısız!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
}
?>