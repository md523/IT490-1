#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doLogin($username,$password)
{
    // lookup username in databas
    // check password
    
    //return false if not valid
    $hostname = '192.168.194.201';
    $dbuser = 'rahi';
    $dbpass = 'database';
    $dbname = 'project';
    $conn = mysqli_connect($hostname, $dbuser, $dbpass, $dbname);
	
    if (!$connection)
	{
		echo "Error connecting to database: ".$conn->connect_errno.PHP_EOL;
		exit(1);
	}
	echo "Connection Established".PHP_EOL;
	return $conn;
	
	//$username = $POST['username'];
	//$password = $POST['password'];
	$username2 = $mysqli->escape_string($username);
	$password2 = $mysqli->escape_string($password);
	
	// lookup username and password in database
	$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
	// check username and password
	$result = mysqli_query($conn, $sql);
	if(mysqli_num_rows($result) === 1){
		$row = mysqli_fetch_assoc($result);
		if($row['username']=== $username && row['password'] == $password){
			echo "Authorized";
			return true;
			//ADD SESSION CODES
		}
		else 
			{return false;}
	}
	
	//return false if not valid
}

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "login":
      return doLogin($request['username'],$request['password']);
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>

