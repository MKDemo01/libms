<?php
declare(strict_types=1);
/**
 * DASHBOARD/userDetails controller
 *
 * Retrieve the record of a specific user and display it in a user form. If the
 * form is updated and submitted, validate the data and update the existing
 * users record.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

require_once "../app/models/userClass.php";
$user = new User();

// Get recordID if provided
$userID = 0;
if (isset($_GET["id"])) {
  $userID = cleanInput($_GET["id"], "int");
}
$_GET = [];

// Update User record if updateUser POSTed
if (isset($_POST["updateUser"])) {
  $username = cleanInput($_POST["username"], "string");
  $firstName = cleanInput($_POST["firstName"], "string");
  $lastName = cleanInput($_POST["lastName"], "string");
  $email = cleanInput($_POST["email"], "email");
  $contactNo = cleanInput($_POST["contactNo"], "string");
  // Update Database Entry for User Record
  $updateUser = $user->updateRecord($userID, $username, $firstName, $lastName, $email, $contactNo);
} elseif (isset($_POST["updatePassword"])) {  // Update Password if UpdatePassword POSTed
  $newPassword = cleanInput($_POST["newPassword"], "password");
  // Update Database Entry for User Password
  $updatePassword = $user->updatePassword($userID, $newPassword, null, true);
  unset($existingPassword, $newPassword);
}
$_POST = [];

// Get User Details for selected record
$userRecord = $user->getRecord($userID);

// Prep User Form Data
$formData = [
  "formUsage" => "adminUpdate",
  "formTitle" => "User Details - ID: {$userID}",
  "submitName" => "updateUser",
  "submitText" => "Update User",
];

// Show User Form
include "../app/views/dashboard/userForm.php";
