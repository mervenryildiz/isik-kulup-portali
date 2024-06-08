<?php
require_once __DIR__ . "/session_control.php";
if(empty(@$login_user) || @$login_user < 1) {
    header("Location: /error.php");
    die();
}
$page_title = "Duyurular";
require_once __DIR__."/header.php";
?>


<div class="title-box">
    <div class="container">
        <h1>Duyurular</h1>
    </div>
</div>


<main role="main">
    <div class="album py-5 bg-light">
        <div class="container">
            <?php
            $query = $db->prepare("SELECT
                announcements.id, 
                announcements.a_title, 
                announcements.a_content, 
                announcements.created_date
            FROM
                announcements
                LEFT JOIN
                clubs_announcements
                ON 
                    announcements.id = clubs_announcements.announcement_id
                LEFT JOIN
                clubs
                ON 
                    clubs_announcements.club_id = clubs.id
                LEFT JOIN
                clubs_users
                ON 
                    clubs.id = clubs_users.club_id
                    WHERE clubs_users.user_id = ? && clubs_users.user_status = 1");
            $query->execute([$login_user]);
            $row_count = $query->rowCount();
            if($row_count > 0) {
            ?>
            <ol class="list-group">
                <?php
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $a_user = $db->prepare("SELECT id FROM announcements_users WHERE user_id = ? && announcement_id = ?");
                    $a_user->execute([$login_user, @$row['id']]);
                    $a_user_count = $a_user->rowCount();
                ?>
                <li class="list-group-item d-flex justify-content-between align-items-start pt-4 pb-4">
                    <div class="ms-2 me-auto">
                        <?php if($a_user_count == 0) { ?>
                            <div class="fw-bold">
                                <a href="/user-announcement-detail.php?id=<?php echo @$row['id']; ?>">
                                <?php echo @$row['a_title']; ?>
                                </a>
                            </div>

                        <?php } else { ?>
                            <div><a href="/user-announcement-detail.php?id=<?php echo @$row['id']; ?>">
                                    <?php echo @$row['a_title']; ?>
                                </a></div>
                        <?php } ?>
                        <div>
                            <?php echo date("d.m.Y H:i", strtotime(@$row['created_date'])); ?> |
                            <span style="color:rgba(33, 37, 41, 0.5)">
                            <?php
                                echo substr(htmlspecialchars(@$row['a_content']), 0,50)."...";
                            ?>
                                </span>
                        </div>
                    </div>
                    <?php if($a_user_count == 0) { ?>
                    <span class="badge text-bg-danger rounded-pill">&nbsp;</span>
                    <?php } ?>
                </li>
                <?php } ?>
            </ol>
            <?php } ?>
        </div>
    </div>
</main>

<?php require_once __DIR__."/footer.php"; ?>

</body>
</html>
