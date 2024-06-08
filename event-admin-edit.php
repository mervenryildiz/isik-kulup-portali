<?php
require_once __DIR__."/session_control.php";
require_once __DIR__."/header.php";

$get_id = (int)@$_GET['id'];
$club_id = (int)@$_GET['club-id'];
$query = $db->prepare("SELECT id,event_title, event_date, event_time,created_date,e_poster, e_detail, event_location, all_view FROM events WHERE id = ?");
$query->execute(array(@$get_id));

$get = $query->fetch(PDO::FETCH_ASSOC);
$all_view = (int)@$get['all_view'];


$poster = "assets/img/no-image.jpg";
if(!empty(@$get['e_poster'])) {
    $poster = '/uploads/files/200-200/'.@$get['e_poster'];
}
?>

<section class="section">



    <form class="dataform" action="/event-admin-edit-save.php?id=<?php echo @$get_id; ?>&club-id=<?php echo @$club_id; ?>" method="post" enctype="multipart/form-data">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-header">
                            <strong><?php echo @$get['event_title']; ?></strong> Etkinlik Düzenle
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label>Poster: (500x650px)</label><br>
                                    <img src="<?php echo $poster; ?>" width="160" height="160" class="profile-img" alt="Profile">
                                    <input class="profile-img-input" type="file" name="poster"  accept="image/*" id="formFile">
                                    <div class="pt-2">
                                        <a href="javascript:void;" class="btn btn-danger btn-sm image-remove-btn" data-id="<?php echo @$get_id; ?>" title="Afiş sil"><i class="bi bi-trash"></i></a>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="inputName5" class="form-label">Etkinlik Başlığı *</label>
                                    <input type="text" required class="form-control" name="event_title" value="<?php echo @$get['event_title']; ?>"  id="inputName5">
                                </div>


                                <div class="col-md-6 mb-3">
                                    <label for="inputDate5" required class="form-label">Etkinlik Tarihi </label>
                                    <input type="date" class="form-control" id="inputDate5" value="<?php echo date("Y-m-d", strtotime(@$get['event_date']));?>" name="event_date">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="inputTime5" required class="form-label">Etkinlik Saati </label>
                                    <input type="time" class="form-control" id="inputTime5" value="<?php echo date("H:i", strtotime(@$get['event_time'])); ?>" name="event_time">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="inputLocation5" class="form-label">Etkinlik Konumu </label>
                                    <input type="text" class="form-control" name="event_location" value="<?php echo @$get['event_location']; ?>" id="inputLocation5">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="inputName5" class="form-label">Görüntülenme</label>
                                    <select id="inputState" class="form-select" name="all_view">
                                        <option value="1">Herkese Açık</option>
                                        <option value="0"<?php if($all_view == 0) { echo 'selected'; } ?>>Sadece Kullanıcı</option>
                                    </select>
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="inputAddress5" class="form-label">Etkinlik Detayları</label>
                                    <textarea class="tinymce-editor" name="event_detail"  id="dataeditor" <?php echo @$get['e_detail']; ?></textarea>
                                </div>

                                <div class="col-12" id="formresult">

                                </div>

                                <div class="text-center">
                                    <a href="/club-admin-detail.php?id=<?php echo @$club_id; ?>" class="btn btn-light btn-lg">Geri Dön</a>
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

<?php
require_once __DIR__."/footer.php";
?>