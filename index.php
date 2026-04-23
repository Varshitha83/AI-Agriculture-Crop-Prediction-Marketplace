<?php
require_once __DIR__ . "/config.php";
// Decide which language to use (en = English, te = Telugu)
$lang = isset($_GET['lang']) && isset($LANG_STRINGS[$_GET['lang']])
    ? $_GET['lang']
    : $DEFAULT_LANGUAGE;
$t = $LANG_STRINGS[$lang];
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="assets/img/logo.png" />
  <title><?php echo isset($t['site_title']) ? $t['site_title'] : $SITE_NAME; ?></title>

  <!-- Bootstrap + Fonts + Icons -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">

  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p"
        crossorigin="anonymous"/>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.min.css"/>

  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">

  <!-- Nucleo Icons -->
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />

  <!-- CreativeTim Theme -->
  <link rel="stylesheet" href="assets/css/creativetim.min.css" type="text/css">

  <!-- Fix overlay click blocking and navbar stacking context -->
  <style>
    /* Overlay layer in header should not block link clicks */
    header.jumbotron > div:not(.container) {
      pointer-events: none !important;
    }

    /* Ensure sticky navbar stays above background and overlay layers */
    #navbar-main {
      z-index: 9999;
    }

    /* safeguard pseudo-element overlays if added by background effects */
    header.jumbotron::before,
    header.jumbotron::after,
    #navbar-main::before,
    #navbar-main::after {
      pointer-events: none !important;
    }

    /* Ensure navbar is above all elements */
    nav, .navbar {
      position: relative;
      z-index: 9999;
    }

    /* Ensure anchor tags are clickable */
    nav a {
      pointer-events: auto;
    }

    /* Ensure buttons and inputs are clickable */
    input, button, select, textarea {
      pointer-events: auto !important;
    }

    /* Hide dropdown by default, override Bootstrap defaults */
    .navbar .dropdown-menu,
    .navbar-nav .dropdown-menu {
      display: none;
      position: absolute;
      opacity: 0;
      visibility: hidden;
      margin-top: 0.2rem;
      transition: opacity 150ms ease, transform 150ms ease, visibility 150ms ease;
      transform: translateY(-5px);
    }

    /* Prevent overlap */
    .navbar .nav-item.dropdown {
      position: relative;
    }

    /* Show dropdown on hover */
    .navbar .nav-item.dropdown:hover > .dropdown-menu,
    .navbar-nav .dropdown:hover > .dropdown-menu,
    .navbar-nav .dropdown.show > .dropdown-menu {
      display: block;
      opacity: 1;
      transform: translateY(0);
      visibility: visible;
    }

    .navbar-nav .dropdown:hover > a,
    .navbar-nav .dropdown.show > a {
      color: #fff;
    }
  </style>
</head>

<body class="bg-white" id="top">

  <!-- Navbar -->
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
      <div class="container">
        <ul class="navbar-nav navbar-nav-hover align-items-lg-center">
          <li class="nav-item dropdown">
            <a href="index.php" class="navbar-brand mr-lg-5 text-white">
              <img src="assets/img/nav.png" />
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
                <a href="index.php">
                  <img src="assets/img/nav.png" />
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

          <ul class="navbar-nav align-items-lg-center ml-auto">

            <li class="nav-item">
              <a href="contact.php" class="nav-link">
                <span class="text-white nav-link-inner--text">
                  <i class="text-white fas fa-address-card"></i> Contact
                </span>
              </a>
            </li>

            <li class="nav-item">
              <div class="dropdown">
                <a class="nav-link dropdown-toggle text-white " href="farmer/fregister.php" role="button" id="dropdownMenuLink1"
                   aria-haspopup="true" aria-expanded="false">
                  <span class="text-white nav-link-inner--text">
                    <i class="text-white fas fa-user-plus"></i> Sign Up
                  </span>
                </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                  <a class="dropdown-item" href="farmer/fregister.php">Farmer</a>
                  <a class="dropdown-item" href="customer/cregister.php">Customer</a>
                </div>
              </div>
            </li>

            <li class="nav-item">
              <div class="dropdown">
                <a class="nav-link dropdown-toggle text-white " href="admin/alogin.php" role="button" id="dropdownMenuLink2"
                   aria-haspopup="true" aria-expanded="false">
                  <span class="text-white nav-link-inner--text">
                    <i class="text-white fas fa-sign-in-alt"></i> Login
                  </span>
                </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink2">
                  <a class="dropdown-item" href="farmer/flogin.php">Farmer</a>
                  <a class="dropdown-item" href="customer/clogin.php">Customer</a>
                  <a class="dropdown-item" href="admin/alogin.php">Admin</a>
                </div>
              </div>
            </li>

          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->

