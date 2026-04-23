<?php
    // Load central configuration if available
    if (file_exists(__DIR__ . "/config.php")) {
        require_once __DIR__ . "/config.php";
        $servername = isset($DB_HOST) ? $DB_HOST : "localhost";
        $username   = isset($DB_USERNAME) ? $DB_USERNAME : "root";
        $password   = isset($DB_PASSWORD) ? $DB_PASSWORD : "";
        $dbname     = isset($DB_NAME) ? $DB_NAME : "agriculture_portal";
    } else {
        // Fallback defaults
        $servername="localhost";
        $username="root";
        $password="";
        $dbname="agriculture_portal";
    }

    // connect to database
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // $conn = mysqli_connect('us-cdbr-east-03.cleardb.com','b310794f5353e9','d9f40fcf','heroku_f1cacb29cd6455f');
    if(!$conn){
        echo 'Connection error' . mysqli_connect_error();
    } 
?>
