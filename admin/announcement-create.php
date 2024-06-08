<?php
require_once __DIR__."/session_control.php";
require_once __DIR__."/header.php";
require_once __DIR__."/sidebar.php";
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Duyuru Listesi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Ana Sayfa</a></li>
                <li class="breadcrumb-item">Duyurular</li>
                <li class="breadcrumb-item active">Duyuru Ekle</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->


    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Duyuru Ekle</h5>
                        <!-- Multi Columns Form -->
                        <form class="row g-3 dataform" action="announcement-create-save.php" method="post" enctype="multipart/form-data">
                            <div class="col-md-6">
                                <label for="inputName5" class="form-label">Duyuru Başlığı *</label>
                                <input type="text" required class="form-control" name="a_title" id="inputName5">
                            </div>
                            <div class="col-md-12">
                                <label for="inputName5" class="form-label">Kulüp Seç</label>
                                <input type="text" class="form-control user-search-input mb-2" placeholder="Kulüp ara" name="user-search-input">
                                <div class="users-list-table">
                                    <?php
                                    // Kulüpleri adlarına göre sıralı bir şekilde veritabanından al
                                    $club_query = $db->prepare("SELECT id, club_name, email FROM clubs ORDER BY club_name ASC");
                                    $club_query->execute();
                                    // Her bir kulüp için döngü
                                    while($club_row = $club_query->fetch(PDO::FETCH_ASSOC)) {
                                        ?>
                                        <div class="form-check" data-name="<?php echo mb_strtolower(@$club_row['club_name']); ?>" data-email="<?php echo mb_strtolower(@$club_row['email']); ?>">
                                            <input class="form-check-input" type="checkbox" id="club_list<?php echo @$club_row['id']; ?>"
                                                   value="<?php echo @$club_row['id']; ?>" name="club_list[]">
                                            <!-- listele -->
                                            <label class="form-check-label" for="club_list<?php echo @$club_row['id']; ?>">
                                                <?php echo @$club_row['club_name']." (".@$club_row['email'].")"; ?>
                                            </label>
                                        </div>
                                    <?php } ?>

                                </div>
                            </div>

                            <div class="col-12">
                                <label for="inputAddress5" class="form-label">Duyuru Detayı</label>
                                <textarea class="form-control" rows="10" name="a_content"></textarea>
                            </div>

                            <div class="col-12" id="formresult">

                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg formsubmitbutton"name="kaydet">Kaydet</button>
                            </div>
                        </form><!-- End Multi Columns Form -->

                    </div>
                </div>
            </div>
        </div>
    </section>

</main><!-- End #main -->

<?php
require_once __DIR__."/footer.php";
require_once __DIR__."/footer_script.php";
?>

</body>

</html>