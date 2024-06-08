<?php
require_once __DIR__ . "/session_control.php";

if(empty(@$login_user) || @$login_user < 1) {
    header("Location: /error.php");
    die();
}

$page_title = @$userget_name." ".@$userget_surname." - Şifre Değiştir";

require_once __DIR__."/header.php";
?>


<section class="section">



    <form class="dataform" action="/user-profile-save.php" method="post" enctype="multipart/form-data">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <strong for="inputName5" class="form-label">Ad Soyad</strong>
                                    <p><?php echo @$userget_name." ".@$userget_surname; ?></p>
                                </div>

                                <div class="col-md-12">
                                    <strong for="inputName5" class="form-label">Eposta</strong>
                                    <p><?php echo @$userget_email; ?></p>
                                </div>

                                <div class="col-md-12">
                                    <hr>
                                </div>
                                <div class="col-md-12">
                                    <h5><strong>Şifre Güncelle</strong></h5>
                                    <p>Aşağıda şifre alanlarını doldurup kaydetmeniz durumunda şifreniz güncellenir.</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Şifre </label>
                                    <input type="password" class="form-control" name="password">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <labelclass="form-label">Şifre Tekrarı </label>
                                    <input type="password" class="form-control" name="password_renew">
                                </div>

                                <div class="col-12" id="formresult">

                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg formsubmitbutton"name="kaydet">Kaydet</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </form>



        </div>

</section>



<?php require_once __DIR__."/footer.php"; ?>
<script src="/plugins/tinymce/tinymce.min.js" referrerpolicy="origin"></script>

<script>
    tinymce.init({
        selector: '#dataeditor'
    });
</script>

</body>
</html>
