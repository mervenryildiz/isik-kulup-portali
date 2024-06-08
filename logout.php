<?php
if(!isset($_SESSION)){ //Session'a sahip değilse başlat
    session_start();
}
$_SESSION['usersession'] = null; //Kullanıcı oturumunu null yap
unset($_SESSION['usersession']); //session bilgisini kaldır
if (!empty(@$_COOKIE['usercookie'])){ //cookie boş değilse
    setcookie('usercookie', "", time() - 3600, "/"); //cookie bilgisini sil
}
session_regenerate_id(); //Sessin ID yeniden oluştur
header("Location: /");