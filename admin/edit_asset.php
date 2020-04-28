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


			  $row['fs_growth_steady'] == '0' ? $steady = '' : $steady = $row['fs_growth_steady'];
			  $row['fs_growth_sensible'] == '0' ? $sensible = '' : $sensible = $row['fs_growth_sensible'];
			  $row['fs_growth_serious'] == '0' ? $serious = '' : $serious = $row['fs_growth_serious'];


			  $confirmed_by = $row['confirmed_by'];
			  $confirmed_date = $row['confirmed_date'];
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
                        <input type="color" id="asset_color" name="asset_color" value="<?= $asset_color;?>" style="height:4rem;">
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
            <input type="text" id="cat_new" name="cat_new"><input type="hidden" id="cat_ids" name="cat_ids" value="<?=substr($idString, 0, -1);?>">
        </div><!--cats-->
    </div>

    <div class="control">
        <h3 class="heading heading__2">Asset Actions</h3>
        <div id="fund_actions">
            <input type="submit" class="button button__raised" value="Save Changes">
        </div>
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
