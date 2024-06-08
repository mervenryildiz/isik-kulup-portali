<?php
require_once __DIR__."/connection.php";
if (!empty(@$_COOKIE['user_cookie'])) { //Cookie boş değilse
    $login_user = @$_COOKIE['usercookie']; //Cookie bilgisini al
} else {
    $login_user = @$_SESSION['usersession']; //Kullanıcı session bilgisini al
}

if(!empty($login_user)){
    $login_user = base64_decode($login_user); //Kullanıcı bilgisini base64 formundan çöz
    //Veritabanında aktif ve role_id'si 3 olan kullanıcıyı seç
    $query = $db->prepare("SELECT id, role_id FROM users WHERE s_status=1 && id=? LIMIT 1");
    $query->execute([(int)$login_user]);
    $fetch = $query->fetch(PDO::FETCH_ASSOC);

    $user_count = $query->rowCount();

    if($user_count > 0){ //Eğer kullanıcı bulunduysa club-list.php sayfasına yönlendir.
        header("Location: /");
        die();
    } else {
        header("Location: logout.php");  //Bulunamadıysa logout.php sayfasına yönlendir.
        die();
    }

}
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Giriş Yap - Işık Üniversitesi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
          rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="/assets/css/isik_style.css">
</head>
<body>

<div class="login-container">
    <div class="login-header">
        <a href="/"><img src="https://www.isikun.edu.tr/assets/images/logo.svg" alt="Işık Üniversitesi"></a>
    </div>

    <div class="login-body">
        <div class="login-form">
            <div class="pb-4">
                <h5 class="card-title text-center pb-0 fs-4">Giriş Yap</h5>
                <p class="text-center small">Mail ve Parolanızı Giriniz</p>
            </div>
            <form class="dataform" novalidate action="login-control.php" method="post" enctype="multipart/form-data">

                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="floatingInput" name="email" placeholder="***@isik.edu.tr">
                    <label for="floatingInput">Eposta</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Şifre">
                    <label for="floatingPassword">Şifre</label>
                </div>

                <div class="form-check form-switch d-flex justify-content-between">
                    <div>
                        <input class="form-check-input" type="checkbox" value="1" role="switch" name="remember" id="flexSwitchCheckDefault">
                        <label class="form-check-label" for="flexSwitchCheckDefault">Beni Hatırla</label>
                    </div>
                    <div>
                        <a href="forgot-pass.php" class="text-decoration-none">Şifremi Unuttum</a>
                    </div>
                </div>

                <div class="mb-3 mt-3" id="formresult">

                </div>

                <div class="col-12">
                    <button class="btn btn-primary w-100 formsubmitbutton btn-lg" type="submit">Giriş Yap</button>
                </div>

                <div class="row pt-4">
                    <div class="col-6">
                        <a href="#" class="btn btn-outline-secondary w-100 disabled">Giriş Yap</a>
                    </div>

                    <div class="col-6">
                        <a href="sign-up.php" class="btn btn-outline-secondary w-100">Kaydol</a>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <div class="login-footer">
        @2024 - Işık Üniversitesi
    </div>
</div>

<script src="assets/js/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="assets/js/jquery.form.min.js"></script>
<script src="assets/js/isik_script.js"></script>

</body>
</html>