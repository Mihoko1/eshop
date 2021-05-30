<?php 
    require("./components/navigation.php"); 

    session_start();

    if($_SESSION['ID']){
    $logOutBtn.= ' <div class="ml-auto"><form method="POST"><input class="btn btn-primary" type="submit" value="Log Out" name="logOutSubmit"></form></div>';

    }

    if($_POST['logOutSubmit']){
        session_destroy();
        header('Location: index.php');
        exit;

    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <link href="/css/styles.css" rel="stylesheet">
        
        <title>Assignment 2</title>
    </head>
    <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#"><img class="logo" src="../images/eshop2.png" alt="eshop logo"></a>
        <?php print $logOutBtn ?>

    </nav>

<script>
    $('.button').click(function() {
    $.ajax({
        type: "POST",
        url: "some.php",
        data: { name: "John" }
    }).done(function( msg ) {
        alert( "Data Saved: " + msg );
    });
    });

</script>
       