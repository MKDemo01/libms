<?php
declare(strict_types=1);
/**
 * DASHBOARD/bookRecord view
 *
 * Table layout to display a single selected books record.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

?>
<!-- Selected books record details -->
<div class="table-responsive">
  <table class="table table-striped table-sm">
    <thead>
      <tr>
        <th>Image</th>
        <th>Title</th>
        <th>Author</th>
        <th>Publisher</th>
        <th>ISBN</th>
        <th>Total Qty</th>
        <th>Available</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><img class="img-thumbnail" style="width:105px; height:165px" src="<?= getFilePath($bookRecord["BookID"], $bookRecord["ImgFilename"]) ?>" alt="<?= $bookRecord["ImgFilename"] ?>" /></td>
        <td><?= $bookRecord["Title"] ?></td>
        <td><?= $bookRecord["Author"] ?></td>
        <td><?= $bookRecord["Publisher"] ?></td>
        <td><?= $bookRecord["ISBN"] ?></td>
        <td><?= $bookRecord["QtyTotal"] ?></td>
        <td><?= $bookRecord["QtyAvail"] ?></td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="qtyAvail" value="<?= $bookRecord["QtyAvail"] ?>" />
</div>
