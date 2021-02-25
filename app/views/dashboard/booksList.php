<?php
declare(strict_types=1);
/**
 * DASHBOARD/booksList view
 *
 * Table layout, with search option, to display a list of books records,
 * using the {@see \LibraryMS\BookList} class.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

use RecursiveArrayIterator;

include_once "../app/models/bookListClass.php";

?>
<!-- Books List - Header -->
<div class="pt-3 pb-2 mb-3 border-bottom">
  <div class="row">
    <!-- Title -->
    <div class="col-6">
      <h1 class="h2">Books</h1>
    </div>
    <!-- System Messages -->
    <div class="col-6"><?php
      msgShow(); ?>
    </div>
  </div>
</div>

<div class="row">
  <!-- Books - Search -->
  <div class="col-4 mb-3">
    <form action="" method="post" name="schBooks" autocomplete="off">
      <div class="input-group">
        <input class="form-control" type="text" name="schTitle" maxlength="40" placeholder="Search Title" />
        <div class="input-group-append">
          <button class="btn btn-secondary" type="submit" name="bookSearch"><span data-feather="search"></span></button>
        </div>
      </div>
    </form>
  </div>
  <div class="col-8"></div>
</div>

<!-- Books List -->
<div class="table-responsive">
  <table class="table table-striped table-sm">
    <thead>
      <tr>
        <th>Title</th>
        <th>Author</th>
        <th>Publisher</th>
        <th>ISBN</th>
        <th>Price<br />(<?= Constants::getDefaultValues()["currency"] ?>)</th>
        <th>Qty<br />Total</th>
        <th>Qty<br />Available</th>
        <th>Record</th>
      </tr>
    </thead>
    <tbody><?php
      if (empty($bookList)) :  // No books records found ?>
        <tr>
          <td colspan="10">No Books to Display</td>
        </tr><?php
      else :
        // Loop through the books records and output the values
        foreach (new BookList(new RecursiveArrayIterator($bookList)) as $value) :
          echo $value;
        endforeach;
      endif; ?>
    </tbody>
  </table>
</div>
