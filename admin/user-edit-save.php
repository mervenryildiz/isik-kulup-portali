<?php
require_once __DIR__."/session_control.php";
require_once __DIR__."/../connection.php";

$get_id         = @$_GET['id'];
$o_adi          = @$_POST['user_adi'];
$o_soyadi       = @$_POST['user_soyadi'];
$o_durum        = @$_POST['user_durum'];
$o_password     = @$_POST['user_password'];
$o_detaylar     = @$_POST['user_detaylar'];
$o_yetki        = @$_POST['user_yetki'];

if(empty($o_adi) || empty($o_soyadi)) {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Lütfen zorunlu alanları doldurunuz! (*)
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    die();
}


if(!empty($o_password)) {
    $password_length = strlen($o_password);
    if($password_length < 6) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Şifre en az 6 karakter olmalıdır!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        die();
    }

    $o_password = md5($o_password);

    $query = $db->prepare("
        UPDATE users SET 
        s_password=? WHERE id=?
    ");

    $update = $query->execute(array($o_password, $get_id));
}

$query = $db->prepare("
        UPDATE users SET 
        user_name=?, 
        user_surname=?,
        s_status=?,
        detail=?,
        role_id=?
       WHERE id=?
    ");

$update = $query->execute(array($o_adi, $o_soyadi, $o_durum, $o_detaylar, $o_yetki, $get_id));


if ($update) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Kayıt başarıyla gerçekleşti!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';

} else {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Kayıt işlemi başarısız!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
}
?>
