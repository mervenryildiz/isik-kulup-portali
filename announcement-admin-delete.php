<?php

require_once __DIR__."/session_control.php";
require_once __DIR__ . "/connection.php";

$id         = (int)@$_GET['id'];
$club_id    = (int)@$_GET['club-id'];

if (!empty($id)) {
    $query = $db->prepare("DELETE FROM clubs_announcements WHERE announcement_id=?");
    $query->execute(array($id));

    $query = $db->prepare("DELETE FROM announcements WHERE id=?");
    $query->execute(array($id));
}

echo '
    <script>
        window.location.href = "/club-admin-detail.php?id='.@$club_id.'";

    </script>
    ';

exit; // Make sure to exit to prevent further execution

