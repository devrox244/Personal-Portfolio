<?php
$DSN = 'mysql:host = localhost; dbname=p_portfolio';

$conn = new PDO($DSN,'root','Somatic');

if(!$conn){
    die("Connection error: ".mysqli_connect_error());
}
?>