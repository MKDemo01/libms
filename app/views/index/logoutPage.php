<?php
declare(strict_types=1);
/**
 * INDEX/logoutPage view
 *
 * Logout confirmation page.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

?>
<!-- Logout Page -->
<div class="row justify-content-center mb-3">
  <h2>Goodbye.</h2>
</div>
<br />

<!-- System Messages -->
<div class="row justify-content-center mb-3"><?php
  msgShow(); ?>
</div>

<!-- Re-Login Links -->
<div class="row justify-content-center">
  <a href="index.php?p=login">Back to Login</a>
</div>
