$(document).ready(function(){
    $("#form_admin_add").submit(function(e){
        e.preventDefault();
        admin_addUser();
        $("button[type='submit']").blur();
    });

    $("#form_admin_users").submit(function(e){
        e.preventDefault();
    });

    $("#admin_users_remove").click(function(e){
        admin_removeUser();
        $("#admin_users_remove").blur();
    });

    $("#admin_users_disable").click(function(e){
        admin_disableUser();
        $("#admin_users_disable").blur();
    });

    $("#admin_users_enable").click(function(e){
        admin_enableUser();
        $("#admin_users_enable").blur();
    });

    $("#admin_users_admin").click(function(e){
        admin_authUser();
        $("#admin_users_admin").blur();
    });

    $("#form_admin_items").submit(function (e){
        e.preventDefault();
        admin_removeItem();
        $("button[type='submit']").blur();
    });

    $("#admin_items_refresh").click(function(){
        admin_listItems();
        $("#admin_items_refresh").blur();
    })

    admin_listItems();
    admin_listUsers()
});

function admin_addUser(){
    let admin = 0;
    if($("#admin_add_check").prop("checked") == true){
        admin = 1;
    }

    $.ajax({
        method: "POST",
        url: "/php/admin/addUser.php",
        data: {
            name: $("#admin_add_inputUser").val(),
            pass: $("#admin_add_inputPass").val(),
            admin: admin
        }
    }).done(function(code) {
        switch(parseInt(code)){
            case -1:
                $("#admin_add_alert span").text("Error: Could not add user. Ensure username does not already exist");
                $("#admin_add_alert").removeClass("alert-success").addClass("alert-danger").slideDown();
                break;
            case 0:
                $("#admin_add_alert span").text("Please fill in required fields");
                $("#admin_add_alert").removeClass("alert-success").addClass("alert-danger").slideDown();
                break;
            case 1:
                $("#admin_add_alert span").text("User added successfully");
                $("#admin_add_alert").removeClass("alert-danger").addClass("alert-success").slideDown();
                admin_listUsers();
                break;
        }
        setTimeout(function () {$("#admin_add_alert").slideUp();}, 7000);
    });
}

function admin_removeUser(){
    let name = $('#form_admin_users input[name="admin_userSelected"]:checked').val();
    if(typeof name === "undefined"){
        name = "";
    }

    $.ajax({
        method: "POST",
        url: "/php/admin/removeUser.php",
        data: {
            name: name
        }
    }).done(function(code) {
        switch(parseInt(code)){
            case -1:
                $("#admin_users_alert span").text("Error: Could not remove user");
                $("#admin_users_alert").removeClass("alert-success").addClass("alert-danger").slideDown();
                break;
            case 0:
                $("#admin_users_alert span").text("Please pick a user");
                $("#admin_users_alert").removeClass("alert-success").addClass("alert-danger").slideDown();
                break;
            case 1:
                $("#admin_users_alert span").text("User "+ name +" removed");
                $("#admin_users_alert").removeClass("alert-danger").addClass("alert-success").slideDown();
                admin_listUsers();
                break;
        }
        setTimeout(function () {$("#admin_users_alert").slideUp();}, 7000);
    });
}

function admin_enableUser() {
    let name = $('#form_admin_users input[name="admin_userSelected"]:checked').val();
    if(typeof name === "undefined"){
        name = "";
    }

    $.ajax({
        method: "POST",
        url: "/php/admin/enableUser.php",
        data: {
            name: name
        }
    }).done(function(code) {
        switch(parseInt(code)){
            case -1:
                $("#admin_users_alert span").text("Error: Could not enable user");
                $("#admin_users_alert").removeClass("alert-success").addClass("alert-danger").slideDown();
                break;
            case 0:
                $("#admin_users_alert span").text("Please pick a user");
                $("#admin_users_alert").removeClass("alert-success").addClass("alert-danger").slideDown();
                break;
            case 1:
                $("#admin_users_alert span").text("User "+ name +" enabled");
                $("#admin_users_alert").removeClass("alert-danger").addClass("alert-success").slideDown();
                admin_listUsers();
                break;
        }
        setTimeout(function () {$("#admin_users_alert").slideUp();}, 7000);
    });
}

