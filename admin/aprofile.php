<?php
session_start();// Starting Session
require('../sql.php'); // Includes Login Script

// Storing Session
$user = $_SESSION['admin_login_user'];

if(!isset($_SESSION['admin_login_user'])){
header("location: ../index.php");} // Redirecting To Home Page
$query4 = "SELECT * from admin where admin_name ='$user'";
              $ses_sq4 = mysqli_query($conn, $query4);
              $row4 = mysqli_fetch_assoc($ses_sq4);
              $para1 = $row4['admin_id'];
              $para2 = $row4['admin_name'];
			  $para3 = $row4['admin_password'];
			  
?>

<!DOCTYPE html>
<html>
<?php require ('aheader.php');  ?>

  <body class="bg-white admin-profile-page" id="top">

  <style>
    body.admin-profile-page {
      position: relative;
      min-height: 100vh;
      overflow-x: hidden;
      background: linear-gradient(140deg, #0b1b35, #091a2f 35%, #110e33 65%, #0a183b);
      background-size: 300% 300%;
      animation: adminBgShift 20s ease infinite;
    }

    body.admin-profile-page::before,
    body.admin-profile-page::after {
      content: "";
      position: absolute;
      inset: 0;
      z-index: -2;
      pointer-events: none;
    }

    body.admin-profile-page::before {
      background: radial-gradient(circle at 25% 30%, rgba(113, 76, 255, 0.55), transparent 55%);
      filter: blur(120px);
      transform: translate(-15%, -15%);
      animation: floatBlob1 22s ease-in-out infinite;
    }

    body.admin-profile-page::after {
      background: radial-gradient(circle at 75% 80%, rgba(0, 255, 214, 0.48), transparent 55%);
      filter: blur(100px);
      transform: translate(15%, 15%);
      animation: floatBlob2 24s ease-in-out infinite;
    }

    @keyframes adminBgShift {
      0% {
        background-position: 0% 50%;
      }
      50% {
        background-position: 100% 50%;
      }
      100% {
        background-position: 0% 50%;
      }
    }

    @keyframes floatBlob1 {
      0%,
      100% {
        transform: translate(-15%, -15%) scale(1);
      }
      50% {
        transform: translate(-18%, -12%) scale(1.05);
      }
    }

    @keyframes floatBlob2 {
      0%,
      100% {
        transform: translate(15%, 15%) scale(1);
      }
      50% {
        transform: translate(18%, 12%) scale(1.06);
      }
    }

    /* Cursor-follow glow (JS-driven) */
    .admin-profile-page .cursor-glow {
      position: fixed;
      top: 0;
      left: 0;
      width: 380px;
      height: 380px;
      pointer-events: none;
      border-radius: 50%;
      background: radial-gradient(circle at 50% 50%, rgba(33, 230, 253, 0.55), transparent 55%);
      filter: blur(70px);
      mix-blend-mode: screen;
      z-index: 0;
      transform: translate3d(-50%, -50%, 0);
      will-change: transform;
    }

    /* Override gradient/shape backgrounds from the global theme */
    .admin-profile-page .section.section-shaped {
      background: transparent !important;
      background-image: none !important;
    }

    .admin-profile-page .section.section-shaped .shape,
    .admin-profile-page .section.section-shaped .shape span {
      background: transparent !important;
      box-shadow: none !important;
    }

    /* Glassmorphism cards with tilt support */
    .admin-profile-page .card {
      --tilt-x: 0deg;
      --tilt-y: 0deg;
      --card-scale: 1;
      position: relative;
      z-index: 1;
      background: rgba(255, 255, 255, 0.55) !important;
      border-radius: 1rem;
      box-shadow: 0 18px 42px rgba(0, 0, 0, 0.22);
      border: 1px solid rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      transform: perspective(1000px) rotateX(var(--tilt-x)) rotateY(var(--tilt-y)) scale(var(--card-scale));
      transform-style: preserve-3d;
      transition: transform 0.25s ease, box-shadow 0.25s ease;
      will-change: transform;
    }

    .admin-profile-page .card:hover {
      --card-scale: 1.035;
      box-shadow: 0 24px 64px rgba(0, 0, 0, 0.28);
    }

    .admin-profile-page .card .card-body {
      background: transparent !important;
    }

    .admin-profile-page .card .text-white {
      color: #111 !important;
    }

    .admin-profile-page .card .bg-gradient-success,
    .admin-profile-page .card .bg-gradient-white {
      background: rgba(255, 255, 255, 0.55) !important;
    }
  </style>

<?php require ('anav.php');  ?>
 	
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
            <span class="badge badge-danger badge-pill mb-3">Admin</span>
          </div>
        </div>
		
          <div class="row row-content">
            <div class="col-md-4 mb-3">
			
			
				<div class="card">
                <div class="card-body bg-gradient-success">
                  <div class="d-flex flex-column align-items-center text-center">
                    <img src="../assets/img/admin.png" alt="admin" class="rounded-circle " width="158">
                    <div class="mt-3">
                      <h4>                Welcome     <?php echo $para2 ?></h4>
                      <p class="text-white mb-1">Admin ID: <?php echo $para1 ?> </p>

                    </div>
                  </div>
                </div>
              </div>			 		  
            </div>
			
			
                <div class="col-md-8">
              <div class="card mb-3">
                <div class="card-body bg-gradient-white">
				
                  <ol class="text-justify list-group list-group-flush">
               
                <li class="list-group-item">Admin has access to all the data in the Agriculture Portal.</li>
				<li class="list-group-item">Admin can modify and view all the Customer's details when necessory.</li>
				<li class="list-group-item">Admin can manage the farmer's details who provide supplies to the store.</li>
               <li class="list-group-item"> Admin also has access to the sales report and can sort them as required.</li>
                <br>
              </ol>
          
                </div>
              </div>
            </div>
			
          </div>
        </div>
		
  

</section>

    <?php require("footer.php");?>

</body>
  <script>
  function password_show_hide() {
  var x = document.getElementById("password");
  var show_eye = document.getElementById("show_eye");
  var hide_eye = document.getElementById("hide_eye");
  hide_eye.classList.remove("d-none");
  if (x.type === "password") {
    x.type = "text";
    show_eye.style.display = "none";
    hide_eye.style.display = "block";
  } else {
    x.type = "password";
    show_eye.style.display = "block";
    hide_eye.style.display = "none";
  }
}

(function () {
  const body = document.body;
  if (!body.classList.contains("admin-profile-page")) return;

  // Smooth mouse-follow glow
  const glow = document.createElement("div");
  glow.className = "cursor-glow";
  body.appendChild(glow);

  let targetX = window.innerWidth / 2;
  let targetY = window.innerHeight / 2;
  let currentX = targetX;
  let currentY = targetY;

  document.addEventListener("mousemove", (event) => {
    targetX = event.clientX;
    targetY = event.clientY;
  });

  function animateGlow() {
    currentX += (targetX - currentX) * 0.14;
    currentY += (targetY - currentY) * 0.14;
    glow.style.transform = `translate3d(${currentX}px, ${currentY}px, 0) translate(-50%, -50%)`;
    requestAnimationFrame(animateGlow);
  }
  requestAnimationFrame(animateGlow);

  // Card tilt interaction
  const cards = document.querySelectorAll(".admin-profile-page .card");
  cards.forEach((card) => {
    card.addEventListener("mousemove", (event) => {
      const rect = card.getBoundingClientRect();
      const px = (event.clientX - rect.left) / rect.width;
      const py = (event.clientY - rect.top) / rect.height;
      const rotateY = (px - 0.5) * 18;
      const rotateX = (0.5 - py) * 18;
      card.style.setProperty("--tilt-x", `${rotateX}deg`);
      card.style.setProperty("--tilt-y", `${rotateY}deg`);
    });

    card.addEventListener("mouseleave", () => {
      card.style.setProperty("--tilt-x", "0deg");
      card.style.setProperty("--tilt-y", "0deg");
    });
  });
})();
</script>
</html>