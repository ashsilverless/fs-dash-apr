<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$peer_id = $_GET['id'];

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	$query = "SELECT * FROM `tbl_fs_peers` where id = $peer_id;";

    $result = $conn->prepare($query); 
    $result->execute();

          // Parse returned data
          while($row = $result->fetch(PDO::FETCH_ASSOC)) {  
			  $fs_peer_name = $row['fs_peer_name'];
			  $fs_peer_return = $row['fs_peer_return'];
			  $fs_peer_volatility = $row['fs_peer_volatility'];
			  $fs_peer_color = str_replace("#","",$row['fs_peer_color']);
			  $row['fs_trend_line'] == '1' ? $fs_trend_line = 'checked = "checked"' : $fs_trend_line = "";
		  }

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}
?>
<!-- Colour Picker -->
<script src="js/jscolor.js"></script>

<form action="editpeer.php?id=<?=$peer_id;?>" method="post" id="editpeer" name="editpeer">
	<table width="100%" border="0">
      <tbody>
        <tr>
          <td colspan="2"><p>Peer Group Name<br> <input type="text" id="fs_peer_name" name="fs_peer_name" style="width:90%;" value="<?=$fs_peer_name;?>"></p></td>
          </tr>
        <tr>
          <td><p>Return<br><input type="text" name="fs_peer_return" id="fs_peer_return" class="calculator-input" onkeypress="return event.charCode >= 46 && event.charCode <= 57" size="5" value="<?=$fs_peer_return;?>"></p>
            <p>Trend Line<br><input type="checkbox" name="fs_trend_line" id="fs_trend_line" <?=$fs_trend_line;?>><label for="fs_trend_line">Yes </label></p></td>
          <td><p>Volatility<br><input type="text" name="fs_peer_volatility" id="fs_peer_volatility" class="calculator-input" onkeypress="return event.charCode >= 46 && event.charCode <= 57" size="5" value="<?=$fs_peer_volatility;?>"></p>
            <p>Trend Colour<br><input size="7" id="fs_peer_color" name="fs_peer_color" class="jscolor {hash:true}" value="<?=$fs_peer_color?>"></p>	</td>
        </tr>

        <tr>
          <td colspan="2"><input type="submit" style="font-size:0.8em;" class="btn btn-grey" value="Save Changes" <?php if($_SESSION['agent_level']< '2'){ ?>disabled<?php }?>></td>
          </tr>
      </tbody>
    </table>
</form>
