<?php
require_once __DIR__ . "/session_control.php";
if(empty(@$login_user) || @$login_user < 1) {
    header("Location: /error.php");
    die();
}

$get_id = (int)@$_GET['id'];
$query = $db->prepare("SELECT
	announcements.id, 
	announcements.a_title, 
	announcements.a_content, 
	announcements.created_date
FROM
	announcements WHERE id = ?");
$query->execute(array(@$get_id));

$get = $query->fetch(PDO::FETCH_ASSOC);

$an_query = $db->prepare("SELECT id FROM announcements_users WHERE user_id = ? && announcement_id = ?");
$an_query->execute(array($login_user, $get_id));
$an_count = $an_query->rowCount();
if($an_count == 0) {
    $insert = $db->prepare("INSERT INTO announcements_users SET user_id = ?, announcement_id = ?");
    $insert->execute(array($login_user, $get_id));
}

$page_title = @$get['a_title'];
require_once __DIR__."/header.php";
?>


<main role="main">
    <div class="album py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-body">
                            <h1><?php echo @$get['a_title']; ?></h1>
                            <p><?php echo @$get['a_content']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__."/footer.php"; ?>

</body>
</html>
