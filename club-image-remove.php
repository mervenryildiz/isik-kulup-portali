<?php
require_once __DIR__."/session_control.php";
require_once __DIR__."/connection.php";
$get_id = (int)@$_POST['id'];

if($get_id > 0) {
    $query = $db->prepare("SELECT logo FROM clubs WHERE id = ?");
    $query->execute(array(@$get_id));
    $get = $query->fetch(PDO::FETCH_ASSOC);

    if(!empty(@$get['logo'])) {
        unlink("../uploads/files/600-600/".@$get['logo']);
        unlink("../uploads/files/300-300/".@$get['logo']);
        unlink("../uploads/files/200-200/".@$get['logo']);
    }

    $query = $db->prepare("
        UPDATE clubs SET 
        logo=?
        WHERE id=?
    ");

    $update = $query->execute(array(NULL, @$get_id));

}