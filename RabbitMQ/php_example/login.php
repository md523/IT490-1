<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
if (isset($argv[1]))
{
  $msg = $argv[1];
}
else
{
  $msg = "test message";
}

$request = array();
$request['type'] = "login";
$request['username'] = $POST['username'];
$request['password'] = $POST['password'];
$request['message'] = $msg;
$response = $client->send_request($request);
$response = $client->publish($request);

echo "client received response: ".PHP_EOL;
print_r($response);
echo "\n\n";

echo $argv[0]." END".PHP_EOL;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title> 

    <!--Links-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"/>
    <link href="../css/main.css" type="text/css" rel="stylesheet"/>
    <link rel="shortcut icon" href="../img/seedling-solid.svg"/>
    <!--Send user input to login.php to validate the user login and sessionID-->
    <script><!--
        function HandleLoginResponse(response)
        {
            var res = response;
            var text = JSON.parse(res);
            console.log(text.username);
            
            if (text.output == "1")
            {
                sessionStorage.setItem('username',text.username);
                sessionStorage.setItem('sessID',text.sessID);
                alert(text.message);
                location.href = 'home.html';
            }
            else
            {
                alert(text.message);
                location.href = 'login.html';
            }
        }
        
        function SendLoginRequest(username,password)
        {
            var request = new XMLHttpRequest();
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;

            request.open("POST","../testRabbitMQClient.php",true);
            request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            request.onreadystatechange = function ()
            {
                
                if ((this.readyState == 4)&&(this.status == 200))
                {
                    HandleLoginResponse(this.responseText);
                }		
            }
            request.send("type=login&username="+username+"&password="+password);
        }
    </script>-->
</head> 
 
<body>
    <!-- Navigation-->
    <nav class="navbar navbar-expand-md navbar-light sticky-top shadow p-3 mb-5 bg-white rounde">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="signup.html">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!--end of nav-->

    <div class="container p-1"></div>
    <div class="container col-md-6">
    <h1>Sign In</h1>
    <hr>
    <form>
        <div class="mb-3">
            <input type="username" class="form-control" id="username" name="username" placeholder="Enter Username">
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
        </div>
        <a class="nav-link" href="">Forgot Password?</a>
        <button type="button" class="btn btn-primary" onclick="testRabbitMQClient.php">Sign In</button>
    </form>
    </div>
    <div class="container p-1"></div>

</body>

<!--Footer-->
<footer class="text-center text-white sticky-bottom container-fluid footer" style="background-color: #7d9988;">
    <!-- Grid container -->
    <div class="container p-3">
    <!--common links and other infromation accessible from all pages-->
    <p> © Copyright 2022: IT490 Project</p>
  </div>
</footer>

</html>
