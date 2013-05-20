// JavaScript Document
window.systemHashChange = false;
function setupMenuButtons(items){
	$("#body .content .submenu").html("");
	for(var i = 0; i < items.length; i++){
		$("#body .content .submenu").append("<div class='btn' id='menu_"+i+"'><img src='"+items[i].image+"' /></div>");
		$("#menu_"+i).click(items[i].click);
	}
}

(function($){
	$(document).ready(function(e) {
        if(window.location.hash == "" || window.location.hash == "#" ){
			window.location.hash = "/home";
		}
		var loc = window.location.hash.replace("#", "");
		locParts = loc.split('/');
		$("#body .tabs .tab.active").removeClass("active");
		if(locParts.length > 0){
			$("#body .tabs .tab#tab_"+locParts[1]).addClass("active");
		}
		if($("#body .tabs .tab.active").length < 1){
			$("#body .tabs .tab#tab_plugins").addClass("active");
		}
		$("#body .tabs .tab").click(function(e){
			if(!$(this).hasClass("active")){
				document.location.hash = "/"+$(this).attr("id").replace("tab_", "");
				$("#body .tabs .tab.active").removeClass("active");
				$(this).addClass("active");
			}
		});
    });
	
	function handleForms(e){
		e.preventDefault();
		$(this).ajaxSubmit({
			success: handleResponse,
			beforeSubmit:function(arr, $form, options){
				parts = $form[0].action.split("/");
				window.systemHashChange = true;
				document.location.hash = "/"+$form[0].action.replace(parts[0]+"//"+parts[2]+"/"+parts[3]+"/"+parts[4]+"/", "");
				$("#body .content .overlay").css({
					"display":"block" 
				});
			}
		});
	}
	
	function handleResponse(data, textStatus, jqXHR){
		$("#body .content .addon_content").html(data);
		$("#body .content .overlay").css({"display":"none"});
		$('form').bind('submit', handleForms);
		$("#body .content .addon_content a").click(function(e){
			if($(this).attr("target") != "_blank"){
				e.preventDefault();
				$("#body .content .overlay").css({"display":"block"});
				downloadData($(this).attr("href"));
				window.systemHashChange = true;
				document.location.hash = $(this).attr("href").replace("/admin/plugin", "");
				return false;
			}
		})
		$('textarea.editor').ckeditor({
			width:641,
			bodyClass:"frmElem",
			toolbar: [
				{ name: 'document', items: [ 'Source', '-', 'NewPage','Preview', '-', 'Templates' ] },
				{ name: 'styles', items: [ 'Styles', 'Format' ] },
				{ 
					name: 'clipboard', 
					groups: [ 'clipboard', 'undo' ],
				 	items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ]
				},
				'/',
				{ 
					name: 'basicstyles', 
					groups: [ 'basicstyles', 'cleanup' ], 
					items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat' ] 
				},
				{ 
					name: 'paragraph', 
					groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], 
					items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] 
				},
				{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
			]
		});
	}
	
	function downloadData(address){
		
		for(var instanceName in CKEDITOR.instances) {
			try{
		   		CKEDITOR.instances[instanceName].destroy();
			}catch(e){
			}
		}
		setupMenuButtons([]);
		$.ajax({
			success: handleResponse,
			type:"GET",
			url:address
		})
	}
	
	downloadData("/admin/plugin"+document.location.hash.replace("#", ""));
	
	$(window).bind( 'hashchange', function(e) { 
		if(!window.systemHashChange){
			$("#body .content .overlay").css({
				"display":"block"
			});
			downloadData("/admin/plugin"+document.location.hash.replace("#", "")); 
		}else{
			window.systemHashChange = false;
		}
	});
	
})(jQuery);