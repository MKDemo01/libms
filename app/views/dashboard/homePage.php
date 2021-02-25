<?php
declare(strict_types=1);
/**
 * DASHBOARD/homePage view
 *
 * Home page information and instructions. Additional administration
 * instructions provided for admin users.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

?>
<!-- Home - Header -->
<div class="pt-3 pb-2 mb-3 border-bottom">
  <div class="row">
    <!-- Title -->
    <div class="col-6">
      <h1 class="h2">Home</h1>
    </div>
    <!-- System Messages -->
    <div class="col-6"><?php
      msgShow(); ?>
    </div>
  </div>
</div><?php

// Display SuperGlobals on Home Page if in Development Mode
if (Constants::getDefaultValues()["environment"] == "development") : ?>
  <!-- PHP Global Variables -->
  <div>
    <pre><?php
      echo "SESSION: ";
      print_r($_SESSION);
      echo "<br />POST: ";
      print_r($_POST);
      echo "<br />GET: ";
      print_r($_GET);
      echo "<br />FILES: ";
      print_r($_FILES);
      // echo "<br />SERVER: ";
      // print_r($_SERVER);
      echo "<br />";
      ?>
    </pre>
  </div><?php
endif; ?>

<!-- Welcome message & instructions -->
<div>
  <h5>Welcome to the Library Management System.</h5><br />
  <p>Click on '<span data-feather="book"></span> Display Books' to see all of the books in our library, and their current availability. You can also use the 'Search' option at the top of the page to search for any book by its title.</p>
  <p>Click on '<span data-feather="book-open"></span> My Issued Books' to see all of the books that have been issued to you, including any currently outstanding.</p>
  <p>To see your messages, click on the '<span data-feather='mail'></span>' mail link, or click on '<span data-feather="send"></span> Send a Message' to send a message to another library user.</p>
  <p>To update your name, email address, contact details and password, click on '<span data-feather="user"></span> My Profile'.</p>
  <h6>We hope you enjoy using our Library!</h6>
  <br />
</div><?php  // If Admin User show Admin instructions

if ($_SESSION["userIsAdmin"] == "1") :   ?>
  <!-- Admin Instructions -->
  <div>
    <hr />
    <p>As you are an <b>Admin</b> User, you can also:</p>
    <ul>
      <li>Issue a Book to a User</li>
      <li>List all Issued Books and mark an issued book as 'Returned'</li>
      <li>Add a new book to the Library</li>
      <li>List and Edit the details of all Books in the Library</li>
      <li>List and Edit all Users currently registered in the Library</li>
    </ul>
  </div><?php
endif;
