<?php
require_once __DIR__ . "/session_control.php";
if(@$club_user_count == 0) {
    header("Location: /error.php");
    die();
}
$get_id = (int)@$_GET['id'];
$query = $db->prepare("SELECT id, club_name, email, detail, logo FROM clubs WHERE id = ? && c_status=1");
$query->execute(array(@$get_id));
// SELECT sorgularında mutlaka fetch olmalı
$get = $query->fetch(PDO::FETCH_ASSOC);
$page_title = @$get['club_name']." - Kulüp Profili";

require_once __DIR__."/header.php";
$logo = "assets/images/no-image.jpg";
if(!empty(@$get['logo'])) {
    $logo = '/uploads/files/600-600/'.@$get['logo'];
}

?>


<section class="section">
    <form class="datasweetform" action="club-admin-edit-save.php?id=<?php echo @$get_id; ?>" method="post" enctype="multipart/form-data">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-12">
                    <div class="club-intro-img club-image-edit">
                        <!-- Mevcut logo görüntüsü -->
                        <img src="<?php echo $logo; ?>" width="160" height="160" class="profile-img" alt="Profile">
                        <!-- Yeni logo yükleme alanı -->
                        <input class="profile-img-input" type="file" name="logo"  accept="image/*" id="formFile">
                        <!-- Logo silme butonu -->
                        <div class="pt-2">
                            <a href="javascript:void;" class="btn btn-danger btn-sm image-remove-btn" data-id="<?php echo @$get_id; ?>" title="Logo sil"><i class="bi bi-trash"></i></a>
                        </div>

                    </div>
                </div>
                <div class="col-lg-8 col-md-8 col-12">
                    <div class="club-intro-box">
                        <h1>
                        <input type="text" class="form-control form-control-lg" name="club_adi" value="<?php echo @$get['club_name']; ?>">
                        </h1>
                        <p><strong>İletişim:</strong> <?php echo @$get['email']; ?></p>
                        <div class="mb-4">
                            <textarea name="club_detaylar" id="dataeditor"><?php echo @$get['detail']; ?></textarea>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-success formsubmitbutton">Bilgileri Güncelle</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="container mt-5">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true"><strong>Etkinlikler</strong></button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false"><strong>Kulüp Üyeleri</strong></button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link" id="annon-tab" data-bs-toggle="tab" data-bs-target="#annon" type="button" role="tab" aria-controls="annon" aria-selected="false"><strong>Duyurular</strong></button>
            </li>

        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="tab-header">
                    <h4><strong>Etkinlikler</strong></h4>
                    <a href="/event-admin-create.php?club-id=<?php echo @$get_id; ?>" class="btn btn-primary">Etkinlik Ekle</a>
                </div>


                <?php
                $query = $db->prepare("
                SELECT 
                    events.id,
                    events.event_title, 
                    events.e_detail, 
                    events.e_poster, 
                    events.event_date, 
                    events.event_time, 
                    events.event_location 
                FROM events
                LEFT JOIN clubs_events ON events.id = clubs_events.event_id
                WHERE clubs_events.club_id = ? ORDER BY events.event_date DESC, events.event_time ASC
             ");
                $query->execute(array(@$get_id));
                $event_count = $query->rowCount();
                if($event_count > 0) {
                ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Resim</th>
                                <th>Başlık</th>
                                <th width="160">Tarih</th>
                                <th>Konum</th>
                                <th width="120"></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                        $event_title = $row['event_title'];
                        $e_detail = $row['e_detail'];
                        $e_poster = !empty($row['e_poster']) ? '/uploads/files/200-200/' . $row['e_poster'] : '/assets/images/no-image.jpg';
                        $event_date = $row['event_date'];
                        $event_time = $row['event_time'];
                        $event_location = $row['event_location'];
                        ?>
                            <tr valign="middle">
                                <td width="80"><a href="/event-admin-detail.php?id=<?php echo @$row['id']; ?>"><img class="rounded" style="width: 70px;" src="<?php echo htmlspecialchars($e_poster); ?>" alt="<?php echo $event_title; ?>"></a></td>
                                <td><a href="/event-admin-detail.php?id=<?php echo @$row['id']; ?>">
                                        <strong class="card-title"><?php echo $event_title; ?></strong>
                                    </a></td>
                                <td><?php echo date("d.m.Y", strtotime($event_date)); ?> <?php echo date("H:i", strtotime($event_time)); ?></td>
                                <td><?php echo $event_location; ?></td>
                                <td>
                                    <a href="event-admin-edit.php?id=<?php echo @$row['id']; ?>&club-id=<?php echo @$get_id; ?>">Düzenle</a> -
                                    <a href="event-admin-delete.php?id=<?php echo @$row['id']; ?>&club-id=<?php echo @$get_id; ?>">Sil</a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } else { ?>

                    <div class="alert alert-warning" role="alert">
                        <strong><?php echo @$get['club_name']; ?></strong> için etkinlik bulunmamaktadır!
                    </div>
                <?php } ?>


            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="tab-header">
                    <h4><strong>Kulüp Üyeleri</strong></h4>
                </div>

                <?php
                $query = $db->prepare("
                SELECT
                    clubs_users.id,
                    clubs_users.user_id,
                    clubs_users.club_id,
                    clubs_users.user_status,
                    clubs_users.created_date,
                    users.user_name,
                    users.user_surname,
                    users.email 
                FROM
                    clubs_users
                    LEFT JOIN users ON clubs_users.user_id = users.id
                WHERE clubs_users.club_id=? ORDER BY clubs_users.user_status DESC
             ");
                $query->execute(array(@$get_id));
                $user_count = $query->rowCount();
                if($user_count > 0) {
                    ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Ad Soyad</th>
                                <th>Email</th>
                                <th width="160">Tarih</th>
                                <th width="140">Durum</th>
                                <th width="120"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                $user_name = $row['user_name'];
                                $user_surname = $row['user_surname'];
                                $user_email = $row['email'];
                                $user_status = $row['user_status'];
                                $created_date = $row['created_date'];
                                ?>
                                <tr valign="middle">
                                    <td> <?php echo @$row['user_name']." ".@$row['user_surname']; ?> </td>
                                    <td> <?php echo @$row['email']; ?> </td>
                                    <td> <?php echo date("d.m.Y H:i", strtotime(@$row['created_date'])); ?> </td>
                                    <td><?php
                                        if ($user_status == '1') {
                                            echo "<span class='badge text-bg-success'>Aktif</span>";
                                        } elseif($user_status == '2') {
                                            echo "<span class='badge text-bg-warning'>Başvuru</span>";
                                        } else {
                                            echo "<span class='badge text-bg-secondary'>Pasif</span>";
                                        }

                                        ?></td>
                                    <td><div class="d-flex gap-1">
                                            <?php if($user_status != '1') { ?>
                                            <a href="/club-user-status-change.php?club-id=<?php echo @$get_id; ?>&user-id=<?php echo @$row['user_id']; ?>&user-status=1" class="btn btn-success" >Aktif Et</a>
                                            <?php } else { ?>
                                            <a href="/club-user-status-change.php?club-id=<?php echo @$get_id; ?>&user-id=<?php echo @$row['user_id']; ?>&user-status=0" class="btn btn-secondary" >Pasif Et</a>
                                            <?php } ?>
                                        </div></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>

                    <div class="alert alert-warning" role="alert">
                        <strong><?php echo @$get['club_name']; ?></strong> için üye kaydı bulunmamaktadır!
                    </div>
                <?php } ?>

            </div>
            <div class="tab-pane fade" id="annon" role="tabpanel" aria-labelledby="annon-tab">
                <div class="tab-header">
                    <h4><strong>Duyurular</strong></h4>
                    <a href="/announcement-admin-create.php?club-id=<?php echo @$get_id; ?>" class="btn btn-primary">Duyuru Ekle</a>
                </div>

                <?php
                $query = $db->prepare("
                SELECT
                    announcements.id, 
                    announcements.a_title, 
                    announcements.created_date, 
                    clubs_announcements.club_id
                FROM
                    announcements
                    LEFT JOIN
                    clubs_announcements
                    ON 
                        announcements.id = clubs_announcements.announcement_id
                WHERE clubs_announcements.club_id = ? ORDER BY id DESC
             ");
                $query->execute(array(@$get_id));
                $user_count = $query->rowCount();
                if($user_count > 0) {
                    ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Başlık</th>
                                <th width="160">Tarih</th>
                                <th width="120"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                $user_name = $row['a_title'];
                                $created_date = $row['created_date'];
                                ?>
                                <tr valign="middle">
                                    <td> <?php echo @$row['a_title']; ?> </td>
                                    <td> <?php echo date("d.m.Y H:i", strtotime(@$row['created_date'])); ?> </td>
                                    <td><a href="announcement-admin-delete.php?id=<?php echo @$row['id']; ?>&club-id=<?php echo @$get_id; ?>">Sil</a></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>

                    <div class="alert alert-warning" role="alert">
                        <strong><?php echo @$get['club_name']; ?></strong> için üye kaydı bulunmamaktadır!
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
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
