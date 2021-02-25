<?php
declare(strict_types=1);
/**
 * INDEX/loginForm view
 *
 * Form for entering a users login information.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

?>
<!-- Login Form -->
<div class="row">
  <form class="form-user" action="" method="post" name="loginForm">
    <h3 class="mb-3">Please sign in</h3>
    <!-- User Name -->
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text form-labels">Username:</span>
      </div>
      <input class="form-control" type="text" name="lmsUsername" maxlength="40" placeholder="Enter Username" required autofocus />
    </div>
    <!-- Password -->
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text form-labels">Password:</span>
      </div>
      <input class="form-control" type="password" name="lmsPassword"  placeholder="Enter Password" required />
    </div>
    <br />
    <!-- Submit Button -->
    <button class="btn btn-lg btn-primary btn-block" type="submit" name="login" >Sign in</button>
    <!-- New Account Links -->
    <a href="index.php?p=register">Create new account</a>
  </form>
</div>

<!-- System Messages -->
<div class="row justify-content-center"><?php
  msgShow(); ?>
</div>
