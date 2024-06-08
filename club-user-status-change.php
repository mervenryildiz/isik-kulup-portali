<?php

require_once __DIR__."/session_control.php";
require_once __DIR__ . "/connection.php";

$club_id    = (int)@$_GET['club-id'];
$user_id    = (int)@$_GET['user-id'];
$user_status = (int)@$_GET['user-status'];


if (!empty($user_id)) {
    $query=$db->prepare("UPDATE clubs_users SET user_status=? WHERE club_id=? && user_id=?");
    $query->execute(array($user_status,$club_id,$user_id));
}

echo '
    <script>
        window.location.href = "/club-admin-detail.php?id='.@$club_id.'";

    </script>
    ';

exit; // Make sure to exit to prevent further execution

