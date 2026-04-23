<?php
include ('fsession.php');
ini_set('memory_limit', '-1');

if(!isset($_SESSION['farmer_login_user'])){
header("location: ../index.php");} // Redirecting To Home Page
$query4 = "SELECT * from farmerlogin where email='$user_check'";
              $ses_sq4 = mysqli_query($conn, $query4);
              $row4 = mysqli_fetch_assoc($ses_sq4);
              $para1 = $row4['farmer_id'];
              $para2 = $row4['farmer_name'];
			  
?>

<!DOCTYPE html>
<html>
<?php include ('fheader.php');  ?>

  <body class="bg-white" id="top">
  
<?php include ('fnav.php');  ?>

 
  <section class="section section-shaped section-lg">
    <div class="shape shape-style-1 shape-primary">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
    </div>
<!-- ======================================================================================================================================== -->

<div class="container-fluid ">
    
    	 <div class="row">
          <div class="col-md-8 mx-auto text-center">
            <span class="badge badge-danger badge-pill mb-3">Recommendation</span>
          </div>
        </div>
		
          <div class="row row-content">
            <div class="col-md-12 mb-3">

				<div class="card text-white bg-gradient-success mb-3">
				<form role="form" action="#" method="post" >  
				  <div class="card-header">
				  <span class=" text-info display-4" > Crop Recommendation  </span>	
						<span class="pull-right">
							<button type="submit" value="Recommend" name="Crop_Recommend" class="btn btn-warning btn-submit">SUBMIT</button>
							<span id="recommend_spinner" class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true" style="display:none; margin-left:8px;"></span>
						</span>		
				  
				  </div>

				  <div class="card-body text-dark">     
					 
				<table class="table table-striped table-hover table-bordered bg-gradient-white text-center display" id="myTable">

    <thead>
					<tr class="font-weight-bold text-default">
					<th><center> Nitrogen</center></th>
					<th><center>Phosporous</center></th>
					<th><center>Potasioum</center></th>
					<th><center>Temparature</center></th>
					<th><center>Humidity</center></th>
					<th><center>PH</center></th>
					<th><center>Rainfall</center></th>
					
        </tr>
    </thead>
 <tbody>
                                 <tr class="text-center">
                                    <td>
                                    	<div class="form-group">
											<input type = 'number' name = 'n' placeholder="Nitrogen Eg:90" required class="form-control">
											
										</div>
                                    </td>
									
									<td>
                                    	<div class="form-group">
											<input type = 'number' name = 'p' placeholder="Phosphorus Eg:42" required class="form-control">
											
										</div>
                                    </td>
									
									<td>
                                    	<div class="form-group">
											<input type = 'number' name = 'k' placeholder="Pottasium Eg:43" required class="form-control">
											
										</div>
                                    </td>
									
									<td>
                                    	<div class="form-group">
											<input type = 'number' name = 't' step =0.01 placeholder="Temperature Eg:21" required class="form-control">
											
										</div>
                                    </td>
									
									<td>
                                    	<div class="form-group">
											<input type = 'number' name = 'h' step =0.01 placeholder="Humidity Eg:82" required class="form-control">
											
										</div>
                                    </td>
									
									<td>
                                    	<div class="form-group">
											<input type = 'number' name = 'ph' step =0.01 placeholder="PH Eg:6.5" required class="form-control">
											
										</div>
                                    </td>
									
									<td>
                                    	<div class="form-group">
											 <input type = 'number' name = 'r' step =0.01 placeholder="Rainfall Eg:203" required class="form-control">
											
										</div>
                                    </td>
                                </tr>
                            </tbody>
							
					
	</table>
</div>
</div>



<div id="result_card" class="card text-white bg-gradient-success mb-3">
			  <div class="card-header">
			  <span class=" text-success display-4" > Result  </span>					
			  </div>

			  <div class="card-body text-dark">
				<h4>
				<?php 
				if(isset($_POST['Crop_Recommend'])){
				$n=trim($_POST['n']);
				$p=trim($_POST['p']);
				$k=trim($_POST['k']);
				$t=trim($_POST['t']);
				$h=trim($_POST['h']);
				$ph=trim($_POST['ph']);
				$r=trim($_POST['r']);


				echo "Recommended Crop is : ";

				$python = 'C:\\Users\\DELL\\AppData\\Local\\Programs\\Python\\Python313\\python.exe';
				$script = realpath(__DIR__ . '/ML/crop_recommendation/recommend.py');

				if ($script === false) {
				    echo "<span class='text-danger'>Python script not found.</span>";
				} else {
				    $cmd = '"' . $python . '" '
				         . '"' . $script . '" '
				         . escapeshellarg($n) . ' '
				         . escapeshellarg($p) . ' '
				         . escapeshellarg($k) . ' '
				         . escapeshellarg($t) . ' '
				         . escapeshellarg($h) . ' '
				         . escapeshellarg($ph) . ' '
				         . escapeshellarg($r)
				         . ' 2>nul';
				    $output = shell_exec($cmd);
				    if ($output === null || $output === '') {
				        echo "<span class='text-danger'>No recommendation returned.</span>";
				    } else {
				        echo '<div style="font-size: 18px; color: #27ae60; font-weight: bold;">' . htmlspecialchars(trim($output)) . '</div>';
				    }
				}
				} else {
				    echo "<span class='text-muted'>Enter values above and click SUBMIT.</span>";
				}
                    ?>
				</h4>
				<?php
				if (isset($_POST['Crop_Recommend'])) {
				    echo "<script>setTimeout(function(){var el=document.getElementById('result_card'); if(el) el.scrollIntoView({behavior:'smooth'});},100);</script>";
				}
				?>
            </div>
            </div>	
	
            </div>
          </div>  
       </div>
		 
</section>

    <?php require("footer.php");?>

</body>
</html>