<div class="wrapper">

  <div class="wrapper">
    <header class="jumbotron" style="background-image: url('assets/img/hero.jpeg'); background-size: cover; background-position: center; background-attachment: scroll; min-height: 600px; position: relative; display: flex; align-items: center;">
      <!-- Dark overlay for better text readability -->
      <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.4);"></div>
      
      <div class="container" style="position: relative; z-index: 1;">
        <div class="row row-header">
          <div class="col-12 col-sm-6">
            <h1 class="text-white">Agriculture Portal</h1>
            <p class="text-white">
              A True Farmer's Friend.
            </p>
            <div class="cg">
              <div class="card card-body" style="background-color: white;">
                <blockquote cite="blockquote">
                  <h6 class="mb-0 text-dark">
                    <span id="quote">
                      Farming looks mighty easy when your plow is a pencil, and you're a thousand miles from the corn field.
                    </span>
                  </h6>
                  <br />
                  <footer class="blockquote-footer vg text-dark">
                    <span id="author">— DWIGHT D. EISENHOWER</span>
                  </footer>
                </blockquote>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- FEATURES SECTION -->
    <div class="section features-6 text-dark bg-white" id="services">
      <div class="container">

        <div class="row">
          <div class="col-md-8 mx-auto text-center">
            <span class="badge badge-primary badge-pill mb-3">Insight</span>
            <h3 class="display-3">Features</h3>
          </div>
        </div>
        <br>

        <div class="row align-items-center">

          <div class="col-lg-6">
            <div class="info info-horizontal info-hover-success">
              <div class="description pl-4">
                <h3 class="title">Farmers</h3>
                <p>Farmers can get recommendations for crop n fertilizer and even
                  predict the weather and get the news related to agriculture. Farmers can directly sell the crops to the customers.</p>
              </div>
            </div>
          </div>

          <div class="col-lg-6 col-10 mx-md-auto d-none d-md-block">
            <img class="ml-lg-5 pull-right" src="assets/img/agri.png" width="100%">
          </div>
        </div>

        <div class="row align-items-center">
          <div class="col-lg-6 col-10 mx-md-auto d-none d-md-block">
            <img class="ml-lg-5" src="assets/img/customers.png" width="80%">
          </div>

          <div class="col-lg-6">
            <div class="info info-horizontal info-hover-warning mt-5">
              <div class="description pl-4">
                <h3 class="title">Customers</h3>
                <p>Customers can buy crops directly from the faarmers without the involvement of any middlemen.</p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- MORE FEATURES -->
    <div class="section features-2 text-dark bg-white" id="features">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-5 col-md-8 mr-auto text-left">
            <div class="pr-md-5">
              <div class="icon icon-lg icon-shape icon-shape-primary shadow rounded-circle mb-5">
                <i class="ni ni-favourite-28"></i>
              </div>
              <h3 class="display-3 text-justify">Features</h3>
              <p>The time is now for the next step in farming. We bring you the future of farming along with great tools for assisting the farmers.</p>
              <ul class="list-unstyled mt-5">
                <li class="py-2">
                  <div class="d-flex align-items-center">
                    <div>
                      <div class="badge badge-circle badge-primary mr-3">
                        <i class="ni ni-settings-gear-65"></i>
                      </div>
                    </div>
                    <div>
                      <h6 class="mb-0">Highly Reliable and Accurate.</h6>
                    </div>
                  </div>
                </li>
                <li class="py-2">
                  <div class="d-flex align-items-center">
                    <div>
                      <div class="badge badge-circle badge-primary mr-3">
                        <i class="ni ni-html5"></i>
                      </div>
                    </div>
                    <div>
                      <h6 class="mb-0">Faster & Responsive website.</h6>
                    </div>
                  </div>
                </li>
                <li class="py-2">
                  <div class="d-flex align-items-center">
                    <div>
                      <div class="badge badge-circle badge-primary mr-3">
                        <i class="ni ni-settings-gear-65"></i>
                      </div>
                    </div>
                    <div>
                      <h6 class="mb-0">Real time weather forecast.</h6>
                    </div>
                  </div>
                </li>
                <li class="py-2">
                  <div class="d-flex align-items-center">
                    <div>
                      <div class="badge badge-circle badge-primary mr-3">
                        <i class="ni ni-satisfied"></i>
                      </div>
                    </div>
                    <div>
                      <h6 class="mb-0">Integrated news feature.</h6>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-lg-7 col-md-12 pl-md-0">
            <img class="img-fluid ml-lg-5" src="assets/img/features.png" width="100%">
          </div>

        </div>
      </div>
      <span></span>
    </div>

    <!-- TECHNOLOGIES -->
    <div class="section features-6 text-dark bg-white" id="tech">
      <div class="container-fluid shado">

        <div class="row">
          <div class="col-md-8 mx-auto text-center">
            <span class="badge badge-primary badge-pill mb-3">stack</span>
            <h3 class="display-3">Technologies Used</h3>
            <p>Our Development Stack</p>
          </div>
        </div>

        <div class="row text-lg-center align-self-center">
          <div class="col-md-4">
            <div class="info">
              <img class="img-fluid" src="assets/img/html.png" alt="HTML5">
              <h6 class="info-title text-uppercase text-primary">HTML5</h6>
            </div>
          </div>

          <div class="col-md-4">
            <div class="info">
              <img class="img-fluid" src="assets/img/css3.png" alt="CSS3">
              <h6 class="info-title text-uppercase text-primary">CSS3</h6>
            </div>
          </div>

          <div class="col-md-4">
            <div class="info">
              <img class="img-fluid" src="assets/img/js.png" alt="JavaScript">
              <h6 class="info-title text-uppercase text-primary">JavaScript</h6>
            </div>
          </div>
        </div>

        <div class="row text-center">
          <div class="col-md-4 d-none d-md-block">
            <div class="info">
              <img class="img-fluid" src="assets/img/bootstrap.png" alt="BootStrap4">
              <h6 class="info-title text-uppercase text-primary">BootStrap4</h6>
            </div>
          </div>

          <div class="col-md-4 d-none d-md-block">
            <div class="info">
              <img class="img-fluid" src="assets/img/apache.png" alt="Apache">
              <h6 class="info-title text-uppercase text-primary">Apache</h6>
            </div>
          </div>

          <div class="col-md-4 d-none d-md-block">
            <div class="info">
              <img class="img-fluid" src="assets/img/mysql.png" alt="MySQL">
              <h6 class="info-title text-uppercase text-primary">MySQL</h6>
            </div>
          </div>
        </div>

        <div class="row text-center">
          <div class="col-md-4 d-none d-md-block">
            <div class="info">
              <img class="img-fluid" src="assets/img/jquery.png" alt="JQUERY">
              <h6 class="info-title text-uppercase text-primary">JQUERY</h6>
            </div>
          </div>

          <div class="col-md-4 d-none d-md-block">
            <div class="info">
              <img class="img-fluid" src="assets/img/openai2.png" alt="OpenAI">
              <h6 class="info-title text-uppercase text-primary">OPEN AI</h6>
            </div>
          </div>

          <div class="col-md-4 d-none d-md-block">
            <div class="info">
              <img class="img-fluid" src="assets/img/php2.png" alt="PHP">
              <h6 class="info-title text-uppercase text-primary">PHP</h6>
            </div>
          </div>
        </div>

      </div>
    </div>

