<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$initialDate = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
?>

<form action="addtheme.php" method="post" id="addtheme" name="addtheme" class="asset-form">

    <div class="content">
        <h3 class="heading heading__2">New Theme</h3>
        <div class="details">
            <label>Theme Name</label>
            <input type="text" id="theme_title" name="theme_title"></p>
            <label>Narrative</label>
            <textarea name="theme_narrative" id="theme_narrative"></textarea>
            <h4 class="heading heading__4">Theme Type</h4>
            <div class="row">
                <div class="col-4">
                    <label>Steady</label>
                    <div class="radio-item">
                        <input class="star-marker" name="theme_type_steady" type="checkbox" id="theme_type_steady" value="steady" checked="checked">
                        <?php define('__ROOT__', dirname(dirname(__FILE__)));
                        include(__ROOT__.'/admin/images/star.php'); ?>
                    </div><!--radio-->
                </div>
                <div class="col-4">
                    <label>Serious</label>
                    <div class="radio-item">
                        <input class="star-marker" type="checkbox" name="theme_type_serious" value="serious" id="theme_type_serious" checked="checked">
                        <?php define('__ROOT__', dirname(dirname(__FILE__)));
                        include(__ROOT__.'/admin/images/star.php'); ?>
                    </div>
                </div>
                <div class="col-4">
                    <label>Sensible</label>
                    <div class="radio-item">
                        <input class="star-marker" type="checkbox" name="theme_type_sensible" value="sensible" id="theme_type_sensible">
                        <?php define('__ROOT__', dirname(dirname(__FILE__)));
                        include(__ROOT__.'/admin/images/star.php'); ?>
                    </div>
                </div>
            </div><!--row-->

        </div><!--details-->
        <div class="categories">
            <h4>Upload Icon</h4>
            				<div id="fundfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="fundcontainer"><a id="pickfund" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Choose File]</a></div><input name="icon_file" type="hidden" id="icon_file" value="<?=$theme_icon_file;?>"><div id="theme_icon"><?=$theme_icon;?></div>


        </div><!--cats-->
    </div><!--content-->

    <div class="control">
        <h3 class="heading heading__2">Theme Actions</h3>
        <p>Last edited on 14 Jan by James Barton</p>
        <input type="submit" class="btn btn-grey" value="Add Theme">
    </div>





</form>

    <script>

	function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

 // Fund File Upload
var uploader = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'pickfund',
	container: document.getElementById('fundcontainer'),
	url : 'iconupload.php',
	flash_swf_url : 'js/plupload/Moxie.swf',
	silverlight_xap_url : '.js/plupload/Moxie.xap',
	unique_names : true,
	filters : {
		max_file_size : '10mb',
		mime_types: [
			{title : "Image files", extensions : "jpg,jpeg,gif,svg"}
		]
	},

	init: {
		PostInit: function() {
			document.getElementById('fundfilelist').innerHTML = '';
		},

		FilesAdded: function(up, files) {
			for (var i in files) {
				$( "#fund_file" ).val(files[i].name);
			}
			uploader.start();
		},

		UploadProgress: function(up, file) {
			//
		},

        FileUploaded: function(up, file, info) {
            var myData;
				try {
					myData = eval(info.response);
				} catch(err) {
					myData = eval('(' + info.response + ')');
				}

		   $( "#icon_file" ).val(myData.result);
		   $( "#theme_icon" ).html('<img src="../icons_folder/'+myData.result+'" style="margin-right:10px; max-width:80px;">');
        },


		Error: function(up, err) {
			console.log("\nError #" + err.code + ": " + err.message);
		}
	}
});

uploader.init();




    </script>
  </body>
</html>
