<?php
if(!defined("LWCMS_INSTALLER")) 
	die("can't load outside of installer index.php");
?>
<div id="output">
	<button id="install">Start Install</button>
</div>
<div id="holder">
	<button id="finish" disabled="disabled">Finish!</button>
</div>
<script type="text/javascript">
var btn = document.getElementById("install");
btn.addEventListener("click", function(){
	var ajax = new XMLHttpRequest();
	ajax.open("GET", "index.php?step=install", true);
	ajax.setRequestHeader("Content-type","application/octet-stream");
	ajax.onreadystatechange = function(){
		var out = document.getElementById("output");
		if(ajax.readyState == 3){
			out.innerHTML = ajax.responseText;
		}else if(ajax.readyState == 4){
			out.innerHTML = ajax.responseText;
			document.getElementById("finish").disabled = false;
		}
	}
	ajax.send();
}, false);
var fin = document.getElementById("finish");
fin.addEventListener("click", function(){
	if(!fin.disabled){
		document.location = "index.php?step=finished";
	}
}, false);
</script>