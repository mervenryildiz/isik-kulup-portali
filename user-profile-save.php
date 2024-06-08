<?php
require_once __DIR__."/session_control.php";
if(empty(@$login_user) || @$login_user < 1) {
    header("Location: /error.php");
    die();
}
require_once __DIR__."/connection.php";

$password           = @$_POST['password'];
$password_renew     = @$_POST['password_renew'];

if(empty($password)) {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Lütfen şifrenizi giriniz!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
    die();
} elseif(!empty($password)) {
    $password_length = strlen($password);
    if($password_length < 6) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Şifre en az 6 karakter olmalıdır!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        die();
    }
} elseif($password != $password_renew) {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Şifre tekrarı aynı girilmedi!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
    die();
}


$o_password = md5($password);

$query = $db->prepare("
        UPDATE users SET 
        s_password = ?
        WHERE id=?
    ");

$update = $query->execute(array(@$o_password, @$login_user));

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