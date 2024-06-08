<?php
require_once __DIR__."/connection.php";
require_once __DIR__."/function.php";

$email      = @$_POST['email'];

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $domain = substr(strrchr($email, "@"), 1);
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

$query = $db->prepare("SELECT id, role_id, s_status, user_name, user_surname FROM users WHERE (s_status=1 || s_status=2) && email=? LIMIT 1");
$query->execute([$email]);
$fetch = $query->fetch(PDO::FETCH_ASSOC);

$user_count = $query->rowCount();

if ($user_count == 0) {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Kayıtlı eposta hesabı bulunamadı!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    die();
}

$password_code = md5(uniqid(rand(), true));
$password_expire_date = strtotime('+24 hours');
$password_expire_date = date('Y-m-d H:i:s', $password_expire_date);

$query = $db->prepare("UPDATE users SET password_renew_code=?, password_expire_date=? WHERE email=? LIMIT 1");
$update = $query->execute([$password_code, $password_expire_date, $email]);

if($update) {

    $subject = "Şifre Yenileme Talebi";
    $message = 'Sayın '.@$fetch['user_name'].' '.@$fetch['user_surname'].'; <br>
    Işık Üniversitesi Kulüp Portalı şifre yenileme talebiniz doğrultusunda aşağıdaki linke tıklayarak şifrenizi yenileyebilirsiniz.<br><br>
    
    <a href="https://google.com/create-pass.php?u='.@$fetch['id'].'&p='.@$password_code.'">Şifremi Yenile</a>
    ';

    $mail_send = mail_send($email, $subject, $message);

    if($mail_send) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Şifre yenileme talimatları eposta edresinize iletilmiştir. Lütfen epostanızı kontrol edin.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';

        echo '<script>
    $(".dataform")[0].reset();
    setTimeout(function() {
      window.location.href = "/login.php";
    }, 1000);
    </script>';

    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Şifre yenileme işlemi başarısız oldu. Lütfen sistem yöneticisi ile iletişime geçiniz!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }

}
