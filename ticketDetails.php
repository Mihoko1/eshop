<?php

  session_start();

  include_once('./components/header.php');

  if(isset($_GET['ticketId'])) { 
    $ticketId = $_GET['ticketId']; 
  }


  $rows = '';
  $closeMessage = '';
  $statusVal = "";

  $btnRow = '';
  $closeButtonRow = '';

  $doc = new DOMDocument();

  $doc->preserveWhiteSpace = false;
  $doc->formatOutput = true;
  $path = "xml/tickets.xml";
  $doc->load($path);

  $tickets = $doc->getElementsByTagName("ticket");

  $id = $doc->getElementsByTagName('id');
  $dateOpen = $doc->getElementsByTagName('dateopen');
  $category = $doc->getElementsByTagName('category');

  $messages = $doc->getElementsByTagName('messages');
  $message = $doc->getElementsByTagName('message');
  $userId = $doc->getElementsByTagName('userid');
  $staffid = $doc->getElementsByTagName('staffid');
  $status = $doc->getElementsByTagName('status');


  if(isset($_POST['submit'])){
    if($_POST['post'] !== ""){
    
    
      for($i=0; $i < $tickets->length; $i++){ 

        if($id->item($i)->nodeValue == $ticketId){
      
          $newelm = $doc->createElement("message", $_POST['post']);
          $newelm->setAttribute("userid", $_SESSION['ID']);
          $newelm->setAttribute("posted", date('Y-m-d\TH:i:s'));
          $newnode = $messages->item($i)->appendChild($newelm);
        
          // Replace xml
          $doc->save($path);
          
        }
      }

    }

  }

  if(isset($_POST['closeSubmit'])){
    
    for($i=0; $i < $tickets->length; $i++){ 

      if($id->item($i)->nodeValue == $ticketId){
    
        $status->item($i)->nodeValue = "Close";

        $doc->save($path);
      
        $closeMessage .=  '<div class="font-weight-bold mb-3 ml-3 text-danger"><h5>Ticket Closed</h5></div>';

      }
    }

  }
 

  for($j=0; $j < $tickets->length; $j++){ 

    if($id->item($j)->nodeValue == $ticketId){

      $statusVal = $status->item($j)->nodeValue;

      // If a custormer is logged in
      if($userId->item($j)->nodeValue == $_SESSION['ID']){
       
        $btnRow .= '<a href="ticketListing.php">Back to top</a>';
        
      // If an admin or a customer support is logged in      
      }else{
        
        $closeButtonRow .='<form method="POST">
        <input class="btn btn-danger" type="submit" value="Close Ticket" name="closeSubmit" id="closeSubmit" ';
        
        if($statusVal == "Close"){
          $closeButtonRow .= 'disabled ';
        }

        $closeButtonRow .= '></form>';


        $btnRow .= '<a href="ticketAll.php">Back to top</a>';

        // Initial value for staffid is 99999999" and when conversation starts with customer, replace staffid.
        if($staffid->item($j)->nodeValue == "99999999"){

          $staffid->item($j)->nodeValue = $_SESSION['ID'];

          $doc->save($path);
        }
      }

      $rows .= '<div class="border p-3">';
      $rows .= '<div class="font-weight-bold mb-3"><h5>Ticket #'.$ticketId. '</h5></div>';

      if($statusVal == "Close" ){
        $rows .= '<div><span class="font-weight-bold mr-2">Status:</span><span class="text-danger font-weight-bold">'.$statusVal .'</span></div>';
      }else{
        $rows .= '<div><span class="font-weight-bold mr-2">Status:</span><span class="text-info font-weight-bold">'.$statusVal .'</span></div>';
      }

      $rows .= '<div><span class="font-weight-bold mr-2">Date Opened:</span>'.$dateOpen->item($j)->nodeValue .'</div>';
      $rows .= '<div><span class="font-weight-bold mr-2">Category:</span>'.$category->item($j)->nodeValue .'</div></div>';
      $rows .= '<div class="my-4">';

      $messagesElm = $messages->item($j);
      
      for($k=0; $k < $messagesElm->childNodes->length; $k++){ 
        
        
        $rows .= '<div class="my-2"><span class="font-weight-bold mr-2">User ID: '.$messagesElm->childNodes->item($k)->attributes->getNamedItem("userid")->nodeValue.'</span>
        <span class="font-weight-bold mr-2">Posted: '.$messagesElm->childNodes->item($k)->attributes->getNamedItem("posted")->nodeValue.'</span>
        </div>';
        $rows .= '<div class="rounded bgCol py-2 px-2">'.$messagesElm->childNodes->item($k)->nodeValue.'</div>';
        
      }
      $rows .= '</div>'; 

    }

  }
    
?>


<div class="container mt-5">

  <div class="shadow rounded p-3 mb-5 mt-5 bg-white">
    
    <div class="d-flex">
      <div>
        <h2>Ticket Details</h2>
      </div>
    
      <div class="ml-auto p-2">
       <?php print $closeButtonRow ?>
      </div>
    </div>
        
    <div class="my-3">
      <?php print $btnRow ?>
    </div>

    <div class="ticketDetails">
        <?php print $rows; ?>
    </div>

    <form method="POST" class="mt-5">
      <div class="form-group row">
      <label for="post" class="col-lg-2 col-form-label">Post a message</label>
      <div class="col-lg-6 mb-3">
        <input type="text" class="form-control" name="post" id="post">
      </div>
      <div class="col-lg-4">
        <input class="btn btn-primary form-control" type="submit" value="Send" name="submit" id="submit" <?php if($statusVal == "Close"): ?> disabled <?php endif; ?>>
      </div>
    </form>
    <?php print $closeMessage; ?>
  </div>
</div>

<?php

  // Closing tag
  include_once('./components/footer.php');

?>


