<!--
AUTHOR: DECOMBE Sylvain
-->
<html>
<head>
 <meta charset="utf-8">
 <meta name="author" content="DECOMBE Sylvain">
 <title>MoboBank - Sign Up</title>
 <link rel="stylesheet" href="css/style.css">
 <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
</head>
<body>
 <div style="display: block; text-align: center;">
  <img src="img/logo.png" style="height: 20% !important; width: auto !important; margin-bottom: 5%; margin-top: 5%;">
</div>
<div style="background-color: #53a9ee; padding: 5%; border-radius: 15px; color: #384047; margin-left: 5%; margin-right: 5%; margin-bottom: 5%;display: inline-block; width: 90%;">
  <div style="display: flex;">
   <div class="container" style="width: 100% !important;">
   <h3 style="color: black;text-align: center;">Sign up</h3>
   <p style="color: red;line-height: 20px;height: 20px;text-align: center;" id="error_api"></p>
    <form>
      <div style="width: 48%; float: left;padding: 20px">
        <label for="firstname">Firstname:</label>
        <input type="text" id="firstname" name="firstname">
        <p style="color: red;line-height: 20px;height: 20px;margin-bottom: 25px;" id="error_firstname"></p>
        <label style="margin-top: 30px;" for="lastname">Lastname:</label>
        <input type="text" id="lastname" name="lastname">
        <p style="color: red;line-height: 20px;height: 20px;margin-bottom: 25px;" id="error_lastname"></p>
        <label style="margin-top: 30px;" for="phone">Phone:</label>
        <input type="text" id="phone" name="phone">
        <p style="color: red;line-height: 20px;height: 20px;margin-bottom: 25px;" id="error_phone"></p>
        <label style="margin-top: 30px;" for="address">Address:</label>
        <input type="text" id="address" name="address">
        <p style="color: red;line-height: 20px;height: 20px;margin-bottom: 25px;" id="error_address"></p>
      </div>
      <div style="width: 48%; float: left; padding: 20px">
        <label for="email">Email:</label>
        <input type="text" id="email" name="email">
        <label for="cemail">Confirm email:</label>
        <input type="text" id="cemail" name="cemail">
        <p style="color: red;line-height: 20px;height: 20px;margin-bottom: 25px;" id="error_email"></p>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <label for="cpassword">Confirm password:</label>
        <input type="password" id="cpassword" name="cpassword">
        <p style="color: red;line-height: 20px;height: 20px;margin-bottom: 25px;" id="error_password"></p>
      </div>
    </form>
    <button id="signup_button" style="width: 100% !important;">Sign Up</button>
  </div>
</div>
</div>
</body>
<script type="text/javascript">
    $(document).ready(function() {
      $(document).on("click", "#signup_button", function() {
        if (checkSomeInput() == 0 && checkPhone() == 0 && verifEmail() == 0 && checkPassword() == 0) {
          getFromAPI();
        }
      });
    });

    function check_length(str) {
      if (str.length > 1 && str.length < 300)
        return 0;
      return -1;
    }

    function checkSomeInput() {
      var i = 0;
      if (check_length($('#firstname').val()) == -1) {
          $('#error_firstname').text("Error, please enter your firstname.");
          ++i;
        } else if (check_length($('#firstname').val()) == 0) {
          $('#error_firstname').text("");
        }
        if (check_length($('#lastname').val()) == -1) {
          $('#error_lastname').text("Error, please enter your lastname.");
          ++i;
        } else if (check_length($('#lastname').val()) == 0) {
          $('#error_lastname').text("");
        }
        if (check_length($('#address').val()) == -1) {
          $('#error_address').text("Error, please enter your address.");
          ++i;
        } else if (check_length($('#address').val()) == 0) {
          $('#error_address').text("");
        }
      if (i == 0)
        return 0;
      return -1;
    }

    function verifEmail() {
      email = $('#email').val();
      cemail = $('#cemail').val();
      var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
               
      if(email == '') {
        $('#error_email').text("Error, please enter your email address.");
        return -1;
      } else if(!emailReg.test(email)) {
        $('#error_email').text("Error, this is not a valid email address.");
        return -1;
      } else {
        if (cemail == '') {
          $('#error_email').text("Error, please enter the confirmation email.");
          return -1;
        } else if (cemail !== email) {
          $('#error_email').text("Error, the confirmation email and the email don't match.");
          return -1;
        }
        $('#error_email').text("");
        return 0;
      }
    }

    function checkPassword() {
      password = $('#password').val();
      cpassword = $('#cpassword').val();
      if(password == '') {
        $('#error_password').text("Error, please enter your password.");
        return -1;
      } else {
        if (cpassword == '') {
          $('#error_password').text("Error, please enter the confirmation password.");
          return -1;
        } else if (password !== cpassword) {
          $('#error_password').text("Error, the confirmation password and the password don't match.");
          return -1;
        }
        $('#error_password').text("");
        return 0;
      }
    }

    function checkPhone() {
      phone = $('#phone').val();
      intRegex = /[0-9 -()+]+$/;
      if((phone.length < 6) || (!intRegex.test(phone)))
      {
          $('#error_phone').text("Error, please enter your phone number.");
          return -1;
      }
      $('#error_phone').text("");
      return 0;
    }

    function getFromAPI() {
      var url = "http://localhost:1234/signup?firstname="+$('#firstname').val()+"&lastname="+$('#lastname').val()+"&phone="+$('#phone').val()+"&address="+$('#address').val()+"&email="+$('#email').val()+"&password="+$('#password').val();

      var redirection = "/~Sylvain/database/DatabaseFront"

      $.getJSON(url, function(data) {
        //console.log("success");
      })
        .done(function(data) {
          console.log(data);
          var obj = $.parseJSON(JSON.stringify(data));
          if (obj.state == 0 && obj.userid != 0) {
            $('#error_api').text("");
            console.log(obj.state+obj.userid);
            window.location.replace(redirection+"/home.php?userid="+obj.userid);
          }
          else {
            $('#error_api').text("Error, incorrect informations.");
          }
        })
        .fail(function(data) {
          $('#error_api').text("Error, unable to connect to remote server.");
        })
        .always(function(data) {
          //console.log(data);
          console.log( "complete" );
      });
    }

</script>
</html>