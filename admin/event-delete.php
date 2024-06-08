<?php

require_once __DIR__."/session_control.php";
require_once __DIR__ . "/../connection.php";

$id = @$_GET['id'];

if (!empty($id)) {

    $query = $db->prepare("SELECT e_poster FROM events WHERE id = ?");
    $query->execute(array(@$id));
    $get = $query->fetch(PDO::FETCH_ASSOC);

    if(!empty(@$get['e_poster'])) {
        unlink("../uploads/files/500-650/".@$get['e_poster']);
        unlink("../uploads/files/300-390/".@$get['e_poster']);
        unlink("../uploads/files/200-200/".@$get['e_poster']);
    }

    $query = $db->prepare("DELETE FROM events WHERE id=?");
    $query->execute(array($id));

    $query = $db->prepare("DELETE FROM clubs_events WHERE event_id=?");
    $query->execute(array($id));
}

header("Location: event-list.php");
exit; // Make sure to exit to prevent further execution

