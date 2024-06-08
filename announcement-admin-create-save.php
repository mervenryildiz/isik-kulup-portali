<?php
require_once __DIR__."/session_control.php";
require_once __DIR__."/connection.php";

$a_adi      = @$_POST['a_title'];
$a_detail   = @$_POST['a_detail'];
$club_id    = (int)@$_GET['club-id'];

if(empty($a_adi) || empty($a_detail)){
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Lütfen zorunlu alanları doldurunuz! (*)
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    die();
}

if($club_id == 0){
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Lütfen etkinliğin ait olduğu kulübü seçiniz! (*)
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    die();
}


$a_detail = nl2br(@$a_detail);

$query = $db->prepare( "
    INSERT INTO announcements SET 
    a_title=?,
    a_content=?");
$insert = $query->execute(array($a_adi, $a_detail));


if ($insert) {
    $last_id = $db->lastInsertId();

    $club_insert = $db->prepare("INSERT INTO clubs_announcements SET club_id=?, announcement_id=?");
    $club_insert->execute(array(@$club_id, @$last_id));

    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Kayıt başarıyla gerçekleşti!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    echo '
    <script>
    setTimeout(function() {
        window.location.href = "/club-admin-detail.php?id='.@$club_id.'";
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