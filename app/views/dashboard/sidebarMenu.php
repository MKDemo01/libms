<?php
declare(strict_types=1);
/**
 * DASHBOARD/sidebarMenu view
 *
 * Sidebar menu with welcome message for current logged-in user, a count of
 * their unread messages, and links to each page. An Admin section with
 * additional links is provided for admin users.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

?>
<!-- Sidebar Menu -->
<nav class="col-md-2 d-none d-md-block bg-light sidebar">
  <div class="sidebar-sticky">
    <ul class="nav flex-column">
      <!-- Welcome, Unread Message count & Link to My Messages -->
      <li class="nav-brand">
        <h6 class="ml-2">Welcome, <span id="welcomeUser"><?= $_SESSION["username"] ?></span><span style="margin-left:10px" id="msgsUnread"><?= $msgsUnreadLink ?></span></h6>
        <hr>
      </li>
      <!-- Home -->
      <li class="nav-item">
        <a class="nav-link<?= ($page == "home") ? " active" : ""; ?>" href="dashboard.php?p=home"><span data-feather="home"></span>Home</a>
      </li>
      <!-- Display Books -->
      <li class="nav-item">
        <a class="nav-link<?= (($page == "displayBooks") || ($page == "booksIssuedByBook")) ? " active" : ""; ?>" href="dashboard.php?p=displayBooks"><span data-feather="book"></span>Display Books</a>
      </li>
      <!-- My Issued Books -->
      <li class="nav-item">
        <a class="nav-link<?= ($page == "myIssuedBooks") ? " active" : ""; ?>" href="dashboard.php?p=myIssuedBooks"><span data-feather="book-open"></span>My Issued Books</a>
      </li>
      <!-- Send a Message -->
      <li class="nav-item">
        <a class="nav-link<?= ($page == "sendMessage") ? " active" : ""; ?>" href="dashboard.php?p=sendMessage"><span data-feather="send"></span>Send a Message</a>
        <hr />
      </li>
      <!-- My Profile -->
      <li class="nav-item">
        <a class="nav-link<?= ($page == "myProfile") ? " active" : ""; ?>" href="dashboard.php?p=myProfile"><span data-feather="user"></span>My Profile</a>
        <hr />
      </li>
    </ul><?php
    // Only display Admin section if user is Admin
    if ($_SESSION["userIsAdmin"] == 1) : ?>
      <!-- Admin Section -->
      <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">Admin Section</h6>
      <ul class="nav flex-column">
        <!-- Issue Book-->
        <li class="nav-item">
          <a class="nav-link<?= ($page == "issueBook") ? " active" : ""; ?>" href="dashboard.php?p=issueBook"><span data-feather="arrow-up-circle"></span>
          Issue Book</a>
        </li>
        <!-- List/Return Issued Books -->
        <li class="nav-item">
          <a class="nav-link<?= ($page == "listReturnIssuedBooks") ? " active" : ""; ?>" href="dashboard.php?p=listReturnIssuedBooks"><span data-feather="arrow-down-circle"></span>
          List/Return Issued Books</a>
        </li>
        <!-- Add Book -->
        <li class="nav-item">
          <a class="nav-link<?= ($page == "addBook") ? " active" : ""; ?>" href="dashboard.php?p=addBook"><span data-feather="plus-circle"></span>
          Add Book</a>
        </li>
        <!-- List/Edit Books -->
        <li class="nav-item">
          <a class="nav-link<?= ($page == "listEditBooks" || $page == "bookDetails") ? " active" : ""; ?>" href="dashboard.php?p=listEditBooks"><span data-feather="layers"></span>
          List/Edit Books</a>
        </li>
        <!-- List/Edit Users-->
        <li class="nav-item">
          <a class="nav-link<?= ($page == "listEditUsers" || $page == "userDetails") ? " active" : ""; ?>" href="dashboard.php?p=listEditUsers"><span data-feather="users"></span>
          List/Edit Users</a>
        </li>
      </ul><?php
    endif; ?>
  </div>
</nav>