<?php require("footer.php"); ?>

<!-- =================  SCRIPTS  ================= -->

<!-- Bootstrap JS (and dependencies if needed) -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha384-tsQFqpERiu2vYk3U5rKOB1p5YDoAuCEr0aKBslrYJBv3h5Gk5tS3Xm9Og8ifwB6M"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"
        integrity="sha384-vjEeS7cQkP/d9vywgq6Z9erjRzCQXDpUe1koRaSPo6e7/jT730GpShypHbOWcT2T"
        crossorigin="anonymous"></script>

<!-- OPENAI QUOTE SCRIPT -->
<script>
  const quoteEl  = document.getElementById("quote");
  const authorEl = document.getElementById("author");

  const fallbackQuotes = [
    { text: "Agriculture is the most healthful, most useful and most noble employment of man.", author: "George Washington" },
    { text: "The farmer has to be an optimist or he wouldn't still be a farmer.", author: "Will Rogers" },
    { text: "Farming looks mighty easy when your plow is a pencil, and you're a thousand miles from the corn field.", author: "Dwight D. Eisenhower" },
    { text: "The ultimate goal of farming is not the growing of crops, but the cultivation and perfection of human beings.", author: "Masanobu Fukuoka" },
    { text: "Agriculture is the backbone of every nation.", author: "Unknown" }
  ];

  let typingTimeout = null;

  function typeQuote({ text, author }) {
    // Cancel any ongoing typing animation
    if (typingTimeout) {
      clearTimeout(typingTimeout);
      typingTimeout = null;
    }

    quoteEl.textContent = '';
    authorEl.textContent = '';

    let i = 0;
    const typeSpeed = 30; // ms per character

    function type() {
      if (i < text.length) {
        quoteEl.textContent += text[i];
        i++;
        typingTimeout = setTimeout(type, typeSpeed);
      } else {
        // Typing finished, show author instantly
        authorEl.textContent = author ? "— " + author : "—";
        typingTimeout = null;
      }
    }

    type();
  }

  function getRandomFallback() {
    return fallbackQuotes[Math.floor(Math.random() * fallbackQuotes.length)];
  }

  function applyFallback() {
    const fallback = getRandomFallback();
    typeQuote(fallback);
  }

  const QUOTE_INTERVAL_MS = 10000;
  let lastQuoteText = "";

  async function fetchQuoteFromApi() {
    try {
      const response = await fetch("quote_api.php", {
        method: "POST" // or "GET" – we don't send any body
      });

      if (!response.ok) {
        return null;
      }

      const data = await response.json();
      const content = data && data.content ? data.content.trim() : "";

      if (!content) {
        return null;
      }

      // Expecting format: "Quote text - Author name"
      let quoteText = content;
      let authorText = "";

      const splitIndex = content.lastIndexOf(" - ");
      if (splitIndex !== -1) {
        quoteText = content.slice(0, splitIndex).replace(/^"|"$/g, "").trim();
        authorText = content.slice(splitIndex + 3).trim();
      }

      if (!quoteText) {
        return null;
      }

      return { text: quoteText, author: authorText };
    } catch (err) {
      console.error("Error fetching quote from backend:", err);
      return null;
    }
  }

  async function loadFarmingQuote() {
    // Keep current quote visible while fetching new one.
    const maxRetries = 3;
    for (let attempt = 0; attempt < maxRetries; ++attempt) {
      const quote = await fetchQuoteFromApi();
      if (!quote) {
        // If fetch fails, use fallback immediately (no need to retry the remote API)
        applyFallback();
        return;
      }

      if (quote.text !== lastQuoteText) {
        lastQuoteText = quote.text;
        typeQuote(quote);
        return;
      }

      // If we got the same quote as last time, retry a few times before falling back
    }

    // If we still have no new quote after retries, use a local fallback
    applyFallback();
  }

  // Run automatically on page load and refresh at interval
  window.addEventListener("load", () => {
    loadFarmingQuote();
    setInterval(loadFarmingQuote, QUOTE_INTERVAL_MS);
  });
</script>

</body>
</html>
