<?php
declare(strict_types=1);
/**
 * INDEX/registerForm view
 *
 * Form for registering a new users login and contact information.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

?>
<!-- Registration Form -->
<div class="row justify-content-center">
  <form class="form-user" action="" method="post" name="registerForm" autocomplete="off">
    <h3 class="mb-3">User Registration Form</h3>
    <!-- User Name -->
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text form-labels">Username:</span>
      </div>
      <input class="form-control" type="text" name="username" maxlength="40" placeholder="Enter Username" value="<?= $newUserRecord["Username"] ?>" required autofocus />
    </div>
    <!-- Password -->
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text form-labels">Password:</span>
      </div>
      <input class="form-control" type="password" name="password" minlength="5" placeholder="Enter Password" required />
    </div>
    <!-- First Name -->
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text form-labels">First Name:</span>
      </div>
      <input class="form-control" type="text" name="firstName" maxlength="40" placeholder="Enter First Name" value="<?= $newUserRecord["FirstName"] ?>" />
    </div>
    <!-- Last Name -->
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text form-labels">Last Name:</span>
      </div>
      <input class="form-control" type="text" name="lastName" maxlength="40" placeholder="Enter Last Name" value="<?= $newUserRecord["LastName"] ?>" />
    </div>
    <!-- Email Address -->
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text form-labels">Email:</span>
      </div>
      <input class="form-control" type="email" name="email" maxlength="40" placeholder="Enter Email Address" value="<?= $newUserRecord["Email"] ?>" required />
    </div>
    <!-- Contact Number -->
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text form-labels">Contact No:</span>
      </div>
      <input class="form-control" type="tel" name="contactNo" maxlength="40" placeholder="Enter Contact Telephone Number" value="<?= $newUserRecord["ContactNo"] ?>" />
    </div>
    <br />
    <!-- Submit Button -->
    <button class="btn btn-lg btn-primary btn-block" type="submit" name="register">Register</button>
    <!-- Back to Login Link -->
    <a href="index.php?p=login">Back to Login</a>
    <br />
  </form>
</div>

<!-- System Messages -->
<div class="row justify-content-center"><?php
  msgShow(); ?>
</div>
