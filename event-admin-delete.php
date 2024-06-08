<?php

require_once __DIR__."/session_control.php";
require_once __DIR__ . "/connection.php";

$id         = (int)@$_GET['id'];
$club_id    = (int)@$_GET['club-id'];

if (!empty($id)) {

    $query = $db->prepare("SELECT e_poster FROM events WHERE id = ?");
    $query->execute(array(@$id));
    $get = $query->fetch(PDO::FETCH_ASSOC);

    if(!empty(@$get['e_poster'])) {
        unlink("uploads/files/500-650/".@$get['e_poster']);
        unlink("uploads/files/300-390/".@$get['e_poster']);
        unlink("uploads/files/200-200/".@$get['e_poster']);
    }

    $query = $db->prepare("DELETE FROM events WHERE id=?");
    $query->execute(array($id));

}

echo '
    <script>
        window.location.href = "/club-admin-detail.php?id='.@$club_id.'";

    </script>
    ';

exit; // Make sure to exit to prevent further execution

