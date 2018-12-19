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
 <title>MoboBank - User</title>
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
    <div style="display: block; float: left; width: 33%; padding: 30px; margin: 10px;">
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
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?php echo $json_data['phone']?>" disabled>
        <label style="margin-top: 30px;" for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo $json_data['address']?>" disabled>
      </div>
      <div style="width: 48%; float: left; padding: 20px">
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" value="<?php echo $json_data['email']?>" disabled>
      </div>
    <button class="showmore">Modify</button>
  </div>
</div>
</body>
</html>