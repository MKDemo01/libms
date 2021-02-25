<?php
declare(strict_types=1);
/**
 * DASHBOARD/displayBooks controller
 *
 * Retrieve all the active books records, filtered by Title if search criteria
 * are entered, and display them using the 'bookCards' view.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

require_once "../app/models/bookClass.php";
$book = new Book();

// Fix Book Title Search, if available in $_SESSION
$title = null;
if (isset($_SESSION["navSchString"])) {
  $title = fixSearch($_SESSION["navSchString"]);
}

// Get Display List of ACTIVE books
$bookDisplay = $book->getDisplay($title);

// Show Book Cards View
include "../app/views/dashboard/bookCards.php";
