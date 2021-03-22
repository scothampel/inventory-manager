$(document).ready(function(){
    $("#form_AI").submit(function (e) {
        e.preventDefault();
        addItem()
        $("button[type='submit']").blur();
    });

    $("#form_RI").submit(function (e) {
        e.preventDefault();
        removeItem();
        $("button[type='submit']").blur();
    });

    $("#form_FI").submit(function (e) {
        e.preventDefault();
        findItem();
        $("button[type='submit']").blur();
    });

    $("#form_GU").submit(function (e) {
        e.preventDefault();
        generateUPC();
        $("button[type='submit']").blur();
    });

    $("#form_PB").submit(function (e) {
        e.preventDefault();
        printBarcode();
        $("button[type='submit']").blur();
    });
});

function addItem() {
    $.ajax({
        method: "GET",
        url: "/php/add.php",
        data: {
            name: $("#AI_inputName").val(),
            count: $("#AI_inputCount").val(),
            upc: $("#AI_inputUPC").val(),
            groups: $("#AI_inputGroups").val()
        }
    }).done(function(code) {
        switch(parseInt(code)){
            case -1:
                $("#AI_alert span").text("Error: Item not added. Check if barcode already exists");
                $("#AI_alert").removeClass("alert-success").addClass("alert-danger").slideDown();
                break;
            case 0:
                $("#AI_alert span").text("Please fill in the required fields");
                $("#AI_alert").removeClass("alert-success").addClass("alert-danger").slideDown();
                break;
            case 1:
                $("#AI_alert span").text("New item added");
                $("#AI_alert").removeClass("alert-danger").addClass("alert-success").slideDown();
                break;
            case 2:
                $("#AI_alert span").text("Existing item stock increased");
                $("#AI_alert").removeClass("alert-danger").addClass("alert-success").slideDown();
                break;
        }
        setTimeout(function () {$("#AI_alert").slideUp();}, 5000);
    });
}

function removeItem() {
    $.ajax({
        method: "GET",
        url: "/php/remove.php",
        data: {
            name: $("#RI_inputName").val(),
            count: $("#RI_inputCount").val(),
            upc: $("#RI_inputUPC").val(),
        }
    }).done(function(code) {
        console.log(code);
        switch(parseInt(code)){
            case -1:
                $("#RI_alert span").text("Error: Item not removed");
                $("#RI_alert").removeClass("alert-success").addClass("alert-danger").slideDown();
                break;
            case 0:
                $("#RI_alert span").text("Please fill in the required fields");
                $("#RI_alert").removeClass("alert-success").addClass("alert-danger").slideDown();
                break;
            case 1:
                $("#RI_alert span").text("Item not found");
                $("#RI_alert").removeClass("alert-success").addClass("alert-danger").slideDown();
                break;
            case 2:
                $("#RI_alert span").text("Item completely removed. Stock is 0");
                $("#RI_alert").removeClass("alert-danger").addClass("alert-success").slideDown();
                break;
            default:
                $("#RI_alert span").text("Item removed. New stock is: " + code);
                $("#RI_alert").removeClass("alert-danger").addClass("alert-success").slideDown();
                break;
        }
        setTimeout(function () {$("#RI_alert").slideUp();}, 7000);
    });
}

function findItem() {
    $.ajax({
        method: "GET",
        url: "/php/find.php",
        data: {
            name: $("#FI_inputName").val(),
            groups: $("#FI_inputGroups").val(),
            upc: $("#FI_inputUPC").val(),
        }
    }).done(function(code) {
        var result = JSON.parse(code);

        if(typeof result === "number"){
            switch(parseInt(code)){
                case -1:
                    $("#FI_alert span").text("Error: No result returned");
                    $("#FI_alert").removeClass("alert-success").addClass("alert-danger").slideDown();
                    break;
                case 0:
                    $("#FI_alert span").text("Please fill in one of the fields");
                    $("#FI_alert").removeClass("alert-success").addClass("alert-danger").slideDown();
                    break;
                case 1:
                    $("#FI_alert span").text("Item not found");
                    $("#FI_alert").removeClass("alert-success").addClass("alert-danger").slideDown();
                    break;
            }
            setTimeout(function () {$("#FI_alert").slideUp();}, 7000);
        }
        else{
            $("#FI_table tbody").empty();
            result.forEach(function(val){
                let row_formatted = "<tr><td>"+ val["name"] +"</td><td>"+ val["count"] +"</td><td>"+ val["upc"].toString().padStart(15, 0) +"</td><td>"+ val["groups"] +"</td><td><button class='btn btn-dark btn-outline-grey bg-orange border-0' onclick='generateUPC("+ val["upc"] +")'>Generate</button></td></tr>"
                $("#FI_table tbody").append(row_formatted);
            });

            $("#FI_result").removeClass("d-none");
        }

    });
}
function generateUPC(code) {
    switchTo("GU");

    if(typeof code === "undefined"){
        code = $("#GU_inputUPC").val();
    }

    code = code.toString().padStart(15, 0);

    const element = "<div class='UPC mt-3 pt-3 pb-3 bg-white rounded text-center border'><img class='UPC-image' src='php/generate.php?data="+ code +"'><p class='d-none font-pt'>"+ code +"</p></div>";
    $(".UPC").replaceWith(element);
    $(".UPCContainer").removeClass("d-none");
}

let currentPage = "AI";
function switchTo(page) {
    if(page !== currentPage){
        currentPage = page;

        $(".nav-item .nav-link h4").addClass("d-none");
        $(".nav-item .nav-link h6").removeClass("d-none");
        $("#nav_" + page + " h4").removeClass("d-none");
        $("#nav_" + page + " h6").addClass("d-none");

        $(".switchable").addClass("d-none");
        $("#" + page).removeClass("d-none");

        if($("#navbarCollapse").hasClass("show")){
            $(".navbar-toggler").click();
        }

        $("#" + page + " input.focus").focus();
    }
}

function printBarcode(){
    if($("#UPCCheck").prop("checked") == true){
        $(".UPC p").addClass("print");
    }
    else{
        $(".UPC p").removeClass("print");
    }
    window.print();
}