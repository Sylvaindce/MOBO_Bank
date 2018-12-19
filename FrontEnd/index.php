<!--
AUTHOR: DECOMBE Sylvain
-->
<html>
  <head>
  	<meta charset="utf-8">
    <meta name="author" content="DECOMBE Sylvain">
    <title>MoboBank - Login</title>
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
  </head>
  <body>
  	<div style="display: block; text-align: center;">
  		<img src="img/logo.png" style="height: 20% !important; width: auto !important; margin-bottom: 5%; margin-top: 5%;">
  	</div>
  	<div style="background-color: #53a9ee; padding: 5%; border-radius: 15px; color: #384047; margin-left: 5%; margin-right: 5%; margin-bottom: 5%;display: inline-block; width: 90%;">
  		<div style="display: flex;">
  			<div class="container">
        <p style="color: red;line-height: 20px;height: 20px;text-align: center;" id="error_api"></p>
  			<form>
  				<label style="margin-top: 30px;" for="login">Email:</label>
  				<input type="text" id="login" name="login">
          <p style="color: red;line-height: 20px;height: 20px;margin-bottom: 25px;" id="error_email"></p>
          <label for="password">Password:</label>
          <input type="password" id="password" name="password">
          <p style="color: red;line-height: 20px;height: 20px;margin-bottom: 25px;" id="error_password"></p>
        </form>
            <button id="login_button" class="login">Log In</button>
          	</div>
          	<div class="container" style="text-align: center; background: none !important; border-left: 2px solid white; border-radius: 0px !important;margin: auto;">
  				    <a href="/~Sylvain/database/DatabaseFront/signup.php"><button>Sign Up</button></a>
  			</div>
  		</div>
  	</div>
  </body>
  <script type="text/javascript">
    $(document).ready(function() {
      $(document).on("click", "#login_button", function() {
        login = $('#login').val();
        password = $('#password').val();
        if (verifEmail(login) == 0 && verifPassword(password) == 0) {
          getFromAPI(login, password);
        }
      });
    });

    function verifPassword(password) {
      if (password == '') {
        $('#error_password').text("Error, please enter your password.");
        return -1;
      } else {
        $('#error_password').text("");
        return 0;
      }
    }

    function verifEmail(email) {
      var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
               
      if(email == '') {
        $('#error_email').text("Error, please enter your email address.");
        return -1;
      } else if(!emailReg.test(email)) {
        $('#error_email').text("Error, this is not a valid email address.");
        return -1;
      } else {
        $('#error_email').text("");
        return 0;
      }
    }

    function getFromAPI(login, password) {
      var url = "http://localhost:1234/login?email="+login+"&password="+password;

      var redirection = "/~Sylvain/database/DatabaseFront"

      $.getJSON(url, function(data) {
        //console.log("success");
      })
        .done(function(data) {
          console.log(data);
          var obj = $.parseJSON(JSON.stringify(data));
          if (obj.state == 0 && obj.userid != 0 && obj.role == 1) {
            $('#error_api').text("");
            console.log(obj.state+obj.userid);
            window.location.replace(redirection+"/home.php?userid="+obj.userid);
          } else if (obj.state == 0 && obj.userid != 0 && obj.role == 2) {
            $('#error_api').text("");
            console.log("Admin");
            window.location.replace(redirection+"admin/home.php?userid="+obj.userid);
          }
          else {
            $('#error_api').text("Error, incorrect information, please check your email and your password.");
          }
        })
        .fail(function(data) {
          console.log(data);
          $('#error_api').text("Error, unable to connect to remote server.");
        })
        .always(function(data) {
          //console.log(data);
          console.log( "complete" );
      });
    }
  </script>
</html>
