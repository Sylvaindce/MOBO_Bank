<!--
AUTHOR: DECOMBE Sylvain
-->
<?php
  $userid = $_GET['userid'];
  $url = "http://localhost:1234/user?userid=".$userid;
  $json = file_get_contents($url);
  $json_data = json_decode($json, true);

  $path = "/~Sylvain/database/DatabaseFront/";

  $url2 = "http://localhost:1234/logs?userid=".$userid;
  $jsonTransaction = file_get_contents($url2);
  $jsonTransactionData = json_decode($jsonTransaction, true);

  $allActions = str_replace('"', '', $jsonTransactionData['action']);
  $allActions = str_replace('[', '', $allActions);
  $allActions = str_replace(']', '', $allActions);
  $allActions = split(',', $allActions);

  $allDate = str_replace('"', '', $jsonTransactionData['date']);
  $allDate = str_replace('[', '', $allDate);
  $allDate = str_replace(']', '', $allDate);
  $allDate = str_replace('.0', '', $allDate);
  $allDate = split(',', $allDate);

  $allFirstname = str_replace('"', '', $jsonTransactionData['firstname']);
  $allFirstname = str_replace('[', '', $allFirstname);
  $allFirstname = str_replace(']', '', $allFirstname);
  $allFirstname = split(',', $allFirstname);

  $allLastname = str_replace('"', '', $jsonTransactionData['lastname']);
  $allLastname = str_replace('[', '', $allLastname);
  $allLastname = str_replace(']', '', $allLastname);
  $allLastname = split(',', $allLastname);

?>
<html>
<head>
 <meta charset="utf-8">
 <meta name="author" content="DECOMBE Sylvain">
 <title>MoboBank - Admin Home</title>
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
    <a href="<?php echo $path ?>">Log Out</a>
  </div>
</div>
<div style="background-color: #53a9ee; padding: 5%; border-radius: 15px; color: #384047; margin-left: 5%; margin-right: 5%;margin-bottom: 5%; display: inline-block; width: 90%;">
  <div style="display: flex; text-align: center;">
    <div style="border-radius: 10px; padding: 30px; margin: 10px;display: block; float: left; width: 33%;">
    </div>
    <a href="<?php echo $path.'admin/customers.php?userid='.$userid; ?>" style="display: block; float: left; width: 33%; ">
    <div style="border: 4px solid white; border-radius: 10px; padding: 30px; margin: 10px;">
      <span style="color: white; font-size: 40px !important;" class="icon fa-users"></span>
      <h4>Customers</h4>
    </div>
    </a>
    <div style="border-radius: 10px; padding: 30px; margin: 10px;display: block; float: left; width: 33%;">
    </div>
  </div>
  <div style="margin-top: 4%;">
    <h4><span style="color: white; font-size: 40px !important;" class="icon fa-file-text-o"></span> Last logs:</h4>
  </div>
  <div style="width: 98% !important;" class="container">
    <ul>
      <li><span style='width:33%; float: left; text-align: left;'>Date</span><span style='width:33%; float: left; text-align: center;'>Action</span><span style='width:33%; float: left; text-align: right;'>Name</span></li>
    <?php
      if (count($allDate)>=5)
        $limit = 5;
      else
        $limit = count($allDate);
      for($i = 0; $i < $limit; ++$i) {
        echo "<li id=trans".$i."><span style='width:33%; float: left; text-align: left;'>".$allDate[$i]."</span><span style='width:33%; float: left; text-align: center;'>".$allActions[$i]."</span><span style='width:33%; float: left; text-align: right;'>".$allLastname[$i]." ".$allFirstname[$i]."</span></li>";
      }
    ?>
    </ul>
    <a href="<?php echo $path.'admin/logs.php?userid='.$userid; ?>"><button class="showmore">+ Show all</button></a>
  </div>
</div>
</body>
<script type="text/javascript">
    $(document).ready(function() {
      var userid = getUrlParameter('userid');
      //getBalance(userid);
      //window.setInterval(function(){
      //  getBalance(userid);
      //}, 15000);
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