<?php
include_once("../include/utils.php");
include_once("../include/connect.php");

$page_title = "Register";
$required_permissions = AccessLevels::Unregistered;

include("../include/session.php");

$isUpdating = $is_logged_in;

////////////////////////////////////////////////
$error = null;
$fname = $lname = $address = $phone_day = $phone_ah = $phone_mobile = $email = "";
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
<h2><?php echo $isUpdating ? "Update Details" : "Register" ?>:</h2>
<?php
    if ($error != null) {
        echo "<div class='messages'><div class='message error'>".$error."</div></div>";
    }
?>
<form id="register" name="register" method="post" action="<?php getLink('/members/register/') ?>">
    <table>
        <tr>    
            <td><label for="fname">Email: </label></td>
            <td><input type="text" name="email" id="email" value="<?php echo $email; ?>"></td>
        </tr>
        <tr>    
            <td><label for="fname">First Name: </label></td>
            <td><input type="text" name="fname" id="fname" value="<?php echo $fname; ?>"></td>
        </tr>
        <tr>    
            <td><label for="lname">Last Name: </label></td>
            <td><input type="text" name="lname" id="lname" value="<?php echo $lname; ?>"></td>
        </tr>
        <tr class='rule'>
            <td><label for="address">Address: </label></td>
            <td><textarea type="text" name="address" id="address"><?php echo $address; ?></textarea></td>
        </tr>
        <tr>
            <td><label for="phone_day">Phone (Day): </label></td>
            <td><input type="number" name="phone_day" id="phone_day" value="<?php echo $phone_day; ?>"></td>
        </tr>
        <tr>
            <td><label for="phone_ah">Phone (After Hours): </label></td>
            <td><input type="number" name="phone_ah" id="phone_ah" value="<?php echo $phone_ah; ?>"></td>
        </tr>
        <tr>
            <td><label for="phone_mobile">Phone (Mobile): </label></td>
            <td><input type="number" name="phone_mobile" id="phone_mobile" value="<?php echo $phone_mobile; ?>"></td>
        </tr>
        <tr class='rule'>
            <td colspan=2><small>You must provide at least one contact phone number.</small></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" name="submit" id="submit" value="<?php echo $isUpdating ? 'Update' : 'Register'?>"></td>
        </tr>
    </table>
</form>

<?php
    include("../include/footer.php");
?>