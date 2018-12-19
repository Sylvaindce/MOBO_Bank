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

  $allDate = str_replace('"', '', $jsonTransactionData['date']);
  $allDate = str_replace('[', '', $allDate);
  $allDate = str_replace(']', '', $allDate);
  $allDate = str_replace('.0', '', $allDate);
  $allDate = split(',', $allDate);

  $allAccount = str_replace('"', '', $jsonTransactionData['account']);
  $allAccount = str_replace('[', '', $allAccount);
  $allAccount = str_replace(']', '', $allAccount);
  $allAccount = split(',', $allAccount);

?>
<html>
<head>
 <meta charset="utf-8">
 <meta name="author" content="DECOMBE Sylvain">
 <title>MoboBank - Transactions</title>
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
      <h4><span style="color: white;" class="icon fa-university"></span> Account number:</h4>
      <p style="color: white;"><?php echo $json_data['account'] ?></p>
    </div>
  </div>
  <div style="margin-top: 4%;">
    <h4><span style="color: white;" class="icon fa-info-circle"></span> Transactions:</h4>
  </div>
  <div style="width: 98% !important;" class="container">
    <ul>
      <li><span style='width:33%; float: left; text-align: left;'>Date</span><span style='width:33%; float: left; text-align: center;'>Amount</span><span style='width:33%; float: left; text-align: right;'>Account N°</span></li>
    <?php
      for($i = count($allAmount)-1; $i >= 0; --$i) {
        echo "<li id=trans".$i."><span style='width:33%; float: left; text-align: left;'>".$allDate[$i]."</span><span style='width:33%; float: left; text-align: center;'>".$allAmount[$i]."</span><span style='width:33%; float: left; text-align: right;'>".$allAccount[$i]."</span></li>";
      }
    ?>
    </ul>
  </div>
</div>
</body>
<script type="text/javascript">
    $(document).ready(function() {
      var userid = getUrlParameter('userid');
      getBalance(userid);
      //getFromAPI(userid);
    });


    String.prototype.replaceAll = function(str1, str2, ignore) 
    {
      return this.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g,"\\$&"),(ignore?"gi":"g")),(typeof(str2)=="string")?str2.replace(/\$/g,"$$$$"):str2);
    } 

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


    function getFromAPI(userid) {
      var url = "http://localhost:1234/transaction?userid="+userid;

      var redirection = "/~Sylvain/database/DatabaseFront"

      $.getJSON(url, function(data) {
        //console.log("success");
      })
        .done(function(data) {
          var obj = $.parseJSON(JSON.stringify(data));
          console.log(obj);
          
          allAmount = obj.amount.replaceAll('"', '');
          allAmount = allAmount.replaceAll('[', '');
          allAmount = allAmount.replaceAll(']', '');
          allAmount = allAmount.split(',');

          allDate = obj.date.replaceAll('"', '');
          allDate = allDate.replaceAll('[', '');
          allDate = allDate.replaceAll(']', '');
          allDate = allDate.replaceAll('.0', '');
          allDate = allDate.split(',');

          allAccount = obj.account.replaceAll('"', '');
          allAccount = allAccount.replaceAll('[', '');
          allAccount = allAccount.replaceAll(']', '');
          allAccount = allAccount.split(',');

          console.log(allAmount);
          console.log(allDate);
          console.log(allAccount);
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