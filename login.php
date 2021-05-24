<?php

require("./components/header.php");

session_start();
 if(isset($_POST['submit'])){

    $userdoc = new DOMDocument();
    $userdoc->preserveWhiteSpace = false;
    $userdoc->formatOutput = true;
    $userpath = "xml/users.xml";
    $userdoc->load($userpath);


    $users = $userdoc->getElementsByTagName("user");
    $id = $userdoc->getElementsByTagName("id");
    $password = $userdoc->getElementsByTagName("password");
    $userName = $userdoc->getElementsByTagName("username");
    $name = $userdoc->getElementsByTagName("name");
    $type= $userdoc->getElementsByTagName("type");

    

    $count = 0;
    $userError = "";

    
    if ($_POST['password'] == "") {
        $userError =  "Please input valid user name or password";
        $count++;
    }
   
    if ($_POST['username'] == "") {
        $userError =  "Please input valid user name or password";
        $count++;
    }else{
        
        for($i=0; $i < $users->length; $i++){ 
           
            if($userName->item($i)->nodeValue == $_POST['username']){
                
              
                if ($count == 0 && password_verify($_POST['password'], $password->item($i)->nodeValue)) {
    
                     session_regenerate_id(true); //generate and replace new session_id
                     $_SESSION['ID'] = $id->item($i)->nodeValue;

                     if($type->item($i)->nodeValue == 'Customer'){
                    
                        header("location: ticketListing.php",true, 307);
                        exit;

                     }else{
                        header("location: ticketAll.php",true, 307);
                        exit;
                     }
                
                } else {
                   
                    $userError = 'Wrong username or password';
                
                }
            }
        }
    }
}
    
 
?>

<div class="container mt-5">

    <div class="shadow rounded p-3 mb-5 mt-5 bg-white">
        <div class="row">
            <div class="col">

            <h2 class="mb-5">Log in</h2>
            
            <form id="loginForm" name="form_login" method="POST" action="">
            <div class="errorMsg"><?= $userError ? $userError : ''; ?></div>
                    <div class="form-group row">
                        <label for="username" class="col-sm-2 col-form-label">User Name</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" name="username" id="username">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                        <input type="password" class="form-control" name="password" id="password">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-sm-10">
                        <button type="submit" name="submit" class="btn btn-primary">Sign in</button>
                        </div>
                    </div>
                    </form>

                    
            </div>  
        </div>
    </div>
</div>

<?php
    // Closing tag
    require("./components/footer.php");
?>