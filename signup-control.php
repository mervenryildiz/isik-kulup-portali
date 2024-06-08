<?php
require_once __DIR__."/connection.php";

// Kullanıcı girdilerini al ve geçerli olduğunu kontrol et
$user_name      = @$_POST['user_name'];
$user_surname   = @$_POST['user_surname'];
$email          = @$_POST['email'];
$password       = @$_POST['password'];

if(empty($user_name) || empty($user_surname) || empty($email) || empty($password)) {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Lütfen zorunlu alanları doldurunuz! (*)
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    die();
}

// Kullanıcı var mı kontrol et
$query = $db->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
$query->execute(array($email));

$users_count = $query->rowCount();

if($users_count > 0) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Bu öğrenci sistemde mevcut!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    die();
}

// Şifre uzunluğunu kontrol et
$password_length = strlen($password);

if($password_length < 6) {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Şifre en az 6 karakter olmalıdır!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    die();
}
if($password !== $_POST["password_confirmation"]){
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Şifreler eşleşmedi!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    die();
}


if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $domain = substr(strrchr($email, "@"), 1);
    if ($domain !== "isik.edu.tr" && $domain !== "isikun.edu.tr") {
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


$password = md5($password);

// Kullanıcıyı veritabanına ekle
$query = $db->prepare("
    INSERT INTO users (user_name, user_surname, email, s_password, s_status, role_id) 
    VALUES (?, ?, ?, ?, ?, ?)
");

$insert = $query->execute(array($user_name, $user_surname, $email, $password, 2, 1));

if ($insert) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Kayıt başarıyla gerçekleşti!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';

    echo '<script>
    $(".dataform")[0].reset();
    setTimeout(function() {
      window.location.href = "/";
    }, 1000);
    </script>';

} else {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Kayıt işlemi başarısız!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
}
?>