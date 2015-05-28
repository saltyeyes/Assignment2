<?php
/// I gave up
include("../include/utils.php");
include("../include/connect.php");

$required_permissions = AccessLevels::PaidMember;

$isUpdating = isset($_REQUEST["id"]);
if ($isUpdating) {
    $sql = $dbh->prepare("SELECT id FROM notices WHERE id=:id");
    $sql->bindValue(":id", $_REQUEST['id']);
    $sql->execute();
    $row = $sql->fetch();
    if ($row == null) {
        redirect("/404");
    }
}

$page_title = "bulletin board form";

include("../include/header.php");
?>

<h2>Bulletin Board:</h2>

<form id="insert" name="insert" method="post" action="<?php echo getLink('/notices/'. ($isUpdating ? $_REQUEST['id'] . '/edit' : 'new') . '/') ?>" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
    <table>
        <tr>
            <td><label for="content">Notice: </label></td>
            <td><textarea type="text" name="content" id="content"><?php echo $info; ?></textarea></td>
        </tr>
        <tr>
            <?php if($isUpdating) : ?><input type="hidden" name="id" value="<?php echo $_REQUEST["id"]; ?>" /><?php endif; ?>
            <td colspan="2"><input type="submit" name="submit" id="submit" value="<?php echo $isUpdating ? 'Update' : 'Insert'?>"></td>
        </tr>
    </table>
</form>

<?php
    include("../include/footer.php");
?>