<?php
declare(strict_types=1);
/**
 * INDEX/login controller
 *
 * Prepare and show a blank login form for user entry. If the form is submitted,
 * validate the data and if passed open the dashboard home page.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

require_once "../app/models/userClass.php";
$user = new User();

// Verify & Login User if Login Form POSTed
if (isset($_POST["login"])){
  $username = cleanInput($_POST["lmsUsername"], "string");
  $password = cleanInput($_POST["lmsPassword"], "password");
  $_POST = [];

  // Check database entry for user & update session variables
  $login = $user->login($username, $password);
  unset($password);
  if ($login) {  // Login Success
    header("location:dashboard.php?p=home");
  }
}

// Show Login View/Form
include "../app/views/index/loginForm.php";
