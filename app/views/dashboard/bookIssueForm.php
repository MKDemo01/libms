<?php
declare(strict_types=1);
/**
 * DASHBOARD/bookIssueForm view
 *
 * Form for adding a books_issued record. The user record and book record are
 * displayed once selected.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

?>
<!-- Book Issue Form - Header -->
<div class="pt-3 pb-2 mb-3 border-bottom">
  <div class="row">
    <!-- Title -->
    <div class="col-6">
      <h1 class="h2">Issue Book</h1>
    </div>
    <!-- System Messages -->
    <div class="col-6"><?php
      msgShow(); ?>
    </div>
  </div>
</div>

<!-- Book Issue Form -->
<form class="ml-3" action="" method="post" name="bookIssueForm">
  <!-- Select User -->
  <div class="form-group row">
    <label class="col-form-label lab-fixed" for="userSelect">Select User:</label>
    <div class="input-group inp-fixed">
      <select class="form-control" id="userSelect" name="userIDSelected">
        <!-- List all UserID: UserNames (including Current User) --><?php
        userOptions($booksIssuedRecord["UserID"], false); ?>
      </select>
      <div class="input-group-append">
        <button class="btn btn-primary" type="submit" name="selectUser">Select</button>
      </div>
    </div>
  </div><?php
  // Show Selected User Record & Select Book
  if (!empty($userRecord)) : ?>
    <div class="form-group row pr-3"><?php
      include "../app/views/dashboard/userRecord.php"; ?>
    </div>
    <!-- Select Book -->
    <div class="form-group row">
      <label class="col-form-label lab-fixed" for="bookSelect">Select Book:</label>
      <div class="input-group inp-fixed">
        <select class="form-control" id="bookSelect" name="bookIDSelected">
          <!-- List all BookID: Titles --><?php
          bookOptions($booksIssuedRecord["BookID"]); ?>
        </select>
        <div class="input-group-append">
          <button class="btn btn-primary" type="submit" name="selectBook">Select</button>
        </div>
      </div>
    </div><?php
  endif;
  // Show Selected Book Record & Select Issue Date and Return Due Date
  if (!empty($bookRecord)) : ?>
    <div class="form-group row pr-3"><?php
      include "../app/views/dashboard/bookRecord.php"; ?>
    </div>
    <!-- Update Dates and Submit -->
    <div class="form-group row">
      <div class="input-group">
        <!-- Issued Date -->
        <label class="col-form-label mr-3" for="issuedDate">Issued Date:</label>
        <input class="form-control mr-5" type="date" name="issuedDate" id="issuedDate" min="<?= $booksIssuedRecord["EarliestIssueDate"] ?>" value="<?= $booksIssuedRecord["IssuedDate"] ?>" required />
        <!-- Return Due Date -->
        <label class="col-form-label mr-3" for="returnDate">Return Due Date:</label>
        <input class="form-control mr-5" type="date" name="returnDueDate" id="returnDate" min="<?= $booksIssuedRecord["EarliestReturnDate"] ?>" value="<?= $booksIssuedRecord["ReturnDueDate"] ?>" required />
        <!-- Submit Button -->
        <button class="btn btn-primary mr-3" type="submit" name="issueBook" id="issueBook">Issue Book</button>
      </div>
    </div><?php
  endif; ?>
</form>
