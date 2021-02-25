<?php
declare(strict_types=1);
/**
 * DASHBOARD/booksIssuedByBook controller
 *
 * Retrieve all the active and outstanding books_issued records for a specific
 * book, plus the relevant books record, and display them using the
 * 'booksIssuedByBookList' view.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

require_once "../app/models/bookIssuedClass.php";
$bookIssued = new BookIssued();
require_once "../app/models/bookClass.php";
$book = new Book();

// Get recordID & bookRecord if provided
$bookID = 0;
$bookRecord = [];
if (isset($_GET["id"])) {
  $bookID = cleanInput($_GET["id"], "int");
  $bookRecord = $book->getRecord($bookID);
}
$_GET = [];

// Get List of ACTIVE & OUTSTANDING books_issued for BookID
$booksIssuedByBook = $bookIssued->getListByBook($bookID, 1, true);

// Show Books Issued By Book List View
include "../app/views/dashboard/booksIssuedByBookList.php";
