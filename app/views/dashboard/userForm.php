<?php
declare(strict_types=1);
/**
 * DASHBOARD/userForm view
 *
 * Form for editing a users record, with a separate form to change their
 * password.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

?>
<!-- User Form - Header -->
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

if (empty($userRecord)) :  // Users record not found ?>
  <div>User ID not found.</div><?php
else :  // Display User & Password Forms ?>
  <!-- User Form -->
  <form class="ml-3" action="" method="post" name="userForm" autocomplete="off">
    <!-- User Name -->
    <div class="form-group row">
      <label class ="col-form-label lab-fixed" for="username">Username:</label>
      <div class="inp-fixed">
        <input class="form-control" type="text" name="username" id="username" maxlength="40" placeholder="Enter Username" value="<?= $userRecord["Username"] ?>" required />
      </div>
    </div>
    <!-- First Name -->
    <div class="form-group row">
      <label class ="col-form-label lab-fixed" for="firstName">First Name:</label>
      <div class="inp-fixed">
        <input class="form-control" type="text" name="firstName" id="firstName" maxlength="40" placeholder="Enter First Name" value="<?= $userRecord["FirstName"] ?>" />
      </div>
    </div>
    <!-- Last Name -->
    <div class="form-group row">
      <label class ="col-form-label lab-fixed" for="lastName">Last Name:</label>
      <div class="inp-fixed">
        <input class="form-control" type="text" name="lastName" id="lastName" maxlength="40" placeholder="Enter Last Name" value="<?= $userRecord["LastName"] ?>" />
      </div>
    </div>
    <!-- Email Address -->
    <div class="form-group row">
      <label class ="col-form-label lab-fixed" for="email">Email:</label>
      <div class="inp-fixed">
        <input class="form-control" type="email" name="email" id="email" maxlength="40" placeholder="Enter Email Address" value="<?= $userRecord["Email"] ?>" required />
      </div>
    </div>
    <!-- Contact Number -->
    <div class="form-group row">
      <label class ="col-form-label lab-fixed" for="contactNo">Contact No:</label>
      <div class="inp-fixed">
        <input class="form-control" type="tel" name="contactNo" id="contactNo" maxlength="40" placeholder="Enter Contact Telephone Number" value="<?= $userRecord["ContactNo"] ?>" />
      </div>
    </div>
    <!-- Submit Button -->
    <div class="form-group row">
      <div class="col-form-label lab-fixed">
        <button class="btn btn-primary" type="submit" name="<?= $formData["submitName"] ?>"><?= $formData["submitText"] ?></button>
      </div>
      <div class="inp-fixed"></div>
    </div>
  </form>
  <hr />

  <!-- Password Form -->
  <form class="ml-3" action="" method="post" name="passwordForm" autocomplete="off"><?php
    if ($formData["formUsage"] == "profileUpdate") :  // Require entry of Existing Password when updating MyProfile ?>
      <!-- Existing Password -->
      <div class="form-group row">
        <label class ="col-form-label lab-fixed" for="existingPW">Existing Password:</label>
        <div class="inp-fixed">
          <input class="form-control" type="password" name="existingPassword" id="existingPW" placeholder="Enter Existing Password" required />
        </div>
      </div><?php
    endif;?>
    <!-- New Password -->
    <div class="form-group row">
      <label class ="col-form-label lab-fixed" for="newPW">New Password:</label>
      <div class="inp-fixed">
        <input class="form-control" type="password" name="newPassword" id="newPW" minlength="5" placeholder="Enter New Password" required />
      </div>
    </div>
    <!-- Submit Button -->
    <div class="form-group row">
      <div class="col-form-label lab-fixed">
        <button class="btn btn-primary" type="submit" name="updatePassword" id="updatePassword">Update Password</button>
      </div>
      <div class="inp-fixed"></div>
    </div>
  </form><?php
endif;
