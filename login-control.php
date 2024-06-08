<?php
require_once __DIR__."/connection.php";

$email          = @$_POST['email'];
$password       = @$_POST['password'];
$remember       = (int)@$_POST['remember'];

if(empty($email) || empty($password)) {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Lütfen zorunlu alanları doldurunuz! (*)
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    die();
}

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


$query = $db->prepare("SELECT id, role_id, s_status FROM users WHERE (s_status=1 || s_status=2) && email=? && s_password=? LIMIT 1");
$query->execute([$email, md5($password)]);
$fetch = $query->fetch(PDO::FETCH_ASSOC);

$user_count = $query->rowCount();

$user_id    = @$fetch['id'];
$user_role  = @$fetch['role_id'];
$user_status  = @$fetch['s_status'];

if($user_count > 0) {

    if($user_status == 2) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            Üye başvuru onay sürecindedir!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        die();
    }


    $_SESSION['usersession'] = base64_encode($user_id);
    if($remember == 1) {
        setcookie('usercookie', base64_encode($user_id), time() + 60*60*24*14, "/");
    }

    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Giriş işlemi başarılı!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';

    echo '
    <script>
    setTimeout(function() {
        location.reload(); // Sayfayı yenile
    }, 1000);
    </script>';

} else {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Kullanıcı adı veya şifre hatalı!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
}
?>
