<?php
#parse_str(implode('&', array_slice($argv, 1)), $_GET);
    session_start();
    if(isset($_SESSION['auth']) && !empty($_SESSION['auth'])) {
        if (intval($_SESSION['auth']) >= 1) {
            if (((isset($_GET['name']) && !empty($_GET['name'])) || (isset($_GET['upc']) && !empty($_GET['upc']))) && isset($_GET['count']) && !empty($_GET['count'])) {
                require_once "db.php";
                if(isset($_GET['upc']) && !empty($_GET['upc'])){
                    $stmt = $mysqli->prepare("SELECT `name`, `count` FROM items WHERE `upc` = ? LIMIT 1");
                    $stmt->bind_param("i", $_GET['upc']);
                }
                else{
                    $stmt = $mysqli->prepare("SELECT `name`, `count` FROM items WHERE `name` = ? LIMIT 1");
                    $stmt->bind_param("s", $_GET['name']);
                }
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($name, $count);
                $stmt->fetch();
                $rows = $stmt->num_rows;
                $stmt->close();
                if ($rows != 0) {
                    $new_count = $count - intval($_GET['count']);
                    if ($new_count < 0) {
                        $new_count = 0;
                    }

                    if(isset($_GET['upc']) && !empty($_GET['upc'])){
                        $stmt1 = $mysqli->prepare("UPDATE items SET `count` = ? WHERE `upc` = ?");
                        $stmt1->bind_param("ii", $new_count, intval($_GET['upc']));
                    }
                    else{
                        $stmt1 = $mysqli->prepare("UPDATE items SET `count` = ? WHERE `name` = ?");
                        $stmt1->bind_param("is", $new_count, $_GET['name']);
                    }

                    if($stmt1->execute()){
                        if($new_count){
                            echo $new_count;
                        }
                        else{
                            echo 2;
                        }
                    }
                    else{
                        echo -1;
                    }
                    $stmt1->close();
                }
                else {
                    echo 1;
                }
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