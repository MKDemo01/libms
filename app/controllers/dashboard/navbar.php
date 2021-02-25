<?php
declare(strict_types=1);
/**
 * DASHBOARD/navbar controller
 *
 * If the navbar search box is updated, validate and save or clear the search
 * parameters, and display the main navbar at the top of the page
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

// Update Book Title Search, if POSTed from navbar
if (isset($_POST["navSearch"])) {
  if (empty($_POST["navSchString"])) {
    unset($_SESSION["navSchString"]);
  } else {
    $_SESSION["navSchString"] = cleaninput($_POST["navSchString"], "string");
  }
  $_POST = [];
} elseif (isset($_POST["navClear"])) {
  unset($_SESSION["navSchString"]);
  $_POST = [];
}

// Display Navbar Header View
include "../app/views/dashboard/navbarHeader.php";
