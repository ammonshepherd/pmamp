<html>
<head>
<style>
  .green {
    color: green;
    font-size: 1.3em;
  }

  .myButton {
    box-shadow: 0px 10px 14px -7px #276873;
    background:linear-gradient(to bottom, #599bb3 5%, #408c99 100%);
    background-color:#599bb3;
    border-radius:8px;
    display:inline-block;
    cursor:pointer;
    color:#ffffff;
    font-family:Arial;
    font-size:20px;
    font-weight:bold;
    padding:13px 32px;
    text-decoration:none;
    text-shadow:0px 1px 0px #3d768a;
  }
  .myButton:hover {
    background:linear-gradient(to bottom, #408c99 5%, #599bb3 100%);
    background-color:#408c99;
  }
  .myButton:active {
    position:relative;
    top:1px;
  }
</style>
</head>
<body>
<h1>PMAMP: <span class="green">P</span>hp<span class="green">M</span>y<span class="green">A</span>dmin - <span class="green">M</span>ySQL - <span class="green">P</span>HP</h1>

<p> <a href="http://localhost:8080" class="myButton">phpMyAdmin</a></p>


<?php
$server = 'mysql';
$dbname= 'dbase';
$username = 'dbuser';
$password = 'dbpass';

$dsn = "mysql:host=$server;dbname=$dbname";
$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

// Create connection object and assign it to a variable
try {   
  $link = new PDO($dsn, $username, $password, $options);
  if(is_object($link)){
      echo "PDO successfully connected to MySQL";
  }
} catch(PDOException $e) {
  echo "PDO failed: ";
  echo "<pre>$e</pre>";
}

phpinfo();
?>
</body>
</html>
