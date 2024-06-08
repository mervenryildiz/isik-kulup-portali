<?php
require_once __DIR__."/connection.php";
require_once __DIR__."/function.php";

$user_id                    = @$_GET['u'];
$password                   = @$_POST['password'];
$password_confirmation      = @$_POST['password_confirmation'];
$password_code              = @$_SESSION['user_password_renew'];

$query = $db->prepare("SELECT id, role_id, s_status, user_name, user_surname, email FROM users WHERE id=? && password_renew_code=? && (s_status=1 || s_status=2) LIMIT 1");
$query->execute(array($user_id, $password_code));
$user_count = $query->rowCount();
$fetch = $query->fetch(PDO::FETCH_ASSOC);


if ($user_count == 0) {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Kayıtlı eposta hesabı bulunamadı!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    die();
}

$password_length = strlen($password);

if($password_length < 6) {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Şifre en az 6 karakter olmalıdır!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    die();
}
if($password !== $password_confirmation){
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Şifreler eşleşmedi!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    die();
}

$email = @$fetch['email'];
$password = md5($password);

$query = $db->prepare("UPDATE users SET s_password=?, password_renew_code=NULL, password_expire_date=NULL WHERE id=? && password_renew_code=? LIMIT 1");
$update = $query->execute([$password, $user_id, $password_code]);

if($update) {

    $subject = "Şifre Yenileme Başarılı";
    $message = 'Sayın '.@$fetch['user_name'].' '.@$fetch['user_surname'].'; <br>
    Işık Üniversitesi Kulüp Portalı şifre yenileme işleminiz başarıyla gerçekleşmiştir.';

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
