<?php
    session_start();
    if(isset($_SESSION['auth']) && !empty($_SESSION['auth'])) {
        if (intval($_SESSION['auth']) >= 1) {
            if (isset($_GET['data']) && !empty($_GET['data'])) {
                require_once "BarcodeGenerator/BarcodeGenerator.php";
                require_once "BarcodeGenerator/BarcodeGeneratorPNG.php";

                $data = preg_replace('/[^\d]/i', '', $_GET['data']);
                header('Content-Type: image/png');

                $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                echo $generator->getBarcode($data, $generator::TYPE_CODE_128);
            }
        }
        else{
            http_response_code(403);
        }
    }
    else{
        http_response_code(403);
    }