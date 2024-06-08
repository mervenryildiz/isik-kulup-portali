<?php
require_once __DIR__."/session_control.php";
require_once __DIR__."/../connection.php";

$a_title = @$_POST['a_title'];
$a_content = @$_POST['a_content'];
$club_list      = @$_POST['club_list'];

if(!empty($a_content)) {
    $a_content = nl2br($a_content);
}

if(empty($club_list) || count($club_list) == 0){
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Lütfen duyurunun ait olduğu kulübü seçiniz! (*)
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    die();
}

$query = $db->prepare("
    INSERT INTO announcements SET a_title=?, 
    a_content=?
");

$insert = $query->execute(array($a_title, $a_content));
if ($insert) {
    $last_id = $db->lastInsertId();
    if(is_array(@$club_list)) {
        foreach($club_list as $club_item) {
            $club_insert = $db->prepare("INSERT INTO clubs_announcements SET club_id=?, announcement_id=?");
            $club_insert->execute(array(@$club_item, @$last_id));
        }
    }
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Kayıt başarıyla gerçekleşti!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';


    echo '
    <script>
    setTimeout(function() {
        location.reload(); // Sayfayı yenile
    }, 1000);
    </script>
    ';

} else {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Kayıt işlemi başarısız!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
}
?>
