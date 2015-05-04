<?php
include("include/utils.php");
include("include/connect.php");

if (!isset($_REQUEST['id'])) {
    redirect("404.php");
}
$sql = $dbh->prepare("SELECT * from artists where id=:id");
$sql->bindValue(":id", $_REQUEST["id"]);
$sql->execute();
$row = $sql->fetch();

if($row == null) {
    redirect("404.php");
}

$name = $row['name'];
$info = $row['info'];
$image = $row['image'];
$feature = $row['is_featured'];

$page_title = "Artist - " . $name;

////////////////////////////////////////////////


include("include/header.php");
?>
<h2><?php echo $name; ?></h2>
<form class="artistDisplay" name="artist" method="post" action="artistList.php">
<?php echo "<input type='hidden' name='id' value='$row[id]' />"; ?>
    <table>
        <tr>
            <td rowspan=2><?php echo getImageElement($row['image']); ?></td>
        </tr>
        <tr>
            <td><p><?php echo str_replace("\n", "<br/>", $row['info']); ?></p></td>
        </tr>
        <tr>
            <td><input type="submit" name="submit" value="Update" class="updateButton"></td>
            <td><input type="submit" name="submit" value="Delete" class="deleteButton"></td>
        </tr>
    </table>
</form>

<?php
    include("include/footer.php");
?>