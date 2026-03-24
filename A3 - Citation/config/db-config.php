<?php
declare(strict_types= 1);
//configure data to connect to database
$server="localhost";
$admin='root';
$password='';
$bd="citation_db";
 try{
//connect to database
    $pdo=new PDO("mysql:host=$server;dbname=$bd", $admin,
    $password, array(PDO::ATTR_ERRMODE =>
    PDO::ERRMODE_EXCEPTION));
    //echo $pdo ? "Not null":"Null";
    //echo "connected";
}
// Test Error
catch(PDOException $e){
die("Error : ".$e->getMessage());
}
?>