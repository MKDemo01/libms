<?php
declare(strict_types=1);
/**
 * DASHBOARD/myProfile controller
 *
 * Retrieve the user record of the current logged-in user and display it in a
 * user form. If either the user data or user password is updated and
 * submitted, validate the data and update the existing user record.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

require_once "../app/models/userClass.php";
$user = new User();

// Get UserID for current logged-in user
$userID = $_SESSION["userID"];

// Update User record if updateUser POSTed
if (isset($_POST["updateUser"])) {
  $username = cleanInput($_POST["username"], "string");
  $firstName = cleanInput($_POST["firstName"], "string");
  $lastName = cleanInput($_POST["lastName"], "string");
  $email = cleanInput($_POST["email"], "email");
  $contactNo = cleanInput($_POST["contactNo"], "string");
  // Update Database Entry for User Record
  $updateUser = $user->updateRecord($userID, $username, $firstName, $lastName, $email, $contactNo);
  if ($updateUser == 1) {  // If update successful, update welcome message?>
    <script>
      document.getElementById("welcomeUser").innerHTML = "<?= $_SESSION["username"] ?>";
    </script><?php
  }
} elseif (isset($_POST["updatePassword"])) {  // Update Password if UpdatePassword POSTed
  $existingPassword = cleanInput($_POST["existingPassword"], "password");
  $newPassword = cleanInput($_POST["newPassword"], "password");
  // Update Database Entry for User Password
  $updatePassword = $user->updatePassword($userID, $newPassword, $existingPassword, false);
  unset($existingPassword, $newPassword);
}
$_POST = [];

// Get User Details for selected record
$userRecord = $user->getRecord($userID);

// Prep User Form Data
$formData = [
  "formUsage" => "profileUpdate",
  "formTitle" => "My Profile",
  "submitName" => "updateUser",
  "submitText" => "Update User",
];

// Show User Form
include "../app/views/dashboard/userForm.php";