function admin_disableUser() {
    let name = $('#form_admin_users input[name="admin_userSelected"]:checked').val();
    if(typeof name === "undefined"){
        name = "";
    }

    $.ajax({
        method: "POST",
        url: "/php/admin/disableUser.php",
        data: {
            name: name
        }
    }).done(function(code) {
        switch(parseInt(code)){
            case -1:
                $("#admin_users_alert span").text("Error: Could not disable user");
                $("#admin_users_alert").removeClass("alert-success").addClass("alert-danger").slideDown();
                break;
            case 0:
                $("#admin_users_alert span").text("Please pick a user");
                $("#admin_users_alert").removeClass("alert-success").addClass("alert-danger").slideDown();
                break;
            case 1:
                $("#admin_users_alert span").text("User "+ name +" disabled");
                $("#admin_users_alert").removeClass("alert-danger").addClass("alert-success").slideDown();
                admin_listUsers();
                break;
        }
        setTimeout(function () {$("#admin_users_alert").slideUp();}, 7000);
    });
}

function admin_authUser() {
    let name = $('#form_admin_users input[name="admin_userSelected"]:checked').val();
    if(typeof name === "undefined"){
        name = "";
    }

    $.ajax({
        method: "POST",
        url: "/php/admin/makeAdmin.php",
        data: {
            name: name
        }
    }).done(function(code) {
        switch(parseInt(code)){
            case -1:
                $("#admin_users_alert span").text("Error: Could not make user admin");
                $("#admin_users_alert").removeClass("alert-success").addClass("alert-danger").slideDown();
                break;
            case 0:
                $("#admin_users_alert span").text("Please pick a user");
                $("#admin_users_alert").removeClass("alert-success").addClass("alert-danger").slideDown();
                break;
            case 1:
                $("#admin_users_alert span").text("User "+ name +" made admin");
                $("#admin_users_alert").removeClass("alert-danger").addClass("alert-success").slideDown();
                admin_listUsers();
                break;
        }
        setTimeout(function () {$("#admin_users_alert").slideUp();}, 7000);
    });
}

function admin_listUsers() {
    $.ajax({
        method: "GET",
        url: "/php/admin/listUsers.php",
    }).done(function(code) {
        var result = JSON.parse(code);

        if(typeof result !== "number"){
            $("#admin_users_table tbody").empty();
            result.forEach(function(val){
                let row_formatted = "<tr><td><input type='radio' name='admin_userSelected' value='"+ val["name"] +"'></td><td>"+ val["name"] +"</td><td>"+ val["auth"] +"</td></tr>"
                $("#admin_users_table tbody").append(row_formatted);
            });
        }
    });
}

function admin_removeItem() {
    let name = $('#form_admin_items input[name="admin_itemSelected"]:checked').val();
    if(typeof name === "undefined"){
        name = "";
    }

    $.ajax({
        method: "POST",
        url: "/php/admin/permanentRemove.php",
        data: {
            name: name
        }
    }).done(function(code) {
        switch(parseInt(code)){
            case -1:
                $("#admin_items_alert span").text("Error: Could not remove");
                $("#admin_items_alert").removeClass("alert-success").addClass("alert-danger").slideDown();
                break;
            case 0:
                $("#admin_items_alert span").text("Please pick an item");
                $("#admin_items_alert").removeClass("alert-success").addClass("alert-danger").slideDown();
                break;
            case 1:
                $("#admin_items_alert span").text("Item "+ name +" permanently removed");
                $("#admin_items_alert").removeClass("alert-danger").addClass("alert-success").slideDown();
                admin_listItems();
                break;
        }
        setTimeout(function () {$("#admin_items_alert").slideUp();}, 7000);
    });
}

function admin_listItems() {
    $.ajax({
        method: "GET",
        url: "/php/find.php",
        data: {
            name: "all"
        }
    }).done(function(code) {
        var result = JSON.parse(code);

        if(typeof result !== "number"){
            $("#admin_items_table tbody").empty();
            result.forEach(function(val){
                if(parseInt(val['count']) === 0){
                    let row_formatted = "<tr><td><input type='radio' name='admin_itemSelected' value='"+ val["name"] +"'></td><td>"+ val["name"] +"</td><td>"+ val["count"] +"</td><td>"+ val["upc"].toString().padStart(15, 0) +"</td><td>"+ val["groups"] +"</td></tr>"
                    $("#admin_items_table tbody").append(row_formatted);
                }
            });
        }
    });
}