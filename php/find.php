<?php
#parse_str(implode('&', array_slice($argv, 1)), $_GET);
    session_start();
    if(isset($_SESSION['auth']) && !empty($_SESSION['auth'])) {
        if (intval($_SESSION['auth']) >= 1) {
            if (isset($_GET['name']) || isset($_GET['upc']) || isset($_GET['groups'])) {
                if(!empty($_GET['name']) || !empty($_GET['upc']) || !empty($_GET['groups'])){
                    require_once "db.php";

                    if (!empty($_GET['upc'])) {
                        $upc = intval($_GET['upc']);
                        
                        $stmt = $mysqli->prepare("SELECT * FROM items WHERE `upc` = ? ORDER BY `name`");
                        $stmt->bind_param("i", $upc);
                    }
                    elseif (!empty($_GET['name'])) {
                        if ($_GET['name'] == "all") {
                            $stmt = $mysqli->prepare("SELECT * FROM items ORDER BY `name`");
                        } else {
                            $name = "%" . $_GET['name'] . "%";

                            $stmt = $mysqli->prepare("SELECT * FROM items WHERE `name` LIKE ? ORDER BY `name`");
                            $stmt->bind_param("s", $name);
                        }
                    }
                    elseif (!empty($_GET['groups'])) {
                        $grps_arr = explode(",", $_GET['groups']);
                        foreach ($grps_arr as &$grp) {
                            $grp = "%" . $grp . "%";
                        }
                        unset($grp);

                        $query = "SELECT * FROM items WHERE `groups` LIKE ?";
                        $types = "s";
                        for ($i = 1; $i < sizeof($grps_arr); $i++) {
                            $query .= " AND `groups` LIKE ?";
                            $types .= "s";
                        }
                        $query .= " ORDER BY `name`";
                        $stmt = $mysqli->prepare($query);

                        $stmt->bind_param($types, ...$grps_arr);
                    }

                    if($stmt->execute()){
                        $result = $stmt->get_result();
                        $out = $result->fetch_all(MYSQLI_ASSOC);

                        if(sizeof($out) != 0){
                            echo json_encode($out);
                        }
                        else{
                            echo 1;
                        }
                    }
                    else{
                        echo -1;
                    }
                    $stmt->close();
                    $mysqli->close();
                }
                else {
                    echo 0;
                }
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