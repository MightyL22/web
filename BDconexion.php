<?php
    $host = 'b0udro4xsy2rktpxuqfa-mysql.services.clever-cloud.com';
    $dbname = 'b0udro4xsy2rktpxuqfa';
    $username = 'udwoswumddifyodx';
    $password = 'NKnEPVZJh8HOzZpaay6L';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        die();
    }
    ?>