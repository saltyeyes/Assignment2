<?php
//Sorry 
include("../include/utils.php");
include("../include/connect.php");

$required_permissions = AccessLevels::PaidMember;

$isUpdating = isset($_REQUEST["id"]);
if ($isUpdating) {
	$sql = $dbh->prepare("SELECT * FROM events WHERE date $dateop :now ORDER BY date;");
    $sql->bindValue(":id", $_REQUEST['id']);
    $sql->execute();
    $row = $sql->fetch();
    if ($row == null) {
        redirect("/404");
    }
}

$page_title = "Event Form";

////////////////

$error = null;
if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    $sql;
    $dbh->beginTransaction();
	
	$iteration = 0; $oldName = "pending.png";
	if ($isUpdating) {
		$sql = $dbh->prepare("SELECT image FROM events WHERE id=:id");
		$sql->bindValue(":id", $_REQUEST['id']);
		$sql->execute();
		$oldName = $sql->fetch()['image'];
		$iteration = intval(explode("_", $oldName)[1]);
			
	if ($isUpdating) {
		$sql = $dbh->prepare("UPDATE events SET name=:name, info=:info, date=:date, location=:location, image=:image WHERE id=:id");
		$sql->bindValue(":id", $_REQUEST['id']);
	} 
	else {
		$sql = $dbh->prepare("INSERT INTO events (name, info, date, location, image, is_featured) VALUES (:name, :info, :date, :location, :image);");
	}

    $isFeatured = (isset($_REQUEST['feature']) ? 1 : 0);
    $sql->bindValue(":name", $_REQUEST['name']);
    $sql->bindValue(":info", $_REQUEST['info']);
	$sql->bindValue(":date", $_REQUEST['date']);
	$sql->bindValue(":location", $_REQUEST['location']);
	$sql->bindValue(":image", $oldName);
    $success = $sql->execute();

    $id = $isUpdating ? $_REQUEST['id'] : $dbh->lastInsertId();

    try {
        $newName = uploadImage($_FILES['image'], sprintf("%s_%s", $id, $iteration+1));
        $sql = $dbh->prepare("UPDATE events SET image=:image WHERE id=:id");
        $sql->bindValue(":id", $id);
        $sql->bindValue(":image", $newName);
        $sql->execute();
    } catch (Exception $e) {
        if (!$isUpdating or $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {
            $error = "Image upload error: " . $e->getMessage();
        }
    }
    if ($error == null) {
        $dbh->commit();
        redirect("/events/#a".$id);
    } else {
        $dbh->rollback();
    }
}
$name = ""; $info = ""; $date = ""; $location = ""; $image = ""; $feature = "";
if ($_SERVER['REQUEST_METHOD'] == 'GET' and $isUpdating) {
    $sql = $dbh->prepare("SELECT * from events where id = :id");
    $sql->bindValue(":id", $_REQUEST["id"]);
    $sql->execute();

    $row = $sql->fetch();
    $name = $row['name'];
    $info = $row['info'];
	$date = $row['date'];
	$location = $row['location'];
    $image = $row['image'];
}

/////////////////////

include("../include/header.php");
?>
<h2>Event Details:</h2>
<?php
    if ($error != null) {
        echo "<div class='messages'><div class='message error'>".$error."</div></div>";
    }
?>

<form id="insert" name="insert" method="post" action="<?php echo getLink('/events/'. ($isUpdating ? $_REQUEST['id'])?>" 
/unsure here
 enctype="multipart/form-data">
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
        	<td><label for="date">Date: </label></td>
            <td><textarea type="text" name="date" id="date"><?php echo $date; ?></textarea></td>
        </tr>
        <tr>
        	<td><label for="location">Location: </label></td>
            <td><textarea type="text" name="location" id="location"><?php echo $location; ?></textarea></td>
        </tr>
        <tr>
            <td><label for="image">Image: </label></td>
            <td><?php if ($isUpdating) { echo getImageElement($image); } ?><input name="image" type="file" id='image'/></td>
        </tr>
        <tr>
            <td><label for="add_artist">Add Artist: </label></td>
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

