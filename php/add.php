<?php
#parse_str(implode('&', array_slice($argv, 1)), $_GET);
    session_start();
    if(isset($_SESSION['auth']) && !empty($_SESSION['auth'])) {
        if (intval($_SESSION['auth']) >= 1) {
            if (isset($_GET['name']) && !empty($_GET['name']) && isset($_GET['count']) && !empty($_GET['count']) && isset($_GET['groups']) && !empty($_GET['groups'])) {
                require_once "db.php";
                $stmt = $mysqli->prepare("SELECT name, count FROM items WHERE name = ? LIMIT 1");
                $stmt->bind_param("s", $_GET['name']);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($name, $count);
                $stmt->fetch();
                $rows = $stmt->num_rows;
                $stmt->close();
                if ($rows == 0) {
                    if (isset($_GET['upc']) && !empty($_GET['upc'])) {
                        $stmt1 = $mysqli->prepare("INSERT INTO items (`name`, `count`, `upc`, `groups`) VALUES (?, ?, ?, ?)");
                        $stmt1->bind_param("siis", $_GET['name'], intval($_GET['count']), intval($_GET['upc']), $_GET['groups']);
                    } else {
                        $stmt1 = $mysqli->prepare("INSERT INTO items (`name`, `count`, `groups`) VALUES (?, ?, ?)");
                        $stmt1->bind_param("sis", $_GET['name'], intval($_GET['count']), $_GET['groups']);
                    }
                    if ($stmt1->execute()) {
                        echo 1;
                    } else {
                        echo -1;
                    }
                    $stmt1->close();
                } else {
                    $count += intval($_GET['count']);

                    $stmt1 = $mysqli->prepare("UPDATE items SET count = ? WHERE name = ?");
                    $stmt1->bind_param("is", $count, $name);
                    if ($stmt1->execute()) {
                        echo 2;
                    } else {
                        echo -1;
                    }
                    $stmt1->close();
                }
                $mysqli->close();
            } else {
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
