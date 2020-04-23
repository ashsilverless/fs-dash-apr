<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$flag = $_GET['f'];



try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
    
     //    Get the products   ///
	
	if($flag=='1'){
		$query = "SELECT * FROM `tbl_fs_transactions` ORDER BY confirmed_date DESC LIMIT 1;";
		  $result = $conn->prepare($query); 
		  $result->execute();

		  // Parse returned data
		  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			  $confirmed_date = $row['confirmed_date'];
			  $confirmed_by = $row['confirmed_by'];
		  }	
	}

  $flag == '1' ? $query = "SELECT * FROM `tbl_fs_transactions` where confirmed_date LIKE '$confirmed_date';" : $query = "SELECT * FROM `tbl_fs_transactions` where bl_live = 2;";
	
  $result = $conn->prepare($query); 
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $data[] = $row;
  }

  //  Check for duplicated Deal References
	
  $err_check = 'SELECT id,fs_deal_ref, COUNT(*) c FROM `tbl_fs_transactions` GROUP BY fs_deal_ref HAVING c > 1;';
  $result = $conn->prepare($err_check); 
  $result->execute();
  
  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $errors[] = $row;
  }	
	
	
	
  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}

?>
<?php if($flag=='1'){?>
<h5 align="center">Latest Data Confirmed by <strong><?=$confirmed_by;?></strong> on <strong><?=date('j M y',strtotime($confirmed_date));?></strong></h5>
<?php }else{?>
<h4 align="center"><em>Does this data look correct ?</em> <a class="btn btn-success mr-4" href="confirm_data.php">Yes</a><a class="btn btn-danger" href="delete_data.php">No</a></h4>
<?php }?>

<?php if(count($errors)>0){ ?>
		<table class="table table-sm table-striped mt-6" style="font-size:0.8em;">
              <thead>
				  <tr>
					  <th colspan="17"><h3><?=count($errors);?> Duplicate Deal Reference(s) Found !!</h3></th>
				  </tr>
                <tr>
					<th></th>
					<th>Transaction Date</th>
					<th>Deal Ref</th>
					<th>Deal Type</th>
					<th>ISIN Code</th>
					<th>Fund Sedol</th>
					<th>Fund Full Name</th>
					<th>Currency Code</th>
					<th>Accum Units Indicator</th>
					<th>Client Type Description</th>
					<th>Client Code</th>
					<th>Client Name</th>
					<th>Designation</th>
					<th>Product Type</th>
					<th>Shares</th>
					<th>Transaction Price</th>
					<th>Investment Amount</th>
                </tr>
              </thead>
              <tbody>
                  <?php foreach ($errors as $errorRecord):
						   $er_records = getTable('tbl_fs_transactions','id','fs_deal_ref LIKE "'.$errorRecord['fs_deal_ref'].'"');
						   foreach ($er_records as $er_record ):
                      ?>
                      <tr>
						  <td><a href="del_trans_record.php?id=<?=$er_record['id']?>">Delete</a></td>
						  <td><?=$er_record['fs_transaction_date']?></td>
						  <td><?=$er_record['fs_deal_ref']?></td>
							<td><?=$er_record['fs_deal_type']?></td>
							<td><?=$er_record['fs_isin_code']?></td>
							<td><?=$er_record['fs_fund_sedol']?></td>
							<td><?=$er_record['fs_fund_name']?></td>
							<td><?=$er_record['fs_currency_code']?></td>
							<td><?=$er_record['fs_aui']?></td>
							<td><?=$er_record['fs_client_desc']?></td>
							<td><?=$er_record['fs_client_code']?></td>
							<td><?=$er_record['fs_client_name']?></td>
							<td><?=$er_record['fs_designation']?></td>
							<td><?=$er_record['fs_product_type']?></td>
							<td><?=$er_record['fs_shares']?></td>
							<td><?=$er_record['fs_t_price']?></td>
							<td><?=$er_record['fs_iam']?></td>
                      </tr>
                      <?php endforeach; endforeach; ?>
              </tbody>
            </table>
<?php }?>
            <table class="table table-sm table-striped mt-6" style="font-size:0.8em;">
              <thead>
                <tr>
					<th>Transaction Date</th>
					<th>Deal Ref</th>
					<th>Deal Type</th>
					<th>ISIN Code</th>
					<th>Fund Sedol</th>
					<th>Fund Full Name</th>
					<th>Currency Code</th>
					<th>Accum Units Indicator</th>
					<th>Client Type Description</th>
					<th>Client Code</th>
					<th>Client Name</th>
					<th>Designation</th>
					<th>Product Type</th>
					<th>Shares</th>
					<th>Transaction Price</th>
					<th>Investment Amount</th>
                </tr>
              </thead>
              <tbody>
                  <?php foreach ($data as $record):
                      ?>
                      <tr>
						  <td><?=$record['fs_transaction_date']?></td>
						  <td><?=$record['fs_deal_ref']?></td>
							<td><?=$record['fs_deal_type']?></td>
							<td><?=$record['fs_isin_code']?></td>
							<td><?=$record['fs_fund_sedol']?></td>
							<td><?=$record['fs_fund_name']?></td>
							<td><?=$record['fs_currency_code']?></td>
							<td><?=$record['fs_aui']?></td>
							<td><?=$record['fs_client_desc']?></td>
							<td><?=$record['fs_client_code']?></td>
							<td><?=$record['fs_client_name']?></td>
							<td><?=$record['fs_designation']?></td>
							<td><?=$record['fs_product_type']?></td>
							<td><?=$record['fs_shares']?></td>
							<td><?=$record['fs_t_price']?></td>
							<td><?=$record['fs_iam']?></td>
                      </tr>
                      <?php endforeach; ?>
              </tbody>
            </table>