<?php
require_once __DIR__ . "/session_control.php";
if(@$club_user_count == 0) {
    header("Location: /error.php");
    die();
}
$get_id = (int)@$_GET['club-id'];
$query = $db->prepare("SELECT id, club_name, email, detail, logo FROM clubs WHERE id = ? && c_status=1");
$query->execute(array(@$get_id));
// SELECT sorgularında mutlaka fetch olmalı
$get = $query->fetch(PDO::FETCH_ASSOC);
$page_title = @$get['club_name']." - Duyuru Oluştur";

require_once __DIR__."/header.php";
$poster = "assets/images/no-image.jpg"; // Default poster image
?>


<section class="section">



    <form class="dataform" action="/announcement-admin-create-save.php?club-id=<?php echo @$get_id; ?>" method="post" enctype="multipart/form-data">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-header">
                            <strong><?php echo @$get['club_name']; ?></strong> Duyuru Ekle
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="inputName5" class="form-label">Başlık *</label>
                                    <input type="text" required class="form-control" name="a_title" id="inputName5">
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="inputAddress5" class="form-label">Detay *</label>
                                    <textarea class="form-control" rows="6" name="a_detail"></textarea>
                                </div>

                                <div class="col-12" id="formresult">

                                </div>

                                <div class="text-center">
                                    <a href="/club-admin-detail.php?id=<?php echo @$get_id; ?>" class="btn btn-light btn-lg">Geri Dön</a>
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
