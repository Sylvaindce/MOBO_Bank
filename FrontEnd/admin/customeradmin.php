<!--
AUTHOR: DECOMBE Sylvain
-->
<?php
  $adminid = $_GET['userid'];
  $cid = $_GET['cid'];

  $jsonadmin = file_get_contents("http://localhost:1234/user?userid=".$adminid);
  $jsonDataAdmin = json_decode($jsonadmin, true);

  $url = "http://localhost:1234/user?userid=".$cid;
  $json = file_get_contents($url);
  $json_data = json_decode($json, true);

  $path = "/~Sylvain/database/DatabaseFront/";

  $jsonBalance = file_get_contents("http://localhost:1234/balance?userid=".$cid);
  $jsonDataBalance = json_decode($jsonBalance, true);

  $jsonLogid = file_get_contents("http://localhost:1234/logbyid?userid=".$cid."&adminid=".$adminid);
  $jsonDataLogid = json_decode($jsonLogid, true);

  $datelogList = str_replace('"', '', $jsonDataLogid['date']);
  $datelogList = str_replace('[', '', $datelogList);
  $datelogList = str_replace(']', '', $datelogList);
  $datelogList = str_replace('.0', '', $datelogList);
  $datelogList = split(',', $datelogList);

  $actionLogList = str_replace('"', '', $jsonDataLogid['action']);
  $actionLogList = str_replace('[', '', $actionLogList);
  $actionLogList = str_replace(']', '', $actionLogList);
  $actionLogList = str_replace('.0', '', $actionLogList);
  $actionLogList = split(',', $actionLogList);

  $url2 = "http://localhost:1234/transaction?userid=".$cid;
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
 <title>MoboBank - Admin Customer</title>
 <link rel="stylesheet" href="../css/style.css">
 <script type="text/javascript" src="../js/jquery-1.8.3.min.js"></script>
</head>
<body>
 <div style="display: flex; text-align: left;">
  <div style="display: block;float: left;width: 50%;margin-left: 5%;">
    <img src="../img/logo.png" style="height: auto !important; width: 40% !important; padding: 15px 15px 15px 0px;">
  </div>
  <div style="display: block;float: left;width: 50%;margin-right: 5% !important;text-align: right; margin: auto;">
    <h4 style="color: black;"><span style="color: #53a9ee;" class="icon fa-user-circle-o"></span> Hi <?php echo $jsonDataAdmin['firstname']." ".$jsonDataAdmin['lastname'] ?></h4>
    <a href="<?php echo $path; ?>">Log Out</a>
  </div>
</div>
<div style="background-color: #53a9ee; padding: 5%; border-radius: 15px; color: #384047; margin-left: 5%; margin-right: 5%;margin-bottom: 5%; display: inline-block; width: 90%;">
  <a href=<?php echo $path."admin/customers.php?userid=".$adminid; ?>><p style="color: white;font-size: 25px;"><span class="icon fa-arrow-left" style="color: white;font-size: 40px;"></span> Back</p></a>
  <div style="display: flex; text-align: center;">
    <div style="display: block; float: left; width: 33%; border: 4px solid white; border-radius: 10px; padding: 30px; margin: 10px;">
      <h4><span style="color: white; font-size: 40px !important;" class="icon fa-user"></span> <?php echo $json_data['firstname']." ".$json_data['lastname'] ?></h4>
    </div>
    <div style="display: block; float: left; width: 33%; padding: 30px; margin: 10px; text-align: center">
      <h4><span style="color: white;" class="icon fa-diamond"></span> Balance :</h4>
      <p style="color: white;"><?php echo $jsonDataBalance['balance'] ?></p>
    </div>
    <div style="display: block; float: left; width: 33%; padding: 30px; margin: 10px; text-align: left;">
      <h4><span style="color: white;" class="icon fa-university"></span> Account number :</h4>
      <p style="color: white;"><?php echo $json_data['account'] ?></p>
    </div>
  </div>
  <div style="margin-top: 4%;">
    <h4><span style="color: white;" class="icon fa-info-circle"></span> Informations:</h4>
  </div>
  <div style="width: 98% !important;" class="container">
      <div style="width: 48%; float: left;padding: 20px">
        <label for="firstname">Firstname:</label>
        <input type="text" id="firstname" name="firstname" value="<?php echo $json_data['firstname']?>" disabled>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?php echo $json_data['phone']?>" disabled>
        <label style="margin-top: 30px;" for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo $json_data['address']?>" disabled>
      </div>
      <div style="width: 48%; float: left; padding: 20px">
        <label for="lastname">Lastname:</label>
        <input type="text" id="lastname" name="lastname" value="<?php echo $json_data['lastname']?>" disabled>
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" value="<?php echo $json_data['email']?>" disabled>
        <label for="password">Password:</label>
        <input type="text" id="password" name="password" value="<?php echo $json_data['pass']?>" disabled>
      </div>
    <button class="showmore">Modify</button>
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
  <div style="margin-top: 4%;">
    <h4><span style="color: white;" class="icon fa-file-text-o"></span> Logs:</h4>
  </div>
  <div style="width: 98% !important;" class="container">
      <ul>
      <li><span style='width:50%; float: left; text-align: left;'>Date</span><span style='width:50%; float: left; text-align: center;'>Action</span></li>
    <?php
      for($i = 0; $i < count($actionLogList); ++$i) {
        echo "<li id=trans".$i."><span style='width:50%; float: left; text-align: left;'>".$datelogList[$i]."</span><span style='width:50%; float: left; text-align: center;'>".$actionLogList[$i]."</span></li>";
      }
    ?>
    </ul>
  </div>
</div>
</body>
</html>