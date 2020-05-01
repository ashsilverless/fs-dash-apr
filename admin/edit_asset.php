<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
$asset_id = $_GET['id'];

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT *  FROM `tbl_fs_assets` where id = $asset_id;";

    $result = $conn->prepare($query);
    $result->execute();

          // Parse returned data
          while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			  $asset_name = $row['fs_asset_name'];
			  $asset_narrative = $row['fs_asset_narrative'];
              $asset_color = $row['asset_color'];

			  $row['fs_growth_steady'] == '0' ? $steady = '' : $steady = $row['fs_growth_steady'];
			  $row['fs_growth_sensible'] == '0' ? $sensible = '' : $sensible = $row['fs_growth_sensible'];
			  $row['fs_growth_serious'] == '0' ? $serious = '' : $serious = $row['fs_growth_serious'];

			  $confirmed_by = $row['confirmed_by'];
			  $confirmed_date = $row['confirmed_date']= date('d M Y');
			  $cat_id = $row['cat_id'];
		  }

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}


try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT *  FROM `tbl_fs_categories` where bl_live = 1;";

    $result = $conn->prepare($query);
    $result->execute();

          // Parse returned data
          while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			  $cats[] = $row;
		  }

	//    Get the categories associated with this asset   //
	$query = "SELECT *  FROM `tbl_fs_asset_cats` where fs_asset_id = $asset_id;";

    $result = $conn->prepare($query);
    $result->execute();

          // Parse returned data
          while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			  $catArray[] = $row['fs_cat_id'];
		  }

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}?>

