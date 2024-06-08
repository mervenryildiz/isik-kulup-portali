<?php
ob_start();
if(!isset($_SESSION)){
    session_start();
}

$host = 'localhost'; // MySQL sunucusunun adresi
$dbname = 'isikclub'; // Veritabanının adı
$username = 'root'; // MySQL kullanıcı adı
$password = ''; // MySQL şifresi
date_default_timezone_set('Europe/Istanbul');

try {
    // PDO bağlantısını oluştur
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Hata modunu ayarla
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Bağlantı hatası durumunda hata mesajını göster
    echo "Bağlantı hatası: " . $e->getMessage();
}
?>
