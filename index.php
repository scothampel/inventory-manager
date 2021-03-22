<?php
    session_start();
    if(isset($_SESSION['user']) && !empty($_SESSION['user']) && isset($_SESSION['auth']) && (!empty($_SESSION['auth']) || intval($_SESSION['auth']) === 0)){
        if(intval($_SESSION['auth']) <= 0){
            http_response_code(403);
            exit();
        }
    }
    else{
        header("Location: /login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Inventory Manager</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css?family=Arvo" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">

    <link rel="stylesheet" href="/css/stylesheet.css">
</head>
<body>
<nav class="navbar sticky-top bg-orange">
    <a class="navbar-brand pt-2 pb-2" href="/index.php">
        <h1 class="font-arvo d-none d-md-inline-block">Inventory Manager</h1>
        <h3 class="font-arvo d-none d-sm-inline-block d-md-none">Inventory Manager</h3>
        <h4 class="font-arvo d-sm-none">Inventory Manager</h4>
    </a>
    <button class="navbar-toggler btn btn-outline-grey" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0 text-center">
            <li class="nav-item" id="nav_AI">
                <a class="nav-link" onclick="switchTo('AI')">
                    <h4 class="font-pt">Add Item</h4>
                    <h6 class="font-pt d-none">Add Item</h6>
                </a>
            </li>
            <li class="nav-item" id="nav_RI">
                <a class="nav-link" onclick="switchTo('RI')">
                    <h4 class="font-pt d-none">Remove Item</h4>
                    <h6 class="font-pt">Remove Item</h6>
                </a>
            </li>
            <li class="nav-item" id="nav_FI">
                <a class="nav-link" onclick="switchTo('FI')">
                    <h4 class="font-pt d-none">Find Item</h4>
                    <h6 class="font-pt">Find Item</h6>
                </a>
            </li>
            <li class="nav-item" id="nav_GU">
                <a class="nav-link" onclick="switchTo('GU')">
                    <h4 class="font-pt d-none">Generate Barcode</h4>
                    <h6 class="font-pt">Generate Barcode</h6>
                </a>
            </li>
            <?php
                if(intval($_SESSION['auth']) >= 2){
                    echo '<li class="nav-item" id="nav_admin">';
                    echo '    <a class="nav-link" onclick="switchTo(\'admin\')">';
                    echo '        <h4 class="font-pt d-none">Admin</h4>';
                    echo '        <h6 class="font-pt">Admin</h6>';
                    echo '</a></li>';
                }
            ?>
            <li class="nav-item" id="nav_logout">
                <a class="nav-link" href="/logout.php">
                    <h4 class="font-pt d-none">Log Out</h4>
                    <h6 class="font-pt">Log Out</h6>
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5 rounded pr-0 pl-0 content switchable" id="AI">
    <header class="bg-grey rounded-top">
        <h3 class="text-center p-2 mb-0 font-arvo">Add Item</h3>
    </header>
    <div class="col-8 offset-2 pt-5 pb-5" id="formContainer_AI">
        <form class="font-pt rounded border p-3" id="form_AI">
            <div class="alert" id="AI_alert" style="display: none;">
                <span class="font-pt"></span>
            </div>
            <div class="form-group">
                <label for="AI_inputName">Item Name</label>
                <input type="text" class="form-control focus" id="AI_inputName" placeholder="Enter item name" autofocus required>
            </div>
            <div class="form-group">
                <label for="AI_inputCount">Amount</label>
                <input type="number" class="form-control" id="AI_inputCount" placeholder="Enter item amount" required>
            </div>
            <div class="form-group">
                <label for="AI_inputGroups">Groups</label>
                <input type="text" class="form-control" id="AI_inputGroups" placeholder="Enter item groups" required>
                <small id="AI_groupsHelp" class="form-text text-muted">Separate groups with a comma and no spaces.</small>
            </div>
            <div class="form-group">
                <label for="AI_inputUPC">Barcode</label>
                <input type="number" class="form-control" id="AI_inputUPC" placeholder="Enter UPC">
                <small id="AI_UPCHelp" class="form-text text-muted">If no barcode is provided, one will be created for you.</small>
            </div>
            <button type="submit" class="btn btn-dark btn-outline-grey bg-orange border-0">Submit</button>
        </form>
    </div>
</div>

<div class="container mt-5 rounded pr-0 pl-0 content switchable d-none" id="RI">
    <header class="bg-grey rounded-top">
        <h3 class="text-center p-2 mb-0 font-arvo">Remove Item</h3>
    </header>
    <div class="col-8 offset-2 pt-5 pb-5" id="formContainer_RI">
        <form class="font-pt rounded border p-3" id="form_RI">
            <div class="alert" id="RI_alert" style="display: none;">
                <span class="font-pt"></span>
            </div>
            <div class="form-group">
                <label for="RI_inputName">Item Name</label>
                <input type="text" class="form-control focus" id="RI_inputName" placeholder="Enter item name">
            </div>
            <div class="form-group">
                <label for="RI_inputUPC">Barcode</label>
                <input type="number" class="form-control" id="RI_inputUPC" placeholder="Enter item barcode">
                <small id="RI_UPCHelp" class="form-text text-muted">The barcode or name can be used, but the barcode is prioritized.</small>
            </div>
            <div class="form-group">
                <label for="RI_inputCount">Amount</label>
                <input type="number" class="form-control" id="RI_inputCount" placeholder="Enter item amount" required>
            </div>
            <button type="submit" class="btn btn-dark btn-outline-grey bg-orange border-0">Submit</button>
        </form>
    </div>
</div>

<div class="container mt-5 rounded pr-0 pl-0 content switchable d-none" id="FI">
    <header class="bg-grey rounded-top">
        <h3 class="text-center p-2 mb-0 font-arvo">Find Item</h3>
    </header>
    <div class="col-8 offset-2 pt-5 pb-5" id="formContainer_FI">
        <form class="font-pt rounded border p-3" id="form_FI">
            <div class="alert" id="FI_alert" style="display: none;">
                <span class="font-pt"></span>
            </div>
            <div class="form-group">
                <label for="FI_inputName">Item Name</label>
                <input type="text" class="form-control" id="FI_inputName" placeholder="Enter item name">
                <small id="FI_nameHelp" class="form-text text-muted">Enter 'all' for all items.</small>
            </div>
            <div class="form-group">
                <label for="FI_inputUPC">Barcode</label>
                <input type="number" class="form-control focus" id="FI_inputUPC" placeholder="Enter item amount">
            </div>
            <div class="form-group">
                <label for="FI_inputGroups">Groups</label>
                <input type="text" class="form-control" id="FI_inputGroups" placeholder="Enter item groups">
                <small id="FI_groupsHelp" class="form-text text-muted">Separate groups with a comma and no spaces.</small>
            </div>
            <button type="submit" class="btn btn-dark btn-outline-grey bg-orange border-0">Submit</button>
            <div class="d-none" id="FI_result">
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-hover" id="FI_table">
                        <thead class="font-arvo">
                            <tr>
                                <th>Name</th>
                                <th>Stock</th>
                                <th>Barcode</th>
                                <th>Groups</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="container mt-5 rounded pr-0 pl-0 content switchable d-none" id="GU">
    <header class="bg-grey rounded-top">
        <h3 class="text-center p-2 mb-0 font-arvo">Generate Barcode</h3>
    </header>
    <div class="col-8 offset-2 pt-5 pb-5" id="formContainer_GU">
        <div class="col border rounded p-3">
        <form class="font-pt rounded" id="form_GU">
            <div class="form-group">
                <label for="GU_inputUPC">Barcode</label>
                <input type="number" class="form-control focus" id="GU_inputUPC" placeholder="Enter item amount">
            </div>
            <button type="submit" class="btn btn-dark btn-outline-grey bg-orange border-0">Submit</button>
        </form>
        <div class="UPCContainer d-none">
            <div class="printable">
                <div class="UPC"></div>
            </div>
            <form class="font-pt mt-3" id="form_PB">
                <div class="row">
                    <div class="col-4">
                        <button type="submit" class="btn btn-dark btn-outline-grey bg-orange border-0">Print</button>
                    </div>
                    <div class="col-8">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="UPCCheck">
                            <label class="form-check-label" for="UPCCheck">Include UPC</label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>

<?php
if(intval($_SESSION['auth']) >= 2){
    include_once "php/admin/admin.html";
}
?>

<nav class="navbar fixed-bottom bg-orange">
    <span class="font-pt footer">Copyright &copy; 2019 Scot Hampel. All rights reserved.</span>
</nav>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha384-vk5WoKIaW/vJyUAd9n/wmopsmNhiy+L2Z+SBxGYnUkunIxVxAv/UtMOhba/xskxh" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="js/main.js"></script>
<?php
    if(intval($_SESSION['auth']) >= 2){
        echo '<script src="js/admin.js"></script>';
    }
?>
</body>
</html>