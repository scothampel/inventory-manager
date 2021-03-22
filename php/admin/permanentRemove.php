<?php
session_start();
if(isset($_SESSION['auth']) && !empty($_SESSION['auth'])) {
    if (intval($_SESSION['auth']) >= 2) {
        if(isset($_POST['name']) && !empty($_POST['name'])){
            require_once $_SERVER['DOCUMENT_ROOT']."/php/db.php";

            $stmt = $mysqli->prepare("DELETE FROM items WHERE `name` = ?");
            $stmt->bind_param("s", $_POST['name']);

            if($stmt->execute()){
                echo 1;
            }
            else{
                echo -1;
            }
            $stmt->close();
            $mysqli->close();
        }
        else{
            echo 0;
        }
    }
    else{
        http_response_code(403);
    }
}
else{
    http_response_code(403);
}