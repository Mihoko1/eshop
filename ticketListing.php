<?php
    require("./components/header.php");

    session_start();

    $categories = ['==Please Select==' =>'select', 'General' => 'general', 'Shipping' => 'shipping', 'Return' => 'return', 'Others' => 'others'];


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


    // From users.xml
    $users = $userdoc->getElementsByTagName("user");
    $idsUsers = $userdoc->getElementsByTagName("id");
    $userName = $userdoc->getElementsByTagName("username");
    $name = $userdoc->getElementsByTagName("name");


    for($i=0; $i < $idsUsers->length; $i++){ 

        if($idsUsers->item($i)->nodeValue == $_SESSION['ID']){
            $usernameRow .= '<div class="orangeMsg font-weight-bold">Welcome back '.$userName->item($i)->nodeValue. '!</div>';
        }
   
    } 

    // From tickets.xml
    $tickets = $doc->getElementsByTagName("tickets");
    $ticket = $doc->getElementsByTagName("ticket");
    $ids = $doc->getElementsByTagName('id');

    $dateOpen = $doc->getElementsByTagName('dateopen');
    $status = $doc->getElementsByTagName('status');
    $category = $doc->getElementsByTagName('category');
    $messages = $doc->getElementsByTagName('messages');
    $userid = $doc->getElementsByTagName('userid');


    if(isset($_POST['submiTicket'])){
        $count = 0;

        if($_POST['message'] == ""){

            $messageError = "Please input message";
            $count++;

        }

        if($_POST['categories'] == "select") {
            
            $categoryError = "Please choose category";
            $count++;

        } 
        
        if($count == 0){
        
        
            $idLength = $ids->length;
            $lastId = $ids->item($idLength - 1)->nodeValue;
            $newId = intval($lastId) + 1;

            $newelmId = $doc->createElement("id", $newId);
            $newelmDateOpen = $doc->createElement("dateopen", date('Y-m-d\TH:i:s'));
            $newelmCategory = $doc->createElement("category", $_POST['categories']);
            
            $newMessages = $doc->createElement("messages");
            $newelmMsg = $doc->createElement("message", $_POST['message']);
            $newelmMsg->setAttribute("userid", $_SESSION['ID']);
            $newelmMsg->setAttribute("posted", date('Y-m-d\TH:i:s'));
            $newelmMessages = $newMessages->appendChild($newelmMsg);

            $newelmStatus = $doc->createElement("status", "Open");
            $newelmUserId = $doc->createElement("userid", $_SESSION['ID']);

            // Set "99999999" as default value for staff id if no admin or customer staff started chat with customer.
            // Once a staff start to chat with customer, <staffid> will be replaced.
            $newelmStaffId= $doc->createElement("staffid", "99999999"); 

            $newticket = $doc->createElement("ticket");
            
            $newticket->appendChild($newelmId);
            $newticket->appendChild($newelmDateOpen);
            $newticket->appendChild($newelmCategory);
            $newticket->appendChild($newMessages);
            $newticket->appendChild($newelmStatus);
            $newticket->appendChild($newelmUserId);
            $newticket->appendChild($newelmStaffId);

            $tickets->item(0)->appendChild($newticket);
        
            $doc->save($path);
        
        }
    
    }


    for($j=0; $j < $ticket->length; $j++){ 

        if($userid->item($j)->nodeValue == $_SESSION['ID']){
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

    }
    
?>

<div class="container mt-5">
    <div class="shadow rounded p-3 mb-5 mt-5 bg-white">
        <p><?php print $usernameRow ?></p>
        <h2>Submit a Support Ticket</h2>
        <form method="POST" class="mt-3 mb-5">
                      
            <div class="mt-3">
                <div class="form-group">
                    <label class="col-lg-4 col-md-6 col-form-label" for="categories">Please select the category *</label><span class="errorMsg"><?= isset($categoryError) ? $categoryError : ''; ?></span>
                    
                    <div class="col-lg-8 col-md-6">
                        <select class="form-control" name= "categories" id="categories">
                            <?php 
                                foreach ($categories as $key => $value){
                            ?>
                                <option value="<?= $value ?>"<?= $_POST['categories'] == $value ? 'selected' : ''; ?>><?php echo $key ?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
           
            <div class="mt-3">
                <div class="form-group">
                    <label class="col-lg-4 col-md-6 col-form-label" for="message" >Message: </label> <span class="errorMsg"><?= isset($messageError) ? $messageError : ''; ?></span>
                    <div class="col-lg-8 col-md-6">
                        <input class="form-control" type="text" name="message" id="message">
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <div class="form-group">
                    <div class="col-12">
                        <input class="btn btn-black" type="submit" name="submiTicket" value="Submit Ticket" id="submit">
                    </div>
                </div>
            </div>
        </form>

        <h2>Support Tickets</h2>
        <div class="table-responsive">
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
</div>
  
<?php
    // Closing tag
    require("./components/footer.php");
?>




