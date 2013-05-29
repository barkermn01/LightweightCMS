<?php
if(!defined("LWCMS_INSTALLER")) 
	die("can't load outside of installer index.php");
?>
Welcome to the Lightweight CMS Installer Please fillout the forms below<br />
<form action="" method="post">
Please provide the following information about your Installation of LightweightCMS.
<input type="hidden" name="step" value="2" />
<table>
<tr>
	<th>Admin Username</th>
    <td><input type="text" name="user[username]" <?php if(isset($_SESSION['user']['username'])){ 
		echo "value=\"".$_SESSION['user']['username']."\""; } ?> /></td>
</tr>
<tr>
	<th>Admin Password</th>
    <td><input type="password" name="user[password]"<?php if(isset($_SESSION['user']['password'])){ 
		echo "value=\"".$_SESSION['user']['password']."\""; } ?> /></td>
</tr>
<tr>
	<th>Admin Email</th>
    <td><input type="text" name="user[email]"<?php if(isset($_SESSION['user']['email'])){ 
		echo "value=\"".$_SESSION['user']['email']."\""; } ?> /></td>
</tr>
<tr>
	<th>Setup Mode</th>
    <td><select name="version">
    	<option value="Live" selected="selected">Live</option>
        <option value="Beta">Beta</option>
        <option value="Alpha">Alpha</option>
    </select></td>
</tr>
<tr>
	<td align="right" colspan="2">
    	<input type="submit" value="Next >" />
    </td>
</tr>
</table>
</form>