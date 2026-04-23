    <nav
      id="navbar-main"
      class="
        navbar navbar-main navbar-expand-lg
        bg-default
        navbar-light
        position-sticky
        top-0
        shadow
        py-0
      "
    >
      <div class="container-fluid">
        <ul class="navbar-nav navbar-nav-hover align-items-lg-center">
          <li class="nav-item dropdown">
            <a href="../index.php" class="navbar-brand mr-lg-5 text-white">
                               <img src="../assets/img/nav.png" />
            </a>
          </li>
        </ul>

        <button
          class="navbar-toggler bg-white"
          type="button"
          data-toggle="collapse"
          data-target="#navbar_global"
          aria-controls="navbar_global"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon text-white"></span>
        </button>
        <div class="navbar-collapse collapse bg-default" id="navbar_global">
          <div class="navbar-collapse-header">
            <div class="row">
              <div class="col-10 collapse-brand">
                <a href="../index.html">
                  <img src="../assets/img/nav.png" />
                </a>
              </div>
              <div class="col-2 collapse-close bg-danger">
                <button
                  type="button"
                  class="navbar-toggler"
                  data-toggle="collapse"
                  data-target="#navbar_global"
                  aria-controls="navbar_global"
                  aria-expanded="false"
                  aria-label="Toggle navigation"
                >
                  <span></span>
                  <span></span>
                </button>
              </div>
            </div>
          </div>

          <ul class="navbar-nav align-items-lg-center ml-auto topnav" id="nav">
		  
		  	 
		  
	 <li class="nav-item dropdown" id="prediction">
		  <a class="nav-link dropdown-toggle text-white" href="#" role="button" id="dropdownMenuLink1" aria-haspopup="true" aria-expanded="false">
		                  <span class="text-white nav-link-inner--text"
                  ><i class="text-white fas fa-magic"></i> Prediction</span
                >
		  </a>

		   <div class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
		   <a class="dropdown-item" href="fcrop_prediction.php">Crop Prediction​</a>
		   <a class="dropdown-item" href="fyield_prediction.php">Yield Prediction​</a>
			<a class="dropdown-item" href="frainfall_prediction.php">Rainfall Prediction​</a>
		  </div>
		</li>
			
			
			
			 <li class="nav-item dropdown" id="recommendation">
		  <a class="nav-link dropdown-toggle text-white" href="#" role="button" id="dropdownMenuLink2" aria-haspopup="true" aria-expanded="false">
		                  <span class="text-white nav-link-inner--text"
                  ><i class="text-white fas fa-gavel"></i> Recommendation</span
                >
		  </a>

		   <div class="dropdown-menu" aria-labelledby="dropdownMenuLink2">
			<a class="dropdown-item" href="fcrop_recommendation.php">Crop Recommendation​</a>
			<a class="dropdown-item" href="ffertilizer_recommendation.php">Fertilizer Recommendation​</a>
		  </div>
		</li>
			

		  
			 <li class="nav-item dropdown" id="trade">
		  <a class="nav-link dropdown-toggle text-white" href="#" role="button" id="dropdownMenuLink3" aria-haspopup="true" aria-expanded="false">
		                  <span class="text-white nav-link-inner--text"
                  ><i class="text-white fas fa-shopping-cart"></i> Trade</span
                >
		  </a>

		   <div class="dropdown-menu" aria-labelledby="dropdownMenuLink3">
			<a class="dropdown-item" href="ftradecrops.php">Trade Crops​</a>
			<a class="dropdown-item" href="fstock_crop.php">Crop Stocks​</a>
			<a class="dropdown-item" href="fselling_history.php">Selling History​</a>
		  </div>
		</li>
			

			 
			
						 <li class="nav-item dropdown" id="tools">
		  <a class="nav-link dropdown-toggle text-white" href="#" role="button" id="dropdownMenuLink4" aria-haspopup="true" aria-expanded="false">
		                  <span class="text-white nav-link-inner--text"
                  ><i class="text-white fas fa-gear"></i> Tools</span
                >
		  </a>

		   <div class="dropdown-menu" aria-labelledby="dropdownMenuLink4">
		   <a class="dropdown-item" href="fchatgpt.php"> <i class="text-dark fad fa-robot"></i>Chat Bot​</a>
			<a class="dropdown-item" href="fweather_prediction.php"><i class="text-dark fas fa-cloud"></i> Weather Forecast​</a>
			<a class="dropdown-item" href="fnewsfeed.php"> <i class="text-dark fas fa-newspaper"></i>News Feed​</a>
		  </div>
		</li>
			
		
		
		   <li class="nav-item" id="profile">
              <a href="fprofile.php" class="nav-link">
                <span class="text-white nav-link-inner--text font-weight-bold"
                  ><i class="text-white fas fa-user"></i> <?php echo $para2 ?> </span
                >
              </a>
            </li>
			
			
		  
		   <li class="nav-item">
              <a href="flogout.php" class="nav-link">
                <span class="text-white nav-link-inner--text font-weight-bold"
                  ><i class="text-danger fas fa-power-off"></i> Logout </span
                >
              </a>
            </li>



          </ul>
        </div>
      </div>
    </nav>
	
	

<style>
/* Navbar dropdown hover behavior */
.navbar .dropdown-menu {
  display: none;
  opacity: 0;
  visibility: hidden;
  transform: translateY(10px);
  transition: all 0.3s ease;
  position: absolute;
  margin-top: 0 !important;
}

.navbar .nav-item.dropdown:hover > .dropdown-menu,
.navbar .dropdown-menu:hover {
  display: block;
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

.navbar .nav-item.dropdown {
  position: relative;
}

/* Invisible hover bridge to prevent cursor gap closing */
.navbar .nav-item.dropdown::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  height: 15px;
  z-index: 999;
}

/* Ensure dropdown items are clickable */
.navbar .dropdown-menu,
.navbar .dropdown-item {
  pointer-events: auto;
  position: relative;
  z-index: 10000;
}

.topnav a {
  border-bottom: 3px solid transparent;
}

.topnav a:hover {
  border-bottom: 3px solid red;
}

.topnav a.activa {
  border-bottom: 3px solid red;
}

</style>	
	
  <script>
$("#nav li a").each(function() {   
    if (this.href == window.location.href) {
        $(this).addClass("activaa");
    }
});
  </script>