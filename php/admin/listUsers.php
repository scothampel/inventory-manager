<?php
session_start();
if(isset($_SESSION['auth']) && !empty($_SESSION['auth'])) {
    if (intval($_SESSION['auth']) >= 2) {
        require_once $_SERVER['DOCUMENT_ROOT']."/php/db.php";

        $stmt = $mysqli->prepare("SELECT * FROM `users`");

        if($stmt->execute()){
            $result = $stmt->get_result();
            $out = $result->fetch_all(MYSQLI_ASSOC);

            if(sizeof($out) != 0){
                echo json_encode($out);
            }
            else{
                echo 0;
            }
        }
        $stmt->close();
        $mysqli->close();
    }
    else{
        http_response_code(403);
    }
}
else{
    http_response_code(403);
}