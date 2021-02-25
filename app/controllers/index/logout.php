<?php
declare(strict_types=1);
/**
 * INDEX/logout controller
 *
 * Logout User and display the logout page.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

require_once "../app/models/userClass.php";
$user = new User();

// Logout User if logout hyperlink clicked
if (isset($_GET["q"])) {
  $user->logout();
}
$_GET = [];

// Show Logout Page View
include "../app/views/index/logoutPage.php";

// Clear Session
session_unset();
session_destroy();
