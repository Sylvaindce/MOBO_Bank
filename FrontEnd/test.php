<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
  <title>Database php page get JSON answer from API</title>
</head>
<body>
  <?php 
  	$username = $_GET['user'];
    $login = $_GET['login'];
    $password = $_GET['password'];
  	echo "hi ";
  	echo $login . " " . $password;
  	$url = "http://localhost:1234/json";
	$json = file_get_contents($url);
	$json_data = json_decode($json, true);
	echo "Hello ". $json_data["UserName"].".";
	echo "Your balance is ".$json_data["Amount"]; ?>
</body>
</html>