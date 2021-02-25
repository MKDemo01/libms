<?php
declare(strict_types=1);
/**
 * DASHBOARD/userRecord view
 *
 * Table layout to display a single selected users record.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

?>
<!-- Selected users record details -->
<div class="table-responsive">
  <table class="table table-striped table-sm">
    <thead>
      <tr>
        <th>Username</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Contact No</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?= $userRecord["Username"] ?></td>
        <td><?= $userRecord["FirstName"] ?></td>
        <td><?= $userRecord["LastName"] ?></td>
        <td><?= $userRecord["Email"] ?></td>
        <td><?= $userRecord["ContactNo"] ?></td>
      </tr>
    </tbody>
  </table>
</div>
