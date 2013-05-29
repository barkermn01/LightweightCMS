<?php
if(isset($_SESSION['step1'])){
	if($_SESSION['step1']){
		?>
		<div class="green">Database Settings Tested and Working!</div>
		<?php
	}else{
		?>
		<div class="red">Database Settings Tested and Failed Please correct the settings below!</div>
        <?php
	}
}
if(isset($_SESSION['step2'])){
	if($_SESSION['step2']){
		?>
		<div class="green">User and Version to install saved!</div>
		<?php
	}else{
		?>
		<div class="red">User Details are not suitable!</div>
        <?php
	}
}
?>