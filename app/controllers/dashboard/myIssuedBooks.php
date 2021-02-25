<?php
declare(strict_types=1);
/**
 * DASHBOARD/myIssuedBooks controller
 *
 * Retrieve all the active and outstanding books_issued records for the current
 * logged-in user, and display them using the 'booksIssuedByUserList' view.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

require_once "../app/models/bookIssuedClass.php";
$bookIssued = new BookIssued();

// Get List of ACTIVE books_issued for current user
$userID = $_SESSION["userID"];
$booksIssuedToMe = $bookIssued->getListByUser($userID, 1, false);

// Show Books Issued By User List View
include "../app/views/dashboard/booksIssuedByUserList.php";
