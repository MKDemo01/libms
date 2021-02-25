<?php
declare(strict_types=1);
/**
 * DASHBOARD/booksIssuedList view
 *
 * Table layout, with search option, to display a list of books_issued records,
 * including Return option, using the {@see \LibraryMS\BookIssuedList} class.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

use RecursiveArrayIterator;

include_once "../app/models/bookIssuedListClass.php";

?>
<!-- Books Issued List - Header -->
<div class="pt-3 pb-2 mb-3 border-bottom">
  <div class="row">
    <!-- Title -->
    <div class="col-6">
      <h1 class="h2">Issued Books</h1>
    </div>
    <!-- System Messages -->
    <div class="col-6"><?php
      msgShow(); ?>
    </div>
  </div>
</div>

<div class="row">
  <!-- Books Issued - Search -->
  <div class="col-4 mb-3">
    <form action="" method="post" name="schBooksIssued" autocomplete="off">
      <div class="input-group">
        <input class="form-control" type="text" name="schString" maxlength="40" placeholder="Search Book Title or Username" />
        <div class="input-group-append">
          <button class="btn btn-secondary" type="submit" name="bksIssSearch"><span data-feather="search"></span></button>
        </div>
      </div>
    </form>
  </div>
  <div class="col-8"></div>
</div>

<!-- Books Issued List -->
<div class="table-responsive">
  <table class="table table-striped table-sm">
    <thead>
      <tr>
        <th>Book Title</th>
        <th>Issued To</th>
        <th>Issued Date</th>
        <th>Return Due Date</th>
        <th>Returned Date</th>
        <th>Record</th>
      </tr>
    </thead>
    <tbody><?php
      if (empty($booksIssuedList)) :  // No books_issued records found ?>
        <tr>
          <td colspan="6">No Issued Books to Display</td>
        </tr><?php
      else :
        // Loop through the books_issued records and output the values
        foreach (new BookIssuedList(new RecursiveArrayIterator($booksIssuedList)) as $value) :
          echo $value;
        endforeach;
      endif; ?>
    </tbody>
  </table>
</div>
