<?php
header('Content-type: application/json');
require_once __DIR__."/session_control.php";

$club_id          = (int)@$_POST['id'];

if(empty(@$login_user)) {
    $response = ["response_message"=>"Lütfen önce üye girişi yapınız!", "status"=>"error"];
} else {
    $query = $db->prepare("INSERT INTO clubs_users SET club_id=?, user_id=?, user_status=?");
    $insert = $query->execute(array(@$club_id, $login_user, 2));
    $response = ["response_message"=>"Kulüp üyeliği başvurunuz alındı. Kulüp yöneticileri tarafından onaylandığında bilgilendirileceksiniz.", "status"=>"success"];
}

$json = json_encode(@$response);
echo $json;