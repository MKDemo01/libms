<?php
declare(strict_types=1);
/**
 * DASHBOARD/myMessages controller
 *
 * Retrieve all the active RECEIVED messages and all the SENT messages for the
 * current logged-in user, and display them using the 'messagesList' view.
 * Process any status updates that are selected.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

require_once "../app/models/messageClass.php";
$message = new Message();

// Get recordID if provided and process Status changes if hyperlinks clicked
$messageID = 0;
if (isset($_GET["id"])) {
  $messageID = cleanInput($_GET["id"], "int");

  if (isset($_GET["updMessageStatus"])) {  // MessageStatus link was clicked
    $curStatus = cleanInput($_GET["cur"], "int");
    $newStatus = statusCycle("MessageStatus", $curStatus);
    // Update Message MessageStatus
    $updateStatus = $message->updateStatus("MessageStatus", $messageID, $newStatus);
  } elseif (isset($_GET["updRecordStatus"])) {  // RecordStatus link was clicked
    $curStatus = cleanInput($_GET["cur"], "int");
    $newStatus = statusCycle("RecordStatus", $curStatus);
    // Update Message RecordStatus
    $updateStatus = $message->updateStatus("RecordStatus", $messageID, $newStatus);
  }
  // Update Unread Message Link
  $msgsUnreadLink = getMsgsUnreadLink();?>
  <script>
    document.getElementById("msgsUnread").innerHTML = "<?= $msgsUnreadLink ?>";
  </script><?php
}
$_GET = [];

// Get List of ACTIVE RECEIVED messages for current user
$userID = $_SESSION["userID"];
$receivedMessageList = $message->getListReceived($userID, 1, 1);

// Get List of ALL SENT messages for current user
$sentMessageList = $message->getListSent($userID, null, 1);

// Show My Messages View
include "../app/views/dashboard/messagesList.php";
