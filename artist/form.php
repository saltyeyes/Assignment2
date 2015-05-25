<?php
include("../include/utils.php");
include("../include/connect.php");

$required_permissions = AccessLevels::PaidMember;

// include("../include/session.php");

//check if id exists

$isUpdating = isset($_REQUEST["id"]);
if ($isUpdating) {
    $sql = $dbh->prepare("SELECT id FROM artists WHERE id=:id");
    $sql->bindValue(":id", $_REQUEST['id']);
    $sql->execute();
    $row = $sql->fetch();
    if ($row == null) {
        redirect("/404");
    }
}

$page_title = ($isUpdating ? "Update" : "New") . " Artist";

////////////////////////////////////////////////

$error = null;
if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    $sql;
    $dbh->beginTransaction();

    // get old image name
    $iteration = 0; $oldName = "pending.png";
    if ($isUpdating) {
        $sql = $dbh->prepare("SELECT image FROM artists WHERE id=:id");
        $sql->bindValue(":id", $_REQUEST['id']);
        $sql->execute();
        $oldName = $sql->fetch()['image'];
        $iteration = intval(explode("_", $oldName)[1]);
    }

    if ($isUpdating) {
        $sql = $dbh->prepare("UPDATE artists SET name=:name, info=:info, image=:image, is_featured=:feature WHERE id=:id");
        $sql->bindValue(":id", $_REQUEST['id']);
    } else {
        $sql = $dbh->prepare("INSERT INTO artists (name, info, image, is_featured) VALUES (:name, :info, :image, :feature);");
    }
    $isFeatured = (isset($_REQUEST['feature']) ? 1 : 0);
    $sql->bindValue(":name", $_REQUEST['name']);
    $sql->bindValue(":image", $oldName);
    $sql->bindValue(":info", $_REQUEST['info']);
    $sql->bindValue(":feature", $isFeatured);
    $success = $sql->execute();

    $id = $isUpdating ? $_REQUEST['id'] : $dbh->lastInsertId();

    try {
        $newName = uploadImage($_FILES['image'], sprintf("%s_%s", $id, $iteration+1));
        $sql = $dbh->prepare("UPDATE artists SET image=:image WHERE id=:id");
        $sql->bindValue(":id", $id);
        $sql->bindValue(":image", $newName);
        $sql->execute();
    } catch (Exception $e) {
        if (!$isUpdating or $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {
            $error = "Image upload error: " . $e->getMessage();
        }
    }

    if ($error == null and $isFeatured) {
        $sql = $dbh->prepare("UPDATE artists SET is_featured=0 WHERE id!=:id AND is_featured=1;");
        $sql->bindValue(":id", $id);
        $sql->execute();
    }
    if ($error == null) {
        $dbh->commit();
        // print_r($dbh->errorInfo()); die();
        redirect("/artists/#a".$id);
    } else {
        $dbh->rollback();
    }
}
$name = ""; $info = ""; $image = ""; $feature = "";
if ($_SERVER['REQUEST_METHOD'] == 'GET' and $isUpdating) {
    $sql = $dbh->prepare("SELECT * from artists where id = :id");
    $sql->bindValue(":id", $_REQUEST["id"]);
    $sql->execute();

    $row = $sql->fetch();
    $name = $row['name'];
    $info = $row['info'];
    $image = $row['image'];
    $feature = $row['is_featured'];
}

include("../include/header.php");
?>
<h2><?php echo $isUpdating ? "Update" : "New" ?> Artist's details:</h2>
<?php
    if ($error != null) {
        echo "<div class='messages'><div class='message error'>".$error."</div></div>";
    }
?>
<form id="insert" name="insert" method="post" action="<?php echo getLink('/artists/'. ($isUpdating ? $_REQUEST['id'] . '/edit' : 'new') . '/') ?>" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
    <table>
        <tr>    
            <td><label for="name">Name: </label></td>
            <td><input type="text" name="name" id="name" value="<?php echo $name; ?>"></td>
        </tr>
        <tr>
            <td><label for="info">Info: </label></td>
            <td><textarea type="text" name="info" id="info"><?php echo $info; ?></textarea></td>
        </tr>
        <tr>
            <td><label for="image">Image: </label></td>
            <td><?php if ($isUpdating) { echo getImageElement($image); } ?><input name="image" type="file" id='image'/></td>
        </tr>
        <tr>
            <td><input type="checkbox" name="feature" id='feature' <?php echo $feature ? "checked" : ""; ?>></td>
            <td><label for='feature'>Feature this artist</label></td>
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