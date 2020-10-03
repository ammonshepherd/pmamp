testing here

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
       echo "PDO successful";
   }
//   return $link;
 } catch(PDOException $e) {
  echo "PDO failed" . $e;
 }

phpinfo();
?>
