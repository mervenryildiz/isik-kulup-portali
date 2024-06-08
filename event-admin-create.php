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
$page_title = @$get['club_name']." - Etkinlik Oluştur";

require_once __DIR__."/header.php";
$poster = "assets/images/no-image.jpg"; // Default poster image
?>


<section class="section">



    <form class="dataform" action="/event-admin-create-save.php?club-id=<?php echo @$get_id; ?>" method="post" enctype="multipart/form-data">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-header">
                            <strong><?php echo @$get['club_name']; ?></strong> Etkinlik Ekle
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label>Poster: (500x650px)</label><br>
                                    <img src="<?php echo $poster; ?>" width="160" height="160" class="profile-img" alt="Profile">
                                    <input class="profile-img-input" type="file" name="poster"  accept="image/*" id="formFile">
                                    <div class="pt-2">
                                        <a href="" class="btn btn-danger btn-sm image-remove-btn" data-id="0" title="Afiş sil"><i class="bi bi-trash"></i></a>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="inputName5" class="form-label">Etkinlik Başlığı *</label>
                                    <input type="text" required class="form-control" name="event_title" id="inputName5">
                                </div>


                                <div class="col-md-6 mb-3">
                                    <label for="inputDate5" required class="form-label">Etkinlik Tarihi </label>
                                    <input type="date" class="form-control" id="inputDate5" name="event_date">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="inputTime5" required class="form-label">Etkinlik Saati </label>
                                    <input type="time" class="form-control" id="inputTime5" name="event_time">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="inputLocation5" class="form-label">Etkinlik Konumu </label>
                                    <input type="text" class="form-control" name="event_location" id="inputLocation5">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="inputName5" class="form-label">Görüntülenme</label>
                                    <select id="inputState" class="form-select" name="all_view">
                                        <option value="1">Herkese Açık</option>
                                        <option value="0">Sadece Kullanıcı</option>
                                    </select>
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="inputAddress5" class="form-label">Etkinlik Detayları</label>
                                    <textarea class="tinymce-editor" name="event_detail" id="dataeditor"></textarea>
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
