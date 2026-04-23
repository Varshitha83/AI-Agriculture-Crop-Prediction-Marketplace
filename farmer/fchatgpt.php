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
<?php require_once __DIR__ . "/../config.php"; ?>


<!DOCTYPE html>
<html>
<?php require ('fheader.php');  ?>
 <style>

.chat-box {
  height: 59vh; 
  overflow-y: scroll;
}


		.message {
			margin-bottom: 10px;
			padding: 10px;
			padding-bottom: 0;
			border-radius: 10px;
			display: inline-block;
			max-width: 85%;
		
			
			word-wrap: break-word;
			white-space: normal;
		}

		.left-side {
			background-color: lightgray;
			float: left;
		}

		.right-side {
			background-color: lightgreen;
			float: right;
		}		
			
		.popup {
			position: fixed;
			bottom: 20vh;
			left: 50%;
			transform: translateX(-50%);
			background-color: rgba(0, 0, 0, 0.6);
			color: white;
			border-radius: 5px;
			padding: 10px 20px;
			font-size: 16px;
			display: none;
		}
				
 </style>
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


    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 ">
			
				<div class="card text-white bg-gradient-white mt--6">
				
				<div class="card-header bg-gradient-secondary d-flex">

					<span class="text-default display-4">
						<img src="../assets/img/chatgpt.svg" class="rounded-circle img-fluid" width="" alt="Chat GPT Logo"> Chat GPT
					</span>

					<div class="ml-auto">
						<button class="btn btn-info" onclick="window.print()">Print</button>
						<button class="btn btn-danger mr-2 " onclick="clearContent()">Clear Chat</button>					
					</div>								
				</div>

				 <div class="card-body chat-box rounded p1" id="chatbox"><span id="copy-popup" class="popup">Copied!</span></div>
				 							
							 <div class="card-footer bg-gradient-secondary">
                                <div class="form-group row">
                                    <div class="col-md-10 mb-1">
                                        <input id="userInput" type="text" class="form-control text-dark"
                                            placeholder="Type your message here..." />
                                    </div>

                                    <div class="col-md-2">
                                        <input id="sendButton" type="button" value="SUBMIT" class=" form-control btn btn-success btn-block" />
                                    </div>
                                </div>
                            </div>
							
	 </div>

    </div>
       </div>
	</div>
		
</section>

    <?php require("footer.php");?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function clearContent(){
    document.getElementById('chatbox').innerHTML = '';
}

const chatbox = $("#chatbox");
const userInput = $("#userInput");
const sendButton = $("#sendButton");
let messages = [];

sendButton.on("click", function () {

    let message = userInput.val();

    if (message.trim() === "") return;

    // show user message
    chatbox.append('<div class="message right-side">' + message + '</div>');

    userInput.val("");

    // call backend
    $.ajax({
    url: "chatbot_response.php",
    method: "POST",
    contentType: "application/json",
    data: JSON.stringify({ message: message }),

    success: function (response) {
        let data = JSON.parse(response);
        chatbox.append('<div class="message left-side">' + data.reply + '</div>');
    }
});

});


userInput.on("keydown", (event) => {
    if (event.keyCode === 13 && !event.ctrlKey && !event.shiftKey) {
        event.preventDefault();
        sendButton.click();
    } else if (event.keyCode === 13 && (event.ctrlKey || event.shiftKey)) {
        event.preventDefault();
        const cursorPosition = userInput.prop("selectionStart");
        const currentValue = userInput.val();

        userInput.val(
            currentValue.slice(0, cursorPosition) +
            "\n" +
            currentValue.slice(cursorPosition)
        );
        userInput.prop("selectionStart", cursorPosition + 1);

        userInput.prop("selectionEnd", cursorPosition + 1);
    }
});


 </script>
  
</body>
</html>