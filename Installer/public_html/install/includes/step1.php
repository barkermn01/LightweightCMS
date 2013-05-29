<?php
if(!defined("LWCMS_INSTALLER")) 
	die("can't load outside of installer index.php");
?>
<form action="index.php?step=1" method="post">
Please provide the following information about your database.
<input type="hidden" name="step" value="1" />
<table>
<tr>
	<th>Host</th>
    <td><input type="text" name="db[host]" <?php if(isset($_SESSION['db']['host'])){ 
		echo "value=\"".$_SESSION['db']['host']."\""; 
	} ?> /></td>
</tr>
<tr>
	<th>Username</th>
    <td><input type="text" name="db[username]"<?php if(isset($_SESSION['db']['username'])){ 
		echo "value=\"".$_SESSION['db']['username']."\""; 
	} ?> /></td>
</tr>
<tr>
	<th>Password</th>
    <td><input type="Password" name="db[password]" /></td>
</tr>
<tr>
	<th>Database</th>
    <td><input type="text" name="db[database]" <?php if(isset($_SESSION['db']['password'])){ 
		echo "value=\"".$_SESSION['db']['database']."\""; 
	} ?> /></td>
</tr>
<tr>
	<td align="right" colspan="2">
    	<input type="submit" value="Next >" />
    </td>
</tr>
</table>
</form>