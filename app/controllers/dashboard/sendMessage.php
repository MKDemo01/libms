<?php
declare(strict_types=1);
/**
 * DASHBOARD/sendMessage controller
 *
 * Prepare and show a blank message form for user entry, or pre-populate it if
 * this is a reply from a received message. Display the relevant user
 * record when selected. If the form is submitted, validate the data and add a
 * new messages record.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

require_once "../app/models/messageClass.php";
$message = new Message();
require_once "../app/models/userClass.php";
$user = new User();

// Get recipient details if passed from MyMessages
if (isset($_GET["reply"])) {
  $originalMsgID = cleanInput($_GET["id"], "int");
  $_POST["userIDSelected"] = cleanInput($_GET["recID"], "int");
  $_POST["subject"] = cleanInput($_GET["sub"], "string");
  // Mark original message as Read
  $updateStatus = $message->updateStatus("MessageStatus", $originalMsgID, 1);
  // Update Unread Message Link
  $msgsUnreadLink = getMsgsUnreadLink();?>
  <script>
    document.getElementById("msgsUnread").innerHTML = "<?= $msgsUnreadLink ?>";
  </script><?php
}
$_GET = [];

// Create New messages record if Send POSTed
if (isset($_POST["sendMessage"])) {
  if ($_SESSION["userID"] == $_POST["userIDSelected"]) {
    // Check that user is not sending a message to themselves > not now possible!
    $_SESSION["message"] = msgPrep("danger", "Error - You cannot send a message to yourself!");
  } else {
    $senderID = $_SESSION["userID"];
    $receiverID = cleanInput($_POST["userIDSelected"], "int");
    $subject = cleanInput($_POST["subject"], "string");
    $body = cleanInput($_POST["body"], "string");
    // Create messages Database Entry
    $newMsgID = $message->add($senderID, $receiverID, $subject, $body);
    if ($newMsgID) {  // Add Message Success
      $_POST = [];
      $_SESSION["message"] = msgPrep("success", "Issue of Message to User '{$receiverID}' was successful.");
    }
  }
}

// Initialise Message Record
$messageRecord = [
  "ReceiverID" => postValue("userIDSelected", 0),
  "Subject" => postValue("subject"),
  "Body" => postValue("body"),
];

// Get User Record for Selected Receiver ID
$userRecord = [];
if (isset($messageRecord["ReceiverID"])) {
  $selectedUserID = cleanInput($messageRecord["ReceiverID"], "int");
  $userRecord = $user->getRecord($selectedUserID);
}

// Show Message Form
include "../app/views/dashboard/messageForm.php";
