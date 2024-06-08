<?php
require_once __DIR__."/connection.php";
if (!empty(@$_COOKIE['usercookie'])) { //cookie boş değilse cookie bilgilerini al
    $login_user = @$_COOKIE['usercookie'];
} else {
    $login_user = @$_SESSION['usersession']; //cookie yoksa session bilgilerini al
}

if(!empty(@$login_user)) {
    $login_user = base64_decode($login_user); //Şifreli bilgiyi çöz
}
$query = $db->prepare("SELECT id, user_name, user_surname, email,s_status,role_id FROM users WHERE s_status=1 && id=? LIMIT 1"); //Aktif ve sistem yöneticisi olarak seçili olan kullanıcıyı seç
$query->execute([(int)@$login_user]);
$fetch = $query->fetch(PDO::FETCH_ASSOC);

$userget_name = @$fetch['user_name'];
$userget_surname = @$fetch['user_surname'];
$userget_email = @$fetch['email'];
$userget_role_id = @$fetch['role_id'];


$user_count = $query->rowCount();

$query = $db->prepare("SELECT user_id, club_id FROM users_clubs WHERE user_id=?");
$query->execute([$login_user]);
$club_user_count = (int)@$query->rowCount();