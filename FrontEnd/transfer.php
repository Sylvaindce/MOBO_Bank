<!--
AUTHOR: DECOMBE Sylvain
-->
<?php
  $userid = $_GET['userid'];
  $url = "http://localhost:1234/user?userid=".$userid;
  $json = file_get_contents($url);
  $json_data = json_decode($json, true);

  $path = "/~Sylvain/database/DatabaseFront/";
?>
<html>
<head>
 <meta charset="utf-8">
 <meta name="author" content="DECOMBE Sylvain">
 <title>MoboBank - Transfer</title>
 <link rel="stylesheet" href="css/style.css">
 <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
</head>
<body>
 <div style="display: flex; text-align: left;">
  <div style="display: block;float: left;width: 50%;margin-left: 5%;">
    <img src="img/logo.png" style="height: auto !important; width: 40% !important; padding: 15px 15px 15px 0px;">
  </div>
  <div style="display: block;float: left;width: 50%;margin-right: 5% !important;text-align: right; margin: auto;">
    <h4 style="color: black;"><span style="color: #53a9ee;" class="icon fa-user-circle-o"></span> Hi <?php echo $json_data['firstname']." ".$json_data['lastname'] ?></h4>
    <a href="<?php echo $path; ?>">Log Out</a>
  </div>
</div>
<div style="background-color: #53a9ee; padding: 5%; border-radius: 15px; color: #384047; margin-left: 5%; margin-right: 5%;margin-bottom: 5%; display: inline-block; width: 90%;">
  <a href=<?php echo $path."home.php?userid=".$json_data['userid'] ?>><p style="color: white;font-size: 25px;"><span class="icon fa-arrow-left" style="color: white;font-size: 40px;"></span> Back</p></a>
  <div style="display: flex; text-align: center;">
    <div style="display: block; float: left; width: 33%; border: 4px solid white; border-radius: 10px; padding: 30px; margin: 10px;">
      <h4><span style="color: white; font-size: 40px !important;" class="icon fa-user"></span> <?php echo $json_data['firstname']." ".$json_data['lastname'] ?></h4>
    </div>
    <div style="display: block; float: left; width: 33%; padding: 30px; margin: 10px; text-align: left;">
      <h4><span style="color: white;" class="icon fa-diamond"></span> Balance:</h4>
      <p style="color: white;" id="mybalance"></p>
    </div>
    <div style="display: block; float: left; width: 33%; padding: 30px; margin: 10px; text-align: left;">
      <h4><span style="color: white;" class="icon fa-university"></span> Account number :</h4>
      <p style="color: white;"><?php echo $json_data['account'] ?></p>
    </div>
  </div>
  <div style="margin-top: 4%;">
    <h4><span style="color: white;" class="icon fa-info-circle"></span> Transfer informations:</h4>
  </div>
  <div style="width: 98% !important;" class="container">
      <p style="color: red;line-height: 20px;height: 20px;margin-top: 25px; text-align: center;" id="error_transfer"></p>
      <div style="width: 48%; float: left;padding: 20px">
        <label for="toaccount">Account:</label>
        <input type="text" id="toaccount" name="toaccount">
        <p style="color: red;line-height: 20px;height: 20px;" id="error_api"></p>
      </div>
      <div style="width: 48%; float: left; padding: 20px">
        <label for="tamount">Amount:</label>
        <input type="number" id="tamount" name="tamount" value="0">
      </div>
    <button id="transferButton" class="showmore" style="margin-top: 40px;">Transfer</button>
  </div>
</div>
</body>
<script type="text/javascript">
    $(document).ready(function() {
      var userid = getUrlParameter('userid');
      getBalance(userid);
      $(document).on("click", "#transferButton", function() {
        verifAmount(userid);
      });
    });


    var getUrlParameter = function getUrlParameter(sParam) {
      var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

      for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
      }
    };

    function verifAmount(userid) {
      ourbalance = $('#mybalance').text();
      ourbalance = ourbalance.replace(" CNY", "");
      inputbalance = $('#tamount').val();
      if (inputbalance > ourbalance){
        $('#error_transfer').text("Error, entered amount superior to balance.");
      } else {
        $('#error_transfer').text("");
        toaccount = $('#toaccount').val();
        console.log(toaccount);
        if(toaccount.length > 0) {
          $('#error_api').text("");
          getFromAPI(userid, inputbalance, toaccount);
        } else {
          $('#error_api').text("Error, please enter an account number");
        }
      }
    }

    function getBalance(userid) {
      var url = "http://localhost:1234/balance?userid="+userid;
      $.getJSON(url, function(data) {
        //console.log("success");
      })
        .done(function(data) {
          console.log(data);
          var obj = $.parseJSON(JSON.stringify(data));
          $('#mybalance').text(obj.balance+" CNY");
          
        })
        .fail(function(data) {
          console.log("Error, unable to connect to remote server.");
        })
        .always(function(data) {
          console.log( "complete" );
      });
    }

    function getFromAPI(userid, tbalance, taccount) {
      var url = "http://localhost:1234/transfer?userid="+userid+"&balance="+tbalance+"&account="+taccount;

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
            $('#error_api').text("Error, incorrect information, please check the account number.");
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