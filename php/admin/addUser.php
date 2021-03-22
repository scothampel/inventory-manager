<?php
    session_start();
    if(isset($_SESSION['auth']) && !empty($_SESSION['auth'])) {
        if (intval($_SESSION['auth']) >= 2) {
            if(isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['pass']) && !empty($_POST['pass']) && isset($_POST['admin']) && (!empty($_POST['admin']) || intval($_POST['admin']) === 0)){
                $pass_hash = password_hash($_POST['pass'], PASSWORD_DEFAULT);
                switch(intval($_POST['admin'])){
                    case 1:
                        $auth = 2;
                        break;
                    default:
                        $auth = 1;
                }

                require_once $_SERVER['DOCUMENT_ROOT']."/php/db.php";

                $stmt = $mysqli->prepare("INSERT INTO users (`name`, `hash`,`auth`) VALUES (?, ?, ?)");
                $stmt->bind_param("ssi", $_POST['name'], $pass_hash, $auth);

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
