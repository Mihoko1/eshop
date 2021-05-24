<?php

session_start();

require("./components/header.php");


$rows = '';
$userrows = '';
$userdoc = new DOMDocument();
$userdoc->preserveWhiteSpace = false;
$userdoc->formatOutput = true;
$userpath = "xml/users.xml";
$userdoc->load($userpath);

$doc = new DOMDocument();
$doc->preserveWhiteSpace = false;
$doc->formatOutput = true;
$path = "xml/tickets.xml";
$doc->load($path);



$users = $userdoc->getElementsByTagName("user");
$idsUsers = $userdoc->getElementsByTagName("id");
$userName = $userdoc->getElementsByTagName("username");
$name = $userdoc->getElementsByTagName("name");

for($i=0; $i < $idsUsers->length; $i++){ 

    if($idsUsers->item($i)->nodeValue == $_SESSION['ID']){
        
        $usernameRow .= '<div class="orangeMsg font-weight-bold">Welcome back '.$userName->item($i)->nodeValue. '!</div>';
    }
   
  } 

$tickets = $doc->getElementsByTagName("tickets");
$ticket = $doc->getElementsByTagName("ticket");
$ids = $doc->getElementsByTagName('id');

$dateOpen = $doc->getElementsByTagName('dateopen');
$status = $doc->getElementsByTagName('status');
$category = $doc->getElementsByTagName('category');
$messages = $doc->getElementsByTagName('messages');
$userid = $doc->getElementsByTagName('userid');
$staffid = $doc->getElementsByTagName('staffid');


  for($j=0; $j < $ticket->length; $j++){ 

        $rows .= '<tr>';
        $rows .= '<td>'.$ids->item($j)->nodeValue. '</td>';
        $rows .= '<td>'.$dateOpen->item($j)->nodeValue .'</td>';
        $rows .= '<td>'.$category->item($j)->nodeValue .'</td>';

    if($status->item($j)->nodeValue == "Close"){ 

        $rows .= '<td class="text-danger font-weight-bold">'.$status->item($j)->nodeValue .'</td>';

    }else{

        $rows .= '<td class="text-info font-weight-bold">'.$status->item($j)->nodeValue .'</td>';

    }
        $rows .= '<td>'.$userid->item($j)->nodeValue .'</td>';
        $rows .= '<td><a class="btn btn-primary" href="ticketDetails.php?ticketId='. $ids->item($j)->nodeValue .'">View Ticket</a></td>';
        $rows .= '</tr>';
  }
    
?>

<div class="container mt-5">
    <div class="shadow rounded p-3 mb-5 mt-5 bg-white">
        <p><?php print $usernameRow ?></p>
        <h2>Support Ticket Listing</h2>
       
        <table class="table my-3">
            <thead>
                <tr>
                    <th scope="col">Ticket ID</th>
                    <th scope="col">Date Opened</th>
                    <th scope="col">Category</th>
                    <th scope="col">Status</th>
                    <th scope="col">Client ID</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php print $rows; ?>
            </tbody>
        </table>
    </div>
</div>
  
<?php
    // Closing tag
    require("./components/footer.php");
?>





