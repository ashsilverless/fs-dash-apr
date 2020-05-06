<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$peer_id = $_GET['id'];

?>
<!-- Colour Picker -->
<script src="js/jscolor.js"></script>

<form action="addpeer.php" method="post" id="addpeer" name="addpeer">
	<table width="100%" border="0">
      <tbody>
        <tr>
          <td colspan="2"><p>Peer Group Name<br> <input type="text" id="fs_peer_name" name="fs_peer_name" style="width:90%;" value=""></p></td>
          </tr>
        <tr>
          <td><p>Return<br><input type="text" name="fs_peer_return" id="fs_peer_return" class="calculator-input" onkeypress="return event.charCode >= 46 && event.charCode <= 57" size="5" value="0"></p>
            <p>Trend Line<br><input type="checkbox" name="fs_trend_line" id="fs_trend_line"><label for="fs_trend_line">Yes </label></p></td>
          <td><p>Volatility<br><input type="text" name="fs_peer_volatility" id="fs_peer_volatility" class="calculator-input" onkeypress="return event.charCode >= 46 && event.charCode <= 57" size="5" value="0"></p>
            <p>Trend Colour<br><input size="7" id="fs_peer_color" name="fs_peer_color" class="jscolor {hash:true}" value="#000000"></p>	</td>
        </tr>

        <tr>
          <td colspan="2"><input type="submit" style="font-size:0.8em;" class="btn btn-grey" value="Save Changes" <?php if($_SESSION['agent_level']< '2'){ ?>disabled<?php }?>></td>
          </tr>
      </tbody>
    </table>
</form>
