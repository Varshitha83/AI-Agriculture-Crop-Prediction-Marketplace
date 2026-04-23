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

// Handle form POST using Post-Redirect-Get so results only show after submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Fert_Recommend'])) {
	// collect and validate inputs
	$n = isset($_POST['n']) ? trim($_POST['n']) : '';
	$p = isset($_POST['p']) ? trim($_POST['p']) : '';
	$k = isset($_POST['k']) ? trim($_POST['k']) : '';
	$t = isset($_POST['t']) ? trim($_POST['t']) : '';
	$h = isset($_POST['h']) ? trim($_POST['h']) : '';
	$sm = isset($_POST['soilMoisture']) ? trim($_POST['soilMoisture']) : '';
	$soil = isset($_POST['soil']) ? trim($_POST['soil']) : '';
	$crop = isset($_POST['crop']) ? trim($_POST['crop']) : '';

	$errors = [];
	foreach (['n'=>$n,'p'=>$p,'k'=>$k,'t'=>$t,'h'=>$h,'soilMoisture'=>$sm] as $kname => $val) {
		if ($val === '' || !is_numeric($val)) {
			$errors[] = strtoupper($kname) . " must be a number.";
		}
	}
	if ($soil === '') $errors[] = 'Select a soil type.';
	if ($crop === '') $errors[] = 'Select a crop.';

	if (!empty($errors)) {
		$_SESSION['fert_result_msg'] = '<div class="text-danger"><strong>Input errors:</strong><br>' . implode('<br>', array_map('htmlspecialchars',$errors)) . '</div>';
	} else {
		$python = 'C:\\Users\\DELL\\AppData\\Local\\Programs\\Python\\Python313\\python.exe';
		$script = realpath(__DIR__ . '/ML/fertilizer_recommendation/fertilizer_recommendation.py');

		if ($script === false) {
			$_SESSION['fert_result_msg'] = "<span class='text-danger'>Python script not found.</span>";
		} else {
			$cmd = '"' . $python . '" '
				 . '"' . $script . '" '
				 . escapeshellarg($n) . ' '
				 . escapeshellarg($p) . ' '
				 . escapeshellarg($k) . ' '
				 . escapeshellarg($t) . ' '
				 . escapeshellarg($h) . ' '
				 . escapeshellarg($sm) . ' '
				 . escapeshellarg($soil) . ' '
				 . escapeshellarg($crop)
				 . ' 2>nul';
			$output = shell_exec($cmd);
			$out = trim((string)$output);
			if ($out === '') {
				$_SESSION['fert_result_msg'] = "<span class='text-danger'>No recommendation returned.</span>";
			} else {
				$_SESSION['fert_result_msg'] = '<div style="font-size: 18px; color: #27ae60; font-weight: bold;">' . htmlspecialchars($out) . '</div>';
			}
		}
	}

	// set a scroll flag and redirect to avoid showing results on initial GET
	$_SESSION['fert_scroll'] = true;
	header('Location: ' . $_SERVER['REQUEST_URI']);
	exit;
}
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
				  <span class=" text-info display-4" > Fertilizer Recommendation  </span>	
						<span class="pull-right">
							<button type="submit" value="Recommend" name="Fert_Recommend" class="btn btn-warning btn-submit">SUBMIT</button>
							<span id="fert_spinner" class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true" style="display:none; margin-left:8px;"></span>
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
					<th><center>Soil Moisture</center></th>
					<th><center>Soil Type</center></th>
					<th><center>Crop</center></th>
        </tr>
    </thead>
 <tbody>
								<tr class="text-center">
									<td>
										<div class="form-group">
											<input type='number' name='n' placeholder="Nitrogen Eg:37" required class="form-control">
										</div>
									</td>
									<td>
										<div class="form-group">
											<input type='number' name='p' placeholder="Phosphorous Eg:20" required class="form-control">
										</div>
									</td>
									<td>
										<div class="form-group">
											<input type='number' name='k' placeholder="Potassium Eg:30" required class="form-control">
										</div>
									</td>
									<td>
										<div class="form-group">
											<input type='number' name='t' placeholder="Temperature °C Eg:30" step='any' required class="form-control">
										</div>
									</td>
									<td>
										<div class="form-group">
											<input type='number' name='h' placeholder="Humidity % Eg:70" step='any' required class="form-control">
										</div>
									</td>
									<td>
										<div class="form-group">
											<input type='number' name='soilMoisture' placeholder="Soil Moisture Eg:25" step='any' required class="form-control">
										</div>
									</td>
									<td>
										<div class="form-group ">
											<select name="soil" class="form-control" required>
												<option value="">Select Soil Type</option>
												<option value="Sandy">Sandy</option>
												<option value="Loamy">Loamy</option>
												<option value="Black">Black</option>
												<option value="Red">Red</option>
												<option value="Clayey">Clayey</option>
											</select>
										</div>
									</td>
									<td>
										<div class="form-group ">
											<select name="crop" class="form-control" required>
												<option value="">Select Crop</option>
												<option value="Maize">Maize</option>
												<option value="Sugarcane">Sugarcane</option>
												<option value="Cotton">Cotton</option>
												<option value="Tobacco">Tobacco</option>
												<option value="Paddy">Paddy</option>
												<option value="Barley">Barley</option>
												<option value="Wheat">Wheat</option>
												<option value="Millets">Millets</option>
												<option value="Oil seeds">Oil seeds</option>
												<option value="Pulses">Pulses</option>
												<option value="Ground Nuts">Ground Nuts</option>
											</select>
										</div>
									</td>
								</tr>
							</tbody>
							
					
	</table>
	</div>
	</form>

</div>



<div id="result_card" class="card text-white bg-gradient-success mb-3">
			  <div class="card-header">
			  <span class=" text-success display-4" > Result  </span>					
			  </div>

			  <div class="card-body text-dark">
				<h4>Recommended Fertilizer is :</h4>
				<div id="fert_result_display">
				<?php
				if (isset($_SESSION['fert_result_msg'])) {
					// show the stored message (could be an error or the recommendation)
					echo $_SESSION['fert_result_msg'];
					unset($_SESSION['fert_result_msg']);
				} else {
					echo "<span class='text-muted'>Enter values above and click SUBMIT.</span>";
				}
				?>
				</div>
				<?php
				if (!empty($_SESSION['fert_scroll'])) {
				    echo "<script>setTimeout(function(){var el=document.getElementById('result_card'); if(el) el.scrollIntoView({behavior:'smooth'});},100);</script>";
				    unset($_SESSION['fert_scroll']);
				}
				?>
            </div>
            </div>	
	
            </div>
          </div>  
       </div>
		 
</section>

<script>
// Show spinner on form submit to prevent double submits
document.addEventListener('DOMContentLoaded', function(){
  const form = document.querySelector('form[role="form"]');
  const spinner = document.getElementById('fert_spinner');
  const submitBtn = document.querySelector('button[name="Fert_Recommend"]');
  if(form){
    form.addEventListener('submit', function(){
      if(spinner) spinner.style.display = 'inline-block';
      setTimeout(function(){ if(submitBtn) submitBtn.disabled = true; }, 10);
    });
  }
});
</script>

    <?php require("footer.php");?>

</body>
</html>
