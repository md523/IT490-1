#!/usr/bin/php
<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doRegister($fname, $username, $email, $password)
{
    $hostname = '192.168.194.201';
    $dbuser = 'root';
    $dbpass = 'Database@123';
    $dbname = 'Project';
    $dbport = "3306";
    $conn = mysqli_connect($hostname, $dbuser, $dbpass, $dbname, $dbport);
	
    if (!$conn)
	{
		echo "Error connecting to database: ".$conn->connect_errno.PHP_EOL;
		exit(1);
	}
	echo "Connection Established".PHP_EOL;
	//return $conn;
	
    $query = "INSERT INTO `Project`.`user` (`name`, `username`, `email`, `password`) VALUES ('$fname', '$username', '$email', '$password')";
    
 //   $query = "INSERT INTO `Project`.`user` (`name`, `username`, `email`, `password`) VALUES ('bob', 'bob', 'bob@gmail.com', 'bobby')";
    
    if (mysqli_query($conn, $query)) {
  	echo "New record created successfully";
  	return 1;
}   else {
  	echo "Error: " . $query . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
    
    //$sql = "INSERT INTO `PROJECT`.`Users` (`userid`, `name`, `username`, `email`, `password`, `Security Answer 1`, `Security Answer 2`) VALUES (123, '$username', '$fname + " "$lname', '$email', '$password', '$seca1', '$seca2')";

}

function doLogin($username,$password)
{
    $hostname = '192.168.194.201';
    $dbuser = 'root';
    $dbpass = 'Database@123';
    $dbname = 'Project';
    $dbport = "3306";
    $conn = mysqli_connect($hostname, $dbuser, $dbpass, $dbname, $dbport);
	
    if (!$conn)
	{
		echo "Error connecting to database: ".$conn->connect_errno.PHP_EOL;
		exit(1);
	}
	echo "Connection Established".PHP_EOL;
	//return $conn;
	
	//$username = $POST['username'];
	//$password = $POST['password'];
	//$username2 = $mysqli->escape_string($username);
	//$password2 = $mysqli->escape_string($password);
	
	// lookup username and password in database
	$sql = "SELECT * FROM user WHERE username='$username' AND password='$password'";
	// check username and password
	
	$result = mysqli_query($conn, $sql);
	if($result == false)
	{
		echo "Not authorized";
		$result=0;
		
	}
	if (mysqli_num_rows($result)==0)
	{
		echo "NO GOOD";
	}
	else
	{
		while($row=mysqli_fetch_array($result, MYSQLI_ASSOC))
		{
			if ($username = $row['username'] && $password = ['password'])
			{
				echo "Authorized";
				return 1;
			}
			else
			{
				echo "No Good";
				return 2;
				
			}
		}
	}
}

function requestProcessor($request)
{
  echo "received request";
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
    case "register":
      return doRegister($request['fname'], $request['username'], $request['email'], $request['password']);
  }
  //return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

echo "testRabbitMQServer BEGIN";
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END";
exit();
?>
