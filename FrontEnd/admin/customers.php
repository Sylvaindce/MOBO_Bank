<!--
AUTHOR: DECOMBE Sylvain
-->
<?php
  $userid = $_GET['userid'];
  $url = "http://localhost:1234/user?userid=".$userid;
  $json = file_get_contents($url);
  $json_data = json_decode($json, true);

  $path = "/~Sylvain/database/DatabaseFront/";

  $url2 = "http://localhost:1234/customers?userid=".$userid;
  $jsonTransaction = file_get_contents($url2);
  $jsonTransactionData = json_decode($jsonTransaction, true);


  $firstnameList = str_replace('"', '', $jsonTransactionData['firstname']);
  $firstnameList = str_replace('[', '', $firstnameList);
  $firstnameList = str_replace(']', '', $firstnameList);
  $firstnameList = split(',', $firstnameList);

  $idList = str_replace('"', '', $jsonTransactionData['id']);
  $idList = str_replace('[', '', $idList);
  $idList = str_replace(']', '', $idList);
  $idList = split(',', $idList);

  $lastnameList = str_replace('"', '', $jsonTransactionData['lastname']);
  $lastnameList = str_replace('[', '', $lastnameList);
  $lastnameList = str_replace(']', '', $lastnameList);
  $lastnameList = split(',', $lastnameList);

  $loginList = str_replace('"', '', $jsonTransactionData['last']);
  $loginList = str_replace('[', '', $loginList);
  $loginList = str_replace(']', '', $loginList);
  $loginList = str_replace('.0', '', $loginList);
  $loginList = split(',', $loginList);

  $emailList = str_replace('"', '', $jsonTransactionData['email']);
  $emailList = str_replace('[', '', $emailList);
  $emailList = str_replace(']', '', $emailList);
  $emailList = split(',', $emailList);

  $accountList = str_replace('"', '', $jsonTransactionData['account']);
  $accountList = str_replace('[', '', $accountList);
  $accountList = str_replace(']', '', $accountList);
  $accountList = split(',', $accountList);

?>
<html>
<head>
 <meta charset="utf-8">
 <meta name="author" content="DECOMBE Sylvain">
 <title>MoboBank - Admin Customers</title>
 <link rel="stylesheet" href="../css/style.css">
 <script type="text/javascript" src="../js/jquery-1.8.3.min.js"></script>
</head>
<body>
 <div style="display: flex; text-align: left;">
  <div style="display: block;float: left;width: 50%;margin-left: 5%;">
    <img src="../img/logo.png" style="height: auto !important; width: 40% !important; padding: 15px 15px 15px 0px;">
  </div>
  <div style="display: block;float: left;width: 50%;margin-right: 5% !important;text-align: right; margin: auto;">
    <h4 style="color: black;"><span style="color: #53a9ee;" class="icon fa-user-circle-o"></span> Hi <?php echo $json_data['firstname']." ".$json_data['lastname'] ?></h4>
    <a href="<?php echo $path; ?>">Log Out</a>
  </div>
</div>
<div style="background-color: #53a9ee; padding: 5%; border-radius: 15px; color: #384047; margin-left: 5%; margin-right: 5%;margin-bottom: 5%; display: inline-block; width: 90%;">
  <a href=<?php echo $path."admin/home.php?userid=".$json_data['userid'] ?>><p style="color: white;font-size: 25px;"><span class="icon fa-arrow-left" style="color: white;font-size: 40px;"></span> Back</p></a>
  <div style="margin-top: 4%;">
    <h4><span style="color: white;" class="icon fa-info-circle"></span> Customers:</h4>
  </div>
  <div style="width: 98% !important;" class="container">
    <ul>
      <li><span style='width:33%; float: left; text-align: left;'>Account N°</span><span style='width:33%; float: left; text-align: center;'>Name</span><span style='width:33%; float: left; text-align: right;'>Last Login</span></li>
    <?php
      for($i = count($firstnameList)-1; $i >= 0; --$i) {
        echo "<a href='".$path."admin/customeradmin.php?userid=".$userid."&cid=".$idList[$i]."'><li id=trans".$i."><span style='width:33%; float: left; text-align: left;'>".$accountList[$i]."</span><span style='width:33%; float: left; text-align: center;'>".$firstnameList[$i]." ".$lastnameList[$i]."</span><span style='width:33%; float: left; text-align: right;'>".$loginList[$i]."</span></li></a>";
      }
    ?>
    </ul>
  </div>
</div>
</body>
<script type="text/javascript">
    $(document).ready(function() {
      var userid = getUrlParameter('userid');
      //getBalance(userid);
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