<?php
declare(strict_types=1);
/**
 * DASHBOARD/messageForm view
 *
 * Form for sending a message (adding a messages record). The user record of the
 * recipient is displayed once selected.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

?>
<!-- Message Form - Header -->
<div class="pt-3 pb-2 mb-3 border-bottom">
  <div class="row">
    <!-- Title -->
    <div class="col-6">
      <h1 class="h2">Send a Message</h1>
    </div>
    <!-- System Messages -->
    <div class="col-6"><?php
      msgShow(); ?>
    </div>
  </div>
</div>

<!-- Message Form -->
<form class="ml-3" action="" method="post" name="messageForm" autocomplete="off">
  <!-- Select Recipient -->
  <div class="form-group row">
    <label class="col-form-label lab-fixed" for="recipientSelect">Select Recipient:</label>
    <div class="input-group inp-fixed">
      <select class="form-control" id="recipientSelect" name="userIDSelected">
        <!-- List all UserID: UserNames (except Current User) --><?php
        userOptions($messageRecord["ReceiverID"], true); ?>
      </select>
      <div class="input-group-append">
        <button class="btn btn-primary" type="submit" name="selectUser">Select</button>
      </div>
    </div>
  </div><?php
  // Show Selected User Record
  if (!empty($userRecord)) : ?>
    <div class="form-group row pr-3"><?php
      include "../app/views/dashboard/userRecord.php"; ?>
    </div>
    <!-- Subject -->
    <div class="form-group row pr-3">
      <label class="col-form-label lab-fixed" for="subject">Subject:</label>
      <div class="inp-fixed">
        <input class="form-control" type="text" name="subject" id="subject" maxlength="40" placeholder="Enter Subject" value="<?= $messageRecord["Subject"] ?>" required />
      </div>
    </div>
    <!-- Body -->
    <div class="form-group row pr-3">
      <label class="col-form-label lab-fixed" for="body">Message:</label>
      <div class="inp-fixed">
        <textarea class="form-control" name="body" id="body" rows="5" placeholder="Enter Message" maxlength="500" required><?= $messageRecord["Body"] ?></textarea>
      </div>
    </div>
    <!-- Submit Button -->
    <div class="form-group row pt-3 mr-2 border-top">
      <div class="col-form-label lab-fixed">
        <button class="btn btn-primary" type="submit" name="sendMessage" id="sendMessage">Send Message</button>
      </div>
      <div class="inp-fixed"></div>
    </div><?php
  endif; ?>
</form>
