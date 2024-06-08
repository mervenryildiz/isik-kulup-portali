<?php
require_once __DIR__."/../connection.php";
if (!empty(@$_COOKIE['usercookie'])) { //cookie boş değilse cookie bilgilerini al
    $login_user = @$_COOKIE['usercookie'];
} else {
    $login_user = @$_SESSION['usersession']; //cookie yoksa session bilgilerini al
}

$login_user = base64_decode($login_user); //Şifreli bilgiyi çöz
$query = $db->prepare("SELECT id, role_id, user_name, user_surname, email FROM users WHERE s_status=1 && role_id=3 && id=? LIMIT 1"); //Aktif ve sistem yöneticisi olarak seçili olan kullanıcıyı seç
$query->execute([(int)@$login_user]);
$fetch = $query->fetch(PDO::FETCH_ASSOC);

$userget_name = @$fetch['user_name'];
$userget_surname = @$fetch['user_surname'];
$userget_email = @$fetch['email'];


$user_count = $query->rowCount();

if(!empty($login_user)){ //Kullanıcı bilgisi boş değilse
    if($user_count == 0){ //Kullanıcı bulunamadıysa error-login-control.php sayfasına yönlendir
        header("Location: error-login-control.php");
        die();
    }
} else {
    if($user_count == 0){
        header("Location: error-login-control.php");
        die();
    }
}