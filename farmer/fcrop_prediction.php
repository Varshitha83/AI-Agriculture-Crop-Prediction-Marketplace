<?php
include ('fsession.php');
ini_set('memory_limit', '-1');

if (!isset($_SESSION['farmer_login_user'])) {
    header("location: ../index.php");
    exit;
}

$query4   = "SELECT * FROM farmerlogin WHERE email='$user_check'";
$ses_sq4  = mysqli_query($conn, $query4);
$row4     = mysqli_fetch_assoc($ses_sq4);
$para1    = $row4['farmer_id'];
$para2    = $row4['farmer_name'];
?>

<!DOCTYPE html>
<html>
<?php include('fheader.php'); ?>
<style>
  .shape-primary {
    background: linear-gradient(to bottom, #E3F2FD, #FFFFFF) !important;
  }
  .shape-primary span {
    display: none !important;
  }
</style>

<body class="bg-white" id="top">

<?php include('fnav.php'); ?>

<section class="section section-shaped section-lg">
  <div class="shape shape-style-1 shape-primary">
    <span></span><span></span><span></span><span></span><span></span>
    <span></span><span></span><span></span><span></span><span></span>
  </div>

  <div class="container">

    <div class="row">
      <div class="col-md-8 mx-auto text-center">
        <span class="badge badge-danger badge-pill mb-3">Prediction</span>
      </div>
    </div>

    <div class="row row-content">
      <div class="col-md-12 mb-3">

        <!-- =================== FORM =================== -->
        <div class="card text-white bg-gradient-success mb-3">
          <div class="card-header">
            <span class="text-success display-4">Crop Prediction</span>
          </div>

          <div class="card-body text-dark">
            <!-- Post back to the same page -->
            <form role="form" action="" method="post">
              <input type="hidden" name="Crop_Predict" id="Crop_Predict_hidden" value="0">
              <div id="json_error" class="alert alert-danger" style="display:none; margin-bottom:15px;">
                <!-- JSON load errors will appear here -->
              </div>
              <table class="table table-striped table-hover table-bordered bg-gradient-white text-center display" id="myTable">
                <thead>
                  <tr class="font-weight-bold text-default">
                    <th><center>State</center></th>
                    <th><center>District</center></th>
                    <th><center>Season</center></th>
                    <th><center>Prediction</center></th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="text-center">

                    <!-- STATE -->
                    <td>
                      <div class="form-group">
                        <select id="state_select" name="stt" class="form-control" required>
                          <option value="">Select State</option>
                        </select>
                      </div>
                    </td>

                    <!-- DISTRICT -->

                    <td>
                      <div class="form-group">
                        <select id="district_select" name="district" class="form-control" required>
                          <option value="">Select District</option>
                        </select>
                      </div>
                    </td>

                    <!-- SEASON -->

                    <script>
// Populate state and district selects from JSON file
const stateSelect = document.getElementById('state_select');
const districtSelect = document.getElementById('district_select');

const initialState = <?php echo json_encode($_POST['stt'] ?? ''); ?>;
const initialDistrict = <?php echo json_encode($_POST['district'] ?? ''); ?>;

fetch('state_districts.json')
  .then(r => r.json())
  .then(data => {
    const states = Object.keys(data).sort();
    stateSelect.innerHTML = '<option value="">Select State</option>';
    for (const s of states) {
      const opt = document.createElement('option');
      opt.value = s;
      opt.text = s;
      stateSelect.appendChild(opt);
    }

    if (initialState) {
      stateSelect.value = initialState;
      populateDistricts(initialState, data);
      if (initialDistrict) districtSelect.value = initialDistrict;
    }
    // hide any previous JSON error and enable predict button
    const jsonErr = document.getElementById('json_error'); if(jsonErr) jsonErr.style.display='none';
    const predictBtn = document.querySelector('button[name="Crop_Predict"]'); if(predictBtn) predictBtn.disabled = false;
  })
  .catch(err => {
    console.error('Failed to load state_districts.json:', err);
    const jsonErr = document.getElementById('json_error'); if(jsonErr){ jsonErr.style.display='block'; jsonErr.innerText = 'Failed to load state/district data. Please contact admin or try again later.'; }
    const predictBtn = document.querySelector('button[name="Crop_Predict"]'); if(predictBtn) predictBtn.disabled = true;
  });

function populateDistricts(state, data) {
  districtSelect.innerHTML = '<option value="">Select District</option>';
  if (!state || !data[state]) return;
  for (const d of data[state]) {
    const opt = document.createElement('option');
    opt.value = d;
    opt.text = d;
    districtSelect.appendChild(opt);
  }
}

stateSelect && stateSelect.addEventListener('change', function() {
  fetch('state_districts.json')
    .then(r => r.json())
    .then(data => populateDistricts(this.value, data))
    .catch(err => console.error('Failed to load districts:', err));
});
</script>


                    <td>
                      <div class="form-group">
                        <select name="Season" class="form-control" required>
                          <option value="">Select Season ...</option>
                          <option value="Kharif">Kharif</option>
                          <option value="Whole Year">Whole Year</option>
                          <option value="Autumn">Autumn</option>
                          <option value="Rabi">Rabi</option>
                          <option value="Summer">Summer</option>
                          <option value="Winter">Winter</option>
                        </select>
                      </div>
                    </td>

                    <!-- BUTTON -->
                    <td>
                      <center>
                        <div class="form-group" style="display:flex; align-items:center; gap:8px;">
                          <button type="submit" name="Crop_Predict" class="btn btn-success btn-submit">Predict</button>
                          <span id="predict_spinner" class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true" style="display:none; margin-left:8px;"></span>
                        </div>
                      </center>
                    </td>

                  </tr>
                </tbody>
              </table>
            </form>
          </div>
        </div>

        <!-- =================== RESULT =================== -->
        <div id="result_card" class="card text-white bg-gradient-success mb-3">
          <div class="card-header">
            <span class="text-success display-4">Result</span>
          </div>
          <div class="card-body text-dark">
            <h4>
<?php
if (isset($_POST['Crop_Predict'])) {

    $state    = trim($_POST['stt']      ?? '');
    $district = trim($_POST['district'] ?? '');
    $season   = trim($_POST['Season']   ?? '');

    if ($state && $district && $season) {

        echo "Crops grown in " . strtoupper($district) . " during the $season season are :-<br>";

        /* ================= PYTHON CALL ================= */

        // 1) Python executable
        // If "python --version" works in CMD, you can leave this as 'python'.
        // If not, put full path here, e.g.:
        // $python = 'C:\\Users\\DELL\\AppData\\Local\\Programs\\Python\\Python313\\python.exe';
        $python = 'C:\\Users\\DELL\\AppData\\Local\\Programs\\Python\\Python313\\python.exe';

        // 2) Full path to the script
        // This assumes script is located at:
        // agriculture-portal-main/farmer/ML/crop_prediction/ZDecision_Tree_Model_Call.py
        $script = realpath(__DIR__ . '/ML/crop_prediction/ZDecision_Tree_Model_Call.py');

        if ($script === false) {
            echo "<span class='text-danger'>Python script not found. Check path in fcrop_prediction.php.</span>";
        } else {
            // 3) Build command (quotes for Windows paths, escape arguments)
            $cmd = '"' . $python . '" '
                 . '"' . $script . '" '
                 . escapeshellarg($state) . ' '
                 . escapeshellarg($district) . ' '
                 . escapeshellarg($season)
                 . ' 2>&1';   // capture stderr as well

            // 4) Execute
            $output = shell_exec($cmd);

            if ($output === null || $output === '') {
                echo "<span class='text-danger'>No prediction returned. Check Python installation or script path.</span>";
            } else {
                // Parse the output and display as grid
                $lines = array_filter(array_map('trim', explode("\n", $output)));
                $crops = array_filter($lines, function($line) {
                    return !empty($line) && $line !== ',';
                });
                
                if (!empty($crops)) {
                    echo '<div class="row">';
                    $count = 0;
                    foreach ($crops as $crop) {
                        echo '<div class="col-md-3 col-sm-4 col-xs-6 mb-3">';
                        echo '<div class="badge badge-success" style="padding: 10px; font-size: 14px; white-space: normal; display: inline-block; width: 100%;">' . htmlspecialchars($crop) . '</div>';
                        echo '</div>';
                        $count++;
                    }
                    echo '</div>';
                } else {
                    echo '<pre>' . nl2br(htmlspecialchars($output)) . '</pre>';
                }
            }
        }

    } else {
        echo "<span class='text-danger'>Please select state, district and season.</span>";
    }
} else {
    echo "<span class='text-muted'>Select details above and click Predict.</span>";
}
?>
            </h4>
            <?php
            // If form was submitted, scroll smoothly to the result card
            if (isset($_POST['Crop_Predict'])) {
                echo "<script>setTimeout(function(){var el=document.getElementById('result_card'); if(el) el.scrollIntoView({behavior:'smooth'});},100);</script>";
            }
            ?>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<script>
// Show spinner & disable button on form submit to prevent double submits
document.addEventListener('DOMContentLoaded', function(){
  const form = document.querySelector('form[role="form"]');
  const spinner = document.getElementById('predict_spinner');
  const predictBtn = document.querySelector('button[name="Crop_Predict"]');
  if(form){
    form.addEventListener('submit', function(){
      // set hidden input so PHP sees the submit even if the button becomes disabled
      const hidden = document.getElementById('Crop_Predict_hidden');
      if(hidden) hidden.value = '1';
      if(spinner) spinner.style.display = 'inline-block';
      // disable button after setting hidden field to avoid being excluded from POST
      setTimeout(function(){ if(predictBtn) predictBtn.disabled = true; }, 10);
    });
  }
});
</script>

<?php require("footer.php"); ?>

</body>
</html>
