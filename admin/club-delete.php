<?php
require_once __DIR__."/session_control.php";
require_once __DIR__."/../connection.php";

$id = @$_GET['id'];

if(!empty($id)) {

    $query = $db->prepare("SELECT logo FROM clubs WHERE id = ?");
    $query->execute(array(@$id));
    $get = $query->fetch(PDO::FETCH_ASSOC);

    if(!empty(@$get['logo'])) {
        unlink("../uploads/files/600-600/".@$get['logo']);
        unlink("../uploads/files/300-300/".@$get['logo']);
        unlink("../uploads/files/200-200/".@$get['logo']);
    }


    $query = $db->prepare("DELETE FROM clubs WHERE id=?");
    $query->execute(array($id));

    $query = $db->prepare("DELETE FROM users_clubs WHERE club_id=?");
    $query->execute(array($id));
}

header("Location: club-list.php");
exit; // Make sure to exit to prevent further execution

?>