<form action="editasset.php?id=<?=$asset_id;?>" method="post" id="editasset" name="editasset" class="asset-form">
    <div class="content">
        <h3 class="heading heading__2">Asset Details</h3>

        <div class="details">
            <label>Asset Name</label>
            <input type="text" id="asset_name" name="asset_name" value="<?= $asset_name;?>" class="mb1">
            <label>Narrative</label>
            <textarea name="asset_narrative" id="asset_narrative" class="mb2"><?= $asset_narrative;?></textarea>
            <h4 class="heading heading__4 mb1">Growth</h4>
            <div class="row">
                <div class="col-4">
                    <label>Steady</label>
                    <input type="text" name="growth_steady" id="growth_steady" class="calculator-input" onkeypress="return event.charCode >= 46 && event.charCode <= 57" size="5"  value="<?= $steady;?>">
                </div>
                <div class="col-4">
                    <label>Sensible</label>
                    <input type="text" name="growth_sensible" id="growth_sensible" class="calculator-input" onkeypress="return event.charCode >= 46 && event.charCode <= 57" size="5" value="<?= $sensible;?>">
                </div>
                <div class="col-4">
                    <label>Serious</label>
                    <input type="text" name="growth_serious" id="growth_serious" class="calculator-input" onkeypress="return event.charCode >= 46 && event.charCode <= 57" size="5" value="<?= $serious;?>">
                </div>
            </div><!--row-->
        </div><!--details-->

        <div class="categories">
            <label>Categories</label>
            <div class="inner">
                <?php $idString = '';
                for($a=0;$a<count($cats);$a++){
                    $idString .= $cats[$a]['id'].'|';
                    $cats[$a]['id']== $cat_id ? $thisCheck = 'checked = "checked"' : $thisCheck = '';?>
                <div class="radio-item">
                    <input class="star-marker" type="radio" name="cat" value="<?=$cats[$a]['id'];?>" id="cat<?=$cats[$a]['id'];?>" <?=$thisCheck;?>>
                    <?php define('__ROOT__', dirname(dirname(__FILE__)));
                    include(__ROOT__.'/admin/images/star.php'); ?>
                    <label for="cat<?=$cats[$a]['id'];?>"><?=$cats[$a]['cat_name'];?></label>
                </div><!--radio-->
                <a href="#" data-href="deletecat.php?id=<?=$cats[$a]['id'];?>" data-toggle="modal" data-target="#confirm-catdelete" class=" button button__delete elcat"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 17.69 17.69"><defs><style>.cls-1{fill:#1d1d1b;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M0,14.83v-12A2.55,2.55,0,0,1,2.88,0H14.8a2.56,2.56,0,0,1,2.89,2.86v12a2.56,2.56,0,0,1-2.89,2.86H2.88A2.55,2.55,0,0,1,0,14.83Zm14.78,1.64a1.52,1.52,0,0,0,1.69-1.7V2.92a1.53,1.53,0,0,0-1.69-1.71H2.9A1.53,1.53,0,0,0,1.21,2.92V14.77a1.51,1.51,0,0,0,1.69,1.7ZM5.06,11.79,8,8.85,5.06,5.9a.58.58,0,0,1,.42-1,.58.58,0,0,1,.41.17L8.84,8l3-3a.54.54,0,0,1,.41-.18.59.59,0,0,1,.59.6.63.63,0,0,1-.17.42l-3,2.95,2.94,2.93a.57.57,0,0,1,.17.42.59.59,0,0,1-1,.43L8.84,9.69,5.9,12.63a.59.59,0,0,1-.42.17.6.6,0,0,1-.6-.6A.58.58,0,0,1,5.06,11.79Z"/></g></g></svg></a>
                <?php } ?>
            </div><!--inner-->
            <label>Insert In New Category</label>
            <input type="text" id="cat_new" class="mb1"
            name="cat_new"><input type="hidden" id="cat_ids" name="cat_ids" value="<?=substr($idString, 0, -1);?>">
            <label>Asset Colour</label>
            <div class="color-panel-wrapper">
                <input type="color" id="asset_color" class="color-picker" name="asset_color" value="<?= $asset_color;?>">
            </div>

        </div><!--cats-->
    </div>

    <div class="control">
        <h3 class="heading heading__2">Asset Actions</h3>
        <p class="mb1">Last edited by <?= $confirmed_by; ?> on <?= $confirmed_date; ?></p>
        <button type="submit" class="button button__raised mb1">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 viewBox="0 0 21.8 21.8" style="enable-background:new 0 0 21.8 21.8;" xml:space="preserve">
                <style type="text/css">
                    .st0{fill:#96E8C4;}
                </style>
                <g id="Layer_2_1_">
                    <g id="Layer_1-2">
                        <path class="st0" d="M7.7,19.4c-0.1-0.1-0.3-0.2-0.5-0.2H4.9c-1.6,0-2.3-0.7-2.3-2.3v-2.3c0-0.2-0.1-0.4-0.2-0.5l-1.6-1.6
                            c-0.9-0.7-1.1-1.9-0.4-2.8c0.1-0.1,0.2-0.3,0.4-0.4l1.6-1.6c0.1-0.1,0.2-0.3,0.2-0.5V4.9c0-1.6,0.7-2.3,2.3-2.3h2.3
                            c0.2,0,0.4-0.1,0.5-0.2l1.6-1.6c0.6-0.9,1.8-1.1,2.7-0.5c0.2,0.1,0.4,0.3,0.5,0.5l1.6,1.6c0.1,0.1,0.3,0.2,0.5,0.2h2.3
                            c1.6,0,2.3,0.7,2.3,2.3v2.2c0,0.2,0.1,0.4,0.2,0.5L21,9.3c0.9,0.7,1.1,1.9,0.4,2.8c-0.1,0.1-0.2,0.3-0.4,0.4l-1.6,1.6
                            c-0.2,0.1-0.2,0.3-0.2,0.5v2.3c0,1.6-0.7,2.3-2.3,2.3h-2.3c-0.2,0-0.4,0.1-0.5,0.2L12.5,21c-0.6,0.9-1.8,1.1-2.7,0.5
                            c-0.2-0.1-0.3-0.3-0.5-0.5L7.7,19.4z M11.7,20.1l1.9-1.9c0.2-0.2,0.4-0.3,0.7-0.3H17c0.9,0,1.1-0.2,1.1-1.1v-2.7
                            c0-0.3,0.1-0.5,0.3-0.7l1.9-1.9c0.6-0.6,0.6-0.9,0-1.5l-1.9-1.9C18.1,8.1,18,7.8,18,7.6V4.9c0-0.9-0.2-1.1-1.1-1.1h-2.7
                            c-0.3,0-0.5-0.1-0.7-0.3l-1.9-1.9C11,1,10.8,1,10.1,1.7L8.3,3.5C8.1,3.7,7.8,3.9,7.6,3.8H4.9C4,3.8,3.8,4,3.8,4.9v2.7
                            c0,0.3-0.1,0.5-0.3,0.7l-1.9,1.9C1,10.8,1,11,1.7,11.7l1.9,1.9c0.2,0.2,0.3,0.4,0.3,0.7v2.7C3.8,17.8,4,18,4.9,18h2.7
                            c0.3,0,0.5,0.1,0.7,0.3l1.9,1.9C10.8,20.8,11,20.8,11.7,20.1L11.7,20.1z M8.9,15.4l-3.2-3.6c-0.1-0.1-0.2-0.3-0.2-0.4
                            c0-0.4,0.3-0.6,0.7-0.6c0.2,0,0.3,0.1,0.4,0.2l2.7,3l5.1-7.2c0.2-0.3,0.6-0.4,0.9-0.2c0.2,0.1,0.3,0.3,0.3,0.5
                            c0,0.1-0.1,0.3-0.1,0.4l-5.6,7.9c-0.1,0.2-0.3,0.2-0.5,0.2C9.2,15.5,9,15.5,8.9,15.4L8.9,15.4z"/>
                    </g>
                </g>
            </svg>
            Save Changes
      </button>
        <a href="" class="button button__raised button__inline button__danger"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21.82 21.82"><defs><style>.cls-1{fill:#1d1d1b;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M7.71,19.39a.71.71,0,0,0-.54-.22H4.91c-1.57,0-2.26-.69-2.26-2.26V14.65a.67.67,0,0,0-.23-.53L.83,12.5a2,2,0,0,1,0-3.19l1.59-1.6a.72.72,0,0,0,.23-.54V4.92c0-1.59.69-2.27,2.26-2.27H7.17a.73.73,0,0,0,.54-.22L9.31.83a1.94,1.94,0,0,1,3.19,0l1.61,1.6a.71.71,0,0,0,.54.22h2.26c1.57,0,2.26.69,2.26,2.27V7.17a.72.72,0,0,0,.23.54L21,9.31a2,2,0,0,1,0,3.19L19.4,14.12a.67.67,0,0,0-.23.53v2.26c0,1.57-.69,2.26-2.26,2.26H14.65a.71.71,0,0,0-.54.22L12.5,21a1.94,1.94,0,0,1-3.18,0Zm4,.76,1.87-1.88a.89.89,0,0,1,.7-.29h2.67c.89,0,1.07-.17,1.07-1.07V14.23a1,1,0,0,1,.28-.69l1.89-1.87c.63-.64.63-.87,0-1.52L18.26,8.28a.94.94,0,0,1-.28-.7V4.92c0-.9-.18-1.08-1.07-1.08H14.24a.89.89,0,0,1-.7-.29L11.67,1.67C11,1,10.79,1,10.15,1.67L8.28,3.55a.89.89,0,0,1-.7.29H4.91C4,3.84,3.84,4,3.84,4.92V7.58a.94.94,0,0,1-.28.7L1.67,10.15c-.63.65-.63.88,0,1.52l1.89,1.87a1,1,0,0,1,.28.69v2.68c0,.9.17,1.07,1.07,1.07H7.58a.89.89,0,0,1,.7.29l1.87,1.88C10.79,20.79,11,20.79,11.67,20.15ZM6.89,14.38a.55.55,0,0,1,.18-.44l3-3-3-3a.54.54,0,0,1-.18-.44A.6.6,0,0,1,7.5,7a.54.54,0,0,1,.43.19l3,3,3-3A.57.57,0,0,1,14.32,7a.6.6,0,0,1,.61.6.58.58,0,0,1-.18.43l-3,3,3,3a.64.64,0,0,1,.19.45.61.61,0,0,1-.61.61.58.58,0,0,1-.45-.2l-3-3L8,14.79a.57.57,0,0,1-.45.2A.61.61,0,0,1,6.89,14.38Z"/></g></g></svg>Cancel</a>
    </div>

</form>
    <script>
    feather.replace();
	function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    </script>
  </body>
</html>
