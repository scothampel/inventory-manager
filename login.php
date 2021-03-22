<?php
    session_start(['cookie_lifetime' => 28800]);
    $loginFailed = false;

    if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
        header("Location: /index.php");
    }
    elseif(isset($_POST['inputUser']) && !empty($_POST['inputUser']) && isset($_POST['inputPassword']) && !empty($_POST['inputPassword'])){
        require_once "php/db.php";
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE `name` = ? LIMIT 1");
        $stmt->bind_param("s", $_POST['inputUser']);
        $stmt->execute();
        $result = $stmt->get_result();
        $out = $result->fetch_assoc();

        if(password_verify($_POST['inputPassword'], $out['hash'])){
            $_SESSION['user'] = $out['name'];
            $_SESSION['auth'] = $out['auth'];
            header("Location: /index.php");
        }
        else{
            $loginFailed = true;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Please Login</title>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

        <link href="https://fonts.googleapis.com/css?family=Arvo" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">

        <link rel="stylesheet" href="/css/stylesheet.css">
    </head>
    <body>
        <header class="bg-orange">
            <div class="container pt-2 pb-2">
                <div class="text-center p-2 w-100">
                    <h1 class="font-arvo d-none d-sm-inline-block">Inventory Manager</h1>
                    <h3 class="font-arvo d-sm-none">Inventory Manager</h3>
                </div>
            </div>
        </header>

        <div class="container mt-5 rounded pr-0 pl-0 content" id="main">
            <header class="bg-grey rounded-top">
                <h3 class="text-center p-2 mb-0 font-arvo">Please Login</h3>
            </header>
            <div class="col-8 offset-2 pt-5 pb-5" id="formContainer">
                <form class="font-pt rounded border p-3" href="#" method="post">
                    <div class="alert alert-danger" id="loginFailed" style="display: none;">
                        <span class="font-pt">Incorrect Username or Password!<span>
                    </div>
                    <div class="form-group">
                        <label for="inputUser">Username</label>
                        <input type="text" class="form-control" id="inputUser" name="inputUser" placeholder="Enter username">
                    </div>
                    <div class="form-group">
                        <label for="inputPassword">Password</label>
                        <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Password">
                    </div>
                    <button type="submit" class="btn btn-dark btn-outline-grey bg-orange border-0">Submit</button>
                </form>
            </div>
        </div>

        <nav class="navbar fixed-bottom bg-orange">
            <span class="font-arvo footer">Copyright &copy; 2019 Scot Hampel. All rights reserved.</span>
        </nav>

        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha384-vk5WoKIaW/vJyUAd9n/wmopsmNhiy+L2Z+SBxGYnUkunIxVxAv/UtMOhba/xskxh" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <?php if($loginFailed){echo '<script>$(document).ready(function() {$("#loginFailed").show();});</script>'."\n";} ?>
    </body>
</html>