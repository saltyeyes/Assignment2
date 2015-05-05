<?php
include("../include/utils.php");
include("../include/connect.php");

$page_title = "Artists";

////////////////////////////////////////////////

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    if ($_REQUEST['submit'] == "Update") {
        redirect("/artists/".$_REQUEST['id']."/edit/");
    } else if ($_REQUEST['submit'] == "Delete") {
        $sql = $dbh->prepare("DELETE FROM artists WHERE id=:id;");
        $sql->bindValue(":id", $_REQUEST['id']);
        $return = $sql->execute();
    }
}
$sql = $dbh->prepare("SELECT * FROM artists ORDER BY is_featured DESC;");
$sql->execute();
// print_r($dbh->errorInfo());
include("../include/header.php");
?>
<h2>Current Artists:</h2>
<?php

foreach ($sql->fetchAll() as $row) {
    ?>
    <form class="artistSection" name="artist" method="post" action="<?php echo getLink('/artists/'); ?>">
        <?php
        echo "<input type='hidden' name='id' value='$row[id]' />";
        echo "<fieldset" . ($row['is_featured'] ? " class='featured'" : "") . "><a name='a$row[id]'></a>" ?>
        <table>
            <tr>
                <td rowspan=2><?php echo getImageElement($row['image']); ?></td>
                <td><b><?php echo sprintf('<a href="%s">%s</a>', getLink("/artists/".$row['id']), $row['name']); ?></b></td>
            </tr>
            <tr>
                <td><p><?php echo array_shift((explode("\n", $row['info']))); ?></p></td>
            </tr>
            <tr>
                <td><input type="submit" name="submit" value="Update" class="updateButton"></td>
                <td><input type="submit" name="submit" value="Delete" class="deleteButton"></td>
            </tr>
        </table>
    </fieldset>
</form>
<?php
}
$dbh = null;
include("../include/footer.php");
?>