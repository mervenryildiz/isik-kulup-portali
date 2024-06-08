<?php

require_once __DIR__."/session_control.php";
require_once __DIR__ . "/../connection.php";

$id = @$_GET['id'];

if (!empty($id)) {

    $query = $db->prepare("DELETE FROM announcements WHERE id=?");
    $query->execute(array($id));

    $query = $db->prepare("DELETE FROM clubs_announcements WHERE announcement_id=?");
    $query->execute(array($id));
}

header("Location: announcement-list.php");
exit; // Make sure to exit to prevent further execution

