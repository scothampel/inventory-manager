<?php
    $db_user = "inventory";
    $db_pass = "THATGUYOVERTHERE";
    $db_base = "inventory";
    $db_addr = "localhost";

    $table_items = "items;";

    $mysqli = new mysqli($db_addr, $db_user, $db_pass, $db_base);
    if($mysqli->connect_error){
        exit("Database Connection Failed.");
    }
