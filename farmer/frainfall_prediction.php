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

<div class="container ">
    
    	 <div class="row">
          <div class="col-md-8 mx-auto text-center">
            <span class="badge badge-danger badge-pill mb-3">Prediction</span>
          </div>
        </div>
		
          <div class="row row-content">
            <div class="col-md-12 mb-3">

				<div class="card text-white bg-gradient-success mb-3">
				<form role="form" action="#" method="post" >  
				  <div class="card-header">
				  <span class=" text-info display-4" > Rainfall Prediction  </span>	
				  
				  </div>

				  <div class="card-body text-dark">
					 
				<table class="table table-striped table-hover table-bordered bg-gradient-white text-center display" id="myTable">

	<thead>
					<tr class="font-weight-bold text-default">
					<th><center>State</center></th>
					<th><center>District</center></th>
					<th><center>Date</center></th>
					<th><center>Prediction</center></th>
		</tr>
	</thead>
 <tbody>
								<tr class="text-center">

								   <td>
										<div class="form-group ">
												<select id="state-select" name="state" class="form-control" required onchange="print_city('district-select', this.selectedIndex);">
													<option value="">Select State</option>
												</select>
                                                
										</div>
									</td>

									<td>
										<div class="form-group ">
												<select id="district-select" name="district" class="form-control" required>
													<option value="">Select District</option>
												</select>
										</div>
									</td>

									<td>
										<div class="form-group">
											<input type="date" name="date" class="form-control" required />
										</div>
									</td>



									<td>
									<center>
										<div class="form-group ">
											<button type="submit" value="Yield" name="Rainfall_Predict" class="btn btn-success btn-submit">Predict</button>
										</div>

									</center>
									</td>
								</tr>
							</tbody>
							
					
	</table>
	</div>
	</form>
</div>

<div class="card text-white bg-gradient-success mb-3">
				  <div class="card-header">
				  <span class=" text-success display-4" > Result  </span>					
				  </div>

					<h4>
					<?php
					if(isset($_POST['Rainfall_Predict'])){
						// collect inputs
						$state = isset($_POST['state']) ? trim($_POST['state']) : '';
						$district = isset($_POST['district']) ? trim($_POST['district']) : '';
						$date = isset($_POST['date']) ? trim($_POST['date']) : '';
						// basic validation
						$errors = [];
						if ($state === '') $errors[] = 'Select a state.';
						if ($district === '') $errors[] = 'Select a district.';
						if ($date === '' || !strtotime($date)) $errors[] = 'Select a valid date.';

						if (!empty($errors)){
							echo '<div class="text-danger"><strong>Input errors:</strong><br>' . implode('<br>', array_map('htmlspecialchars',$errors)) . '</div>';
						} else {
							$date_str = $date; // yyyy-mm-dd
							$day_name = date('l', strtotime($date));
							$year = date('Y', strtotime($date));
							$month_abbr = strtoupper(date('M', strtotime($date)));

							echo "Predicted Rainfall for <strong>State</strong>: " . htmlspecialchars($state) . ", <strong>District</strong>: " . htmlspecialchars($district) . " on <strong>" . htmlspecialchars($date_str) . "</strong> is (in mm) : ";

							$python = 'C:\\Users\\DELL\\AppData\\Local\\Programs\\Python\\Python313\\python.exe';
							$script = realpath(__DIR__ . '/ML/rainfall_prediction/rainfall_prediction.py');
							if ($script === false) {
								echo "<span class='text-danger'>Python script not found.</span>";
							} else {
								$cmd = '"' . $python . '" '
									 . '"' . $script . '" '
									 . escapeshellarg($state) . ' '
									 . escapeshellarg($district) . ' '
									 . escapeshellarg($date_str) . ' '
									 . escapeshellarg($day_name) . ' '
									 . escapeshellarg($month_abbr) . ' '
									 . escapeshellarg($year)
									 . ' 2>nul';

								$output = shell_exec($cmd);
								$out = trim((string)$output);
								if ($out === '') {
									echo "<span class='text-danger'>No result returned from model.</span>";
								} else {
									// Remove extra lines (time and note) from output
									$filtered = preg_replace([
										'/; if it occurs, estimated time: [^\n]+/i',
										'/\s*\(based on historical [^)]+\)/i',
										'/Note: This is a state-level historical estimate.*$/ims'
									], '', $out);
									$filtered = trim($filtered);
									// Show full filtered text in the default style (no large centered number)
									echo '<div style="font-size: 18px; color: #27ae60; font-weight: bold; white-space: pre-line;">' . htmlspecialchars($filtered) . '</div>';
								}
							}
						}
					}
					?>
					</h4>
            </div>
 
	
	
            </div>
          </div>  
       </div>
		 
</section>

<script>
document.addEventListener('DOMContentLoaded', function(){
	const stateSel = document.getElementById('state-select');
	const districtSel = document.getElementById('district-select');
	// load updated states/districts JSON
	fetch('state_districts.json')
	.then(function(resp){ if(!resp.ok) throw new Error('Failed to load state list'); return resp.json(); })
	.then(function(data){
		window._stateDistricts = data;
		// populate states
		stateSel.innerHTML = '<option value="">Select State</option>';
		Object.keys(data).forEach(function(s){
			const opt = document.createElement('option'); opt.value = s; opt.textContent = s; stateSel.appendChild(opt);
		});
	})
	.catch(function(err){
		console.error('state_districts.json load error', err);
		stateSel.innerHTML = '<option value="">States unavailable</option>';
	});

	stateSel.addEventListener('change', function(){
		const st = this.value;
		const districts = (window._stateDistricts && window._stateDistricts[st]) || [];
		districtSel.innerHTML = '<option value="">Select District</option>';
		districts.forEach(function(d){
			const o = document.createElement('option'); o.value = d; o.textContent = d; districtSel.appendChild(o);
		});
	});
});
</script>

	<?php require("footer.php");?>

</body>
</html>

