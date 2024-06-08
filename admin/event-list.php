<?php
require_once __DIR__."/session_control.php";
require_once __DIR__."/header.php";
require_once __DIR__."/sidebar.php";
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Etkinlik Listesi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Ana Sayfa</a></li>
                <li class="breadcrumb-item">Etkinlikler</li>
                <li class="breadcrumb-item active">Etkinlik Listesi</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>Afiş</th>
                                <th>
                                    Etkinlik Başlığı
                                </th>
                                <th data-type="date" data-format="dd.mm.YYYY">Etkinlik Tarihi</th>
                                <th data-type="time" data-format="H:i">Etkinlik Saati</th>
                                <th>Oluşturulma Tarihi</th>
                                <th>Görüntülenme</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $query = $db->prepare("SELECT id,event_title,event_date,event_time,created_date,e_poster,all_view FROM events");
                            $query->execute();

                            // Her bir etkinlik bilgisi için döngü
                            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                $all_view = $row['all_view'];

                                $poster = "assets/img/no-image.jpg";
                                if(!empty(@$row['e_poster'])) {
                                    $poster = '../uploads/files/200-200/'.@$row['e_poster'];
                                }

                                ?>
                                <tr>

                                    <td><img src="<?php echo $poster; ?>" class="border-radius" width="40" height="40" /></td>
                                    <td><?php echo @$row['event_title']; ?></td>
                                    <td><?php echo date("d.m.Y", strtotime(@$row['event_date'])); ?></td>
                                    <td><?php echo date("H:i", strtotime(@$row['event_time'])); ?></td>
                                    <!-- Düzenle ve Sil bağlantıları -->
                                    <td><?php echo date("d.m.Y H:i", strtotime(@$row['created_date'])); ?></td>

                                    <td><?php
                                        if ($all_view == '1') {
                                            echo "Herkese açık";
                                        }  else {
                                            echo "Sadece Kullanıcı";
                                        }
                                        ?></td>

                                    <td>
                                        <a href="event-edit.php?id=<?php echo @$row['id']; ?>">Düzenle</a> -
                                        <a href="event-delete.php?id=<?php echo @$row['id']; ?>">Sil</a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>

                        <!-- End Table with stripped rows -->

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
