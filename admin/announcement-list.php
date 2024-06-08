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
                <li class="breadcrumb-item active">Duyuru Listesi</li>
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

                                <th>
                                    Duyuru Başlığı
                                </th>
                                <th>Oluşturulma Tarihi</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                            $query = $db->prepare("SELECT id,a_title,a_content,created_date FROM announcements");
                            $query->execute();
                            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <tr>

                                <td><?php echo @$row['a_title']; ?></td>
                                <!-- Düzenle ve Sil bağlantıları -->
                                <td><?php echo date("d.m.Y H:i", strtotime(@$row['created_date'])); ?></td>
                                <td>
                                    <a href="announcement-delete.php?id=<?php echo @$row['id']; ?>">Sil</a>
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