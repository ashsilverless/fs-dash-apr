<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
$isin_code = $_GET['ic'];



$dt = strtotime($_GET['dt'].'-01');

$nextMonth = date("Y-m", strtotime("+1 month", $dt));
$previousMonth = date("Y-m", strtotime("-1 month", $dt));

$dateQueryMonth = date('m', strtotime($_GET['dt'].'-01'));
$dateQueryYear = date('Y', strtotime($_GET['dt'].'-01'));
$dateQuery = date('Y-m', $dt);



function draw_calendar($dt,$month,$year,$isin_code){
    global $host,$user, $pass, $db, $charset, $agent_level, $nextMonth, $previousMonth, $dateQuery, $priceID, $isin_code;
	/* draw table */
    //$priceID = '';
	$calendar = '<table cellpadding="0" cellspacing="0" class="calendar tbl">';


	$calendar.= '<tr class="calendar__head"><td colspan="2"><a href="?dt='.$previousMonth.'" class="monthback'.$isin_code.'"><span data-feather="skip-back"></span><i class="fas fa-chevron-left"></i></a></td><td colspan="3" ><h4 class="heading heading__4">'.date('F Y',mktime(0,0,0,$month,1,$year)).'</h4></td><td colspan="2"><a href="?dt='.$nextMonth.'" class="monthnext'.$isin_code.'"><i class="fas fa-chevron-right"></i><span data-feather="skip-forward"></span></a></td></tr>';


	/* table headings */
	$headings = array('S','M','T','W','T','F','S');
	$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

	/* days and weeks vars now ... */
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
    $today = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$calendar.= '<tr class="calendar-row daily-cells">';

    // Connect and create the PDO object
	  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	/* print "blank" days until the first of the current week */
	for($x = 0; $x < $running_day; $x++):
		$calendar.= '<td class="calendar-day-np"> </td>';
		$days_in_this_week++;
	endfor;

    $day_checks = '';

	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):

        $cur_date = date('Y-m-d', mktime(0, 0, 0, $month, $list_day, $year));



			  $query = "SELECT * FROM tbl_fs_fund WHERE isin_code LIKE '$isin_code' AND correct_at LIKE '".$cur_date."' ORDER BY correct_at ASC";
	//debug($query);
              $result = $conn->prepare($query);
              $result->execute();
              $eventStr = '';
              // Parse returned data
              if($result->rowCount() > 0){
                  while($row = $result->fetch(PDO::FETCH_ASSOC)) {

                      /* add in the day number
                     $calendar .= '<div class="day-number f-left"><span>'.$list_day.'</span></div><div id="clear" style="height:5px;clear:both;"></div>';
					 */

					 $cur_date == $today ? $calendar.= '<td class="calendar-day" style="background-color:rgba(255,255,0,0.3)" valign="top" align="center"><div class="day-number f-left"><span>'.$list_day.'</span></div><div id="clear" style="height:1px;clear:both;"></div>' : $calendar.= '<td class="calendar-day" valign="top" align="center" ><div class="day-number f-left"><span>'.$list_day.'</span></div><div id="clear" style="height:1px;clear:both;"></div>';





                     $calendar .= "<a href='#'  id='".$isin_code.$row['id']."' data-inputclass='input_num' data-type='text' data-pk='".$row['id']."' data-url='addexistingfundprice.php?ic=".$isin_code."' class='editme'>".$row['current_price']."</a>";

					 $priceID .= "#".$isin_code.$row['id'].",";

                      $day_checks .= $row['id']."|";

                  }
              }else{

				  $cur_date == $today ? $calendar.= '<td class="calendar-day-np today"  valign="top" align="center"><div class="day-number f-left"><span>'.$list_day.'</span></div><div id="clear" style="height:1px;clear:both;"></div>' : $calendar.= '<td class="calendar-day-nd" valign="top" align="center" ><div class="day-number f-left"><span>'.$list_day.'</span></div><div id="clear" style="height:1px;clear:both;"></div>';

                  $calendar.= '<p class="price"><span class="empty-price">--.--</span></p>';

                  $nd_day_checks .= $list_day."|";
              }

		$calendar.= '</td>';
		if($running_day == 6):
			$calendar.= '</tr>';
			if(($day_counter+1) != $days_in_month):
				$calendar.= '<tr class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++; $running_day++; $day_counter++;
	endfor;

    $conn = null;        // Disconnect

	/* finish the rest of the days in the week */
	if($days_in_this_week < 8):
		for($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar.= '<td class="calendar-day-np"> </td>';
		endfor;
	endif;

	/* final row */
	$calendar.= '</tr>';

	/* end the table */
	$calendar.= '</table>';

    /* id's of all the days in displayed table = $day_checks */
    $calendar .= '<input type="hidden" name="day_checks" id="day_checks" value="'.substr($day_checks, 0, -1).'">';
    $calendar .= '<input type="hidden" name="nd_day_checks" id="nd_day_checks" value="'.substr($nd_day_checks, 0, -1).'">';
    $calendar .= '<input type="hidden" name="displaydate" id="displaydate" value="'.$dateQuery.'">';

	/* all done, return result */
	return $calendar;
}?>
<?=draw_calendar($dt,$dateQueryMonth,$dateQueryYear,$isin_code);?>

<script type="text/javascript">
	$(document).ready(function() {
		$.fn.editable.defaults.mode = 'inline';
		$('<?=substr($priceID, 0, -1);?>').editable({emptytext: '0',tpl: "<input type='text' style='width: 100px;font-size:1em;'>"});
	});
</script>
