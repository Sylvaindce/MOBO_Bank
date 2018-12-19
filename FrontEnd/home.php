<!--
AUTHOR: DECOMBE Sylvain
-->
<?php
  $userid = $_GET['userid'];
  $url = "http://localhost:1234/user?userid=".$userid;
  $json = file_get_contents($url);
  $json_data = json_decode($json, true);

  $path = "/~Sylvain/database/DatabaseFront/";

  $url2 = "http://localhost:1234/transaction?userid=".$userid;
  $jsonTransaction = file_get_contents($url2);
  $jsonTransactionData = json_decode($jsonTransaction, true);

  $allAmount = str_replace('"', '', $jsonTransactionData['amount']);
  $allAmount = str_replace('[', '', $allAmount);
  $allAmount = str_replace(']', '', $allAmount);
  $allAmount = split(',', $allAmount);
  $allAmount = array_reverse($allAmount);

  $allDate = str_replace('"', '', $jsonTransactionData['date']);
  $allDate = str_replace('[', '', $allDate);
  $allDate = str_replace(']', '', $allDate);
  $allDate = str_replace('.0', '', $allDate);
  $allDate = split(',', $allDate);
  $allDate = array_reverse($allDate);

  $allAccount = str_replace('"', '', $jsonTransactionData['account']);
  $allAccount = str_replace('[', '', $allAccount);
  $allAccount = str_replace(']', '', $allAccount);
  $allAccount = split(',', $allAccount);
  $allAccount = array_reverse($allAccount);
?>
<html>
<head>
 <meta charset="utf-8">
 <meta name="author" content="DECOMBE Sylvain">
 <title>MoboBank - Home</title>
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
    <a href="<?php echo $path ?>">Log Out</a>
  </div>
</div>
<div style="background-color: #53a9ee; padding: 5%; border-radius: 15px; color: #384047; margin-left: 5%; margin-right: 5%;margin-bottom: 5%; display: inline-block; width: 90%;">
  <div style="display: flex; text-align: center;">
  <a href="<?php echo $path.'transfer.php?userid='.$userid ?>" style="display: block; float: left; width: 33%;">
    <div style="border: 4px solid white; border-radius: 10px; padding: 30px; margin: 10px;">
      <span style="color: white; font-size: 40px !important;" class="icon fa-exchange"></span>
      <h4>Transfer</h4>
    </div>
    </a>
    <div style="display: block; float: left; width: 33%; border: 4px solid white; border-radius: 10px; padding: 30px; margin: 10px;">
      <span style="color: white; font-size: 40px !important;" class="icon fa-diamond"></span>
      <h4>Balance</h4>
      <p style="color: white;" id="mybalance"></p>
    </div>
    <a href="<?php echo $path.'user.php?userid='.$userid ?>" style="display: block; float: left; width: 33%;">
    <div style="border: 4px solid white; border-radius: 10px; padding: 30px; margin: 10px;">
      <span style="color: white; font-size: 40px !important;" class="icon fa-address-card"></span>
      <h4>Account</h4>
    </div>
    </a>
  </div>
  <div style="margin-top: 4%;">
    <h4><span style="color: white; font-size: 40px !important;" class="icon fa-history"></span> Last transactions:</h4>
  </div>
  <div style="width: 98% !important;" class="container">
    <ul>
      <li><span style='width:33%; float: left; text-align: left;'>Date</span><span style='width:33%; float: left; text-align: center;'>Amount</span><span style='width:33%; float: left; text-align: right;'>Account N°</span></li>
    <?php
      if (count($allAmount)>=5)
        $limit = 5;
      else
        $limit = count($allAmount);
      for($i = 0; $i < $limit; ++$i) {
        echo "<li id=trans".$i."><span style='width:33%; float: left; text-align: left;'>".$allDate[$i]."</span><span style='width:33%; float: left; text-align: center;'>".$allAmount[$i]."</span><span style='width:33%; float: left; text-align: right;'>".$allAccount[$i]."</span></li>";
      }
    ?>
    </ul>
    <a href="<?php echo $path.'transaction.php?userid='.$userid; ?>"><button class="showmore">+ Show all</button></a>
  </div>
</div>
</body>
<script type="text/javascript">
    $(document).ready(function() {
      var userid = getUrlParameter('userid');
      getBalance(userid);
      window.setInterval(function(){
        getBalance(userid);
      }, 15000);
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


</script>
</html>