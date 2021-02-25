<?php
declare(strict_types=1);
/**
 * DASHBOARD/listEditUsers controller
 *
 * Retrieve all the users records, filtered by Username if search criteria are
 * entered, and display them using the 'usersList' view. Process any status
 * updates that are selected.
 * 
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

require_once "../app/models/userClass.php";
$user = new User();

// Get recordID if provided and process Status changes if hyperlinks clicked
$userID = 0;
if (isset($_GET["id"])) {
  $userID = cleanInput($_GET["id"], "int");

  if (isset($_GET["updIsAdmin"])) {  // IsAdmin link was clicked
    $curStatus = cleanInput($_GET["cur"], "int");
    $newStatus = statusCycle("IsAdmin", $curStatus);
    // Update User IsAdmin
    $updateStatus = $user->updateStatus("IsAdmin", $userID, $newStatus);
  } elseif (isset($_GET["updUserStatus"])) {  // UserStatus link was clicked
    $curStatus = cleanInput($_GET["cur"], "int");
    $newStatus = statusCycle("UserStatus", $curStatus);
    // Update User UserStatus
    $updateStatus = $user->updateStatus("UserStatus", $userID, $newStatus);
  } elseif (isset($_GET["updRecordStatus"])) {  // RecordStatus link was clicked
    $curStatus = cleanInput($_GET["cur"], "int");
    $newStatus = statusCycle("RecordStatus", $curStatus);
    // Update User RecordStatus
    $updateStatus = $user->updateStatus("RecordStatus", $userID, $newStatus);
    // Update Unread Message Link as user may have outstanding messages
    $msgsUnreadLink = getMsgsUnreadLink();?>
    <script>
      document.getElementById("msgsUnread").innerHTML = "<?= $msgsUnreadLink ?>";
    </script><?php
  }
}
$_GET = [];

// Fix Username Search, if entered
$username = null;
if (isset($_POST["userSearch"])) {
  $username = fixSearch($_POST["schUsername"]);
}
$_POST = [];

// Get List of users, excluding current user
$userList = $user->getList($username, true);

// Display Users List View
include "../app/views/dashboard/usersList.php";
