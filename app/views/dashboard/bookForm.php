<?php
declare(strict_types=1);
/**
 * DASHBOARD/bookForm view
 *
 * Form for adding/editing a books record.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

?>
<!-- Book Form - Header -->
<div class="pt-3 pb-2 mb-3 border-bottom">
  <div class="row">
    <!-- Title -->
    <div class="col-6">
      <h1 class="h2"><?= $formData["formTitle"] ?></h1>
    </div>
    <!-- System Messages -->
    <div class="col-6"><?php
      msgShow(); ?>
    </div>
  </div>
</div><?php

if (empty($bookRecord)) :  // Books record not found ?>
  <div>Book ID not found.</div><?php
else :  // Display Book Form ?>
  <!-- Book Form -->
  <form class="ml-3" action="" enctype="multipart/form-data" method="post" name="bookForm" autocomplete="off">
    <div class="row">
      <!-- Left Panel -->
      <div class="col-6">
        <!-- Title -->
        <div class="form-group row">
          <label class="col-form-label lab-fixed" for="title">Title:</label>
          <div class="inp-fixed">
            <input class="form-control" type="text" name="title" id="title" maxlength="40" placeholder="Enter Title" value="<?= $bookRecord["Title"] ?>" required autofocus />
          </div>
        </div>
        <!-- Author -->
        <div class="form-group row">
          <label class="col-form-label lab-fixed" for="author">Author:</label>
          <div class="inp-fixed">
            <input class="form-control" type="text" name="author" id="author" maxlength="40" placeholder="Enter Author" value="<?= $bookRecord["Author"] ?>" required />
          </div>
        </div>
        <!-- Publisher -->
        <div class="form-group row">
          <label class="col-form-label lab-fixed" for="publisher">Publisher:</label>
          <div class="inp-fixed">
            <input class="form-control" type="text" name="publisher" id="publisher" maxlength="40" placeholder="Enter Publisher" value="<?= $bookRecord["Publisher"] ?>" required />
          </div>
        </div>
        <!-- ISBN -->
        <div class="form-group row">
          <label class="col-form-label lab-fixed" for="ISBN">ISBN:</label>
          <div class="inp-fixed">
            <input class="form-control" type="text" name="ISBN" id="ISBN" placeholder="Enter ISBN (Format: 97#-#-###-#####-#)" pattern="(?:(?=.{17}$)97[89][ -](?:[0-9]+[ -]){2}[0-9]+[ -][0-9]|97[89][0-9]{10}|(?=.{13}$)(?:[0-9]+[ -]){2}[0-9]+[ -][0-9Xx]|[0-9]{9}[0-9Xx])" value="<?= $bookRecord["ISBN"] ?>" />
          </div>
        </div>
        <!-- Price -->
        <div class="form-group row">
          <label class="col-form-label lab-fixed" for="price">Price (<?= Constants::getDefaultValues()["currency"] ?>):</label>
          <div class="inp-fixed">
            <input class="form-control" type="number" name="price" id="price" placeholder="Enter Price in <?= Constants::getDefaultValues()["currency"] ?>" min="0" max="99999999.99" step="0.01" value="<?= $bookRecord["Price"] ?>" required />
          </div>
        </div><?php
        if ($formData["formUsage"] == "Add") :  // Display Just Quantity ?>
          <!-- Quantity -->
          <div class="form-group row">
            <label class="col-form-label lab-fixed" for="quantity">Quantity:</label>
            <div class="inp-fixed">
              <input class="form-control" type="number" name="quantity" id="quantity" placeholder="Enter Quantity" min="0" max="2147483647" value="<?= $bookRecord["Quantity"] ?>" required />
            </div>
          </div><?php
        else :  // Display QtyTotal, QtyAvail & Added Details ?>
          <!-- QtyTotal -->
          <div class="form-group row">
            <label class="col-form-label lab-fixed" for="qtyTotal">Total Quantity:</label>
            <div class="inp-fixed">
              <input class="form-control" type="number" name="qtyTotal" id="qtyTotal" placeholder="Enter Total Quantity" min="0" max="2147483647" value="<?= $bookRecord["QtyTotal"] ?>" required />
            </div>
          </div>
          <!-- QtyAvail -->
          <div class="form-group row">
            <label class="col-form-label lab-fixed" for="qtyAvail">Quantity Available:</label>
            <div class="inp-fixed">
              <input class="form-control" type="number" name="qtyAvail" id="qtyAvail" placeholder="Enter Quantity Available" min="0" max="2147483647" value="<?= $bookRecord["QtyAvail"] ?>" required />
            </div>
          </div>
          <!-- Added Details -->
          <div class="form-group row">
            <label class="col-form-label lab-fixed" for="added">Added:</label>
            <div class="inp-fixed">
              <p class="form-control" id="added"><?= date("d/m/Y @ H:i", strtotime($bookRecord["AddedTimestamp"])) . " by UserID: " ?><a class="badge badge-info" href="dashboard.php?p=userDetails&id=<?= $bookRecord["AddedUserID"] ?>"><?= $bookRecord["AddedUserID"] ?></a></p>
            </div>
          </div>
          <?php
        endif; ?>
      </div>

      <!-- Right Panel -->
      <div class="col-6">
        <!-- Image Filename -->
        <div class="form-group row">
          <label class="col-form-label lab-fixed">Book Image:</label>
          <div class="custom-file inp-fixed">
            <input type="hidden" name="MAX_FILE_SIZE" value="<?= Constants::getDefaultValues()['maxUploadSize']?>" />
            <label class="custom-file-label" for="imgFilename">Choose file</label>
            <input class="custom-file-input" type="file" name="imgFilename" id="imgFilename" />
          </div>
        </div><?php
        if ($formData["formUsage"] == "Update") : ?>
          <!-- Current Image -->
          <div class="form-group row">
            <label class="col-form-label lab-fixed" for="image">Current Image:</label>
            <img class="img-thumbnail" width="140" height="220" id="image" src="<?= getFilePath($bookID, $bookRecord["ImgFilename"]) ?>" alt="<?= $bookRecord["ImgFilename"] ?>" />
            <input type="hidden" name="origImgFilename" value="<?= $bookRecord["ImgFilename"] ?>" />
          </div><?php
        endif; ?>
        <!-- Submit Button -->
        <div class="form-group row mt-4">
          <div class="col-form-label lab-fixed">
            <button class="btn btn-primary" type="submit" name="<?= $formData["submitName"] ?>"><?= $formData["submitText"] ?></button>
          </div>
          <div class="inp-fixed"></div>
        </div>
      </div>
    </div>
  </form><?php
endif;
