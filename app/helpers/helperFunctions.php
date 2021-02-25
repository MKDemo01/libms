<?php
declare(strict_types=1);
/**
 * Global Helper Functions
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

use RecursiveArrayIterator;

/**
 * Clean all manual data entry
 * @param mixed $input  Original Input
 * @param string $type  Input Type (string, int, float, email, password)
 * @return mixed        Cleaned Input
 */
function cleanInput($input, $type) {
  if ($type == "string") {
    $output = htmlspecialchars($input, ENT_QUOTES);
    $output = trim($output);
    $output = str_replace("\n", " ",$output);  // For LF replace with space as messages shown as single lines
    $output = stripslashes($output);
    $output = filter_var($output, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW);
  } elseif ($type == "int") {
    $output = filter_var($input, FILTER_SANITIZE_NUMBER_INT);
  } elseif ($type == "float") {
    $output = filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  } elseif ($type = "email") {
    $output = htmlspecialchars($input);
    $output = trim($output);
    $output = filter_var($output, FILTER_SANITIZE_EMAIL);
  }
  return $output;
}

/**
 * Prepare system messages for display
 * @param string $type  Type of message (success / warning / danger)
 * @param string $msg   Message Content
 * @return string       Prepared Message
 */
function msgPrep($type, $msg) {
  if ($type == "success") {
    $prepdMsg = "<div class='alert alert-success'>{$msg}</div>";
  } elseif ($type == "warning") {
    $prepdMsg = "<div class='alert alert-warning'>{$msg}</div>";
  } elseif ($type == "danger") {
    $prepdMsg = "<div class='alert alert-danger'>{$msg}</div>";
  }
  return $prepdMsg;
}

/**
 * Display Session["Message"] and then clear it
 * @return bool  Returns true on completion
 */
function msgShow() {
  if (!empty($_SESSION["message"])) {
    echo $_SESSION["message"];
    unset($_SESSION["message"]);
  }
  return true;
}

/**
 * Clean and fix a Search String to be MariaDB compliant
 * @param string $searchString  Original Input Search String
 * @return string               Cleaned and fixed MariaDB-Compliant Search String
 */
function fixSearch($searchString) {
  $fixed = htmlspecialchars($searchString, ENT_QUOTES);
  $fixed = trim($fixed);
  $fixed = str_replace("?", "_", $fixed);  // Fix MariaDB one char wildcard
  $fixed = str_replace("*", "%", $fixed);  // Fix MariaDB multi char wildcard
  return $fixed;
}

/**
 * Returns the value in a $_POST key field IF it is set
 * @param string $key     Name of $_POST["key"] to return
 * @param mixed $default  Default value to return if "key" NOT set (optional)
 * @return mixed          Returns $_POST["key"] value or default/null
 */
function postValue($key, $default = null) {
  if (isset($_POST["$key"])) {
    return $_POST["$key"];
  } else {
    return $default;
  }
}

/**
 * Returns the full file path for specified image or default path if no image found
 * @param int $bookID          Book ID for image
 * @param string $imgFilename  Product Image Filename
 * @return string              Full filepath to specified image or default
 *
 */
function getFilePath($bookID, $imgFilename) {
  if (empty($imgFilename)) {
    $filePath = Constants::getDefaultValues()["noImgUploaded"];
  } else {
    $filePath = Constants::getDefaultValues()["booksImgPath"] . $bookID . "/" . $imgFilename;
  }
  return $filePath;
}

/**
 * Returns the HTML output relevant to the given status code
 * @param string $type  Status Type (from Constants Class)
 * @param int $status   Status Code (from Constants Class)
 * @param string $link  HREF Link (optional)
 * @return string       Returns the HTML output for the relevant status code
 */
function statusOutput($type, $status, $link = null) {
  $text = Constants::getStatusCodes()[$type][$status]["text"];
  $badge = Constants::getStatusCodes()[$type][$status]["badge"];
  $statusOutput = "<a class='badge badge-{$badge}' href='{$link}'>{$text}</a>";
  return $statusOutput;
}

/**
 * Returns the next status code or reverts to zero
 * @param string $type  Status Type (from Constants Class)
 * @param int $current  Current Status Code
 * @return int          Next Status Code or 0
 */
function statusCycle($type, $current) {
  $max = count(Constants::getStatusCodes()[$type]) - 1;
  $new = ($current == $max) ? 0 : $current + 1;
  return $new;
}

/**
 * Outputs all Approved/Active Users as HTML options, with option to exclude
 * current user
 * @param int $selID   UserID that is marked as 'selected' (optional)
 * @param bool $exCur  True=Exclude current user (optional)
 * @return bool        Returns true on completion
 */
function userOptions($selID = null, $exCur = false) {
  include_once "../app/models/userClass.php";
  $user = new User();
  foreach (new RecursiveArrayIterator($user->getUserIDs()) as $value) {
    if ($exCur == true && $value["UserID"] == $_SESSION["userID"]) {
      continue;
    } elseif ($value["UserID"] == $selID) {
      echo "<option value='{$value["UserID"]}' selected>{$value["UserID"]}: {$value["Username"]}</option>";
    } else {
      echo "<option value='{$value["UserID"]}'>{$value["UserID"]}: {$value["Username"]}</option>";
    }
  }
  return true;
}

/**
 * Outputs all Active Books as HTML options
 * @param int $selID  BookID that is marked as 'selected' (optional)
 * @return bool       Returns true on completion
 */
function bookOptions($selID = null) {
  include_once "../app/models/bookClass.php";
  $book = new Book();
  foreach (new RecursiveArrayIterator($book->getBookIDs()) as $value) {
    if ($value["BookID"] == $selID) {
      echo "<option value='{$value["BookID"]}' selected>{$value["BookID"]}: {$value["Title"]}</option>";
    } else {
      echo "<option value='{$value["BookID"]}'>{$value["BookID"]}: {$value["Title"]}</option>";
    }
  }
  return true;
}

/**
 * Get count of unread received messages for current user, returned in an HTML link
 * @return string  HTML link containing count of unread messages
 */
function getMsgsUnreadLink() {
  include_once "../app/models/messageClass.php";
  $message = new Message();
  $unreadClass = "badge badge-info";
  $unreadCount = $message->countUnreadByUserID($_SESSION["userID"]);
  // Update Badge if no unread
  if ($unreadCount == 0) {
    $unreadClass = "badge badge-light";
  }
  // Create link
  $msgsUnreadLink = "<a class='{$unreadClass}' href='dashboard.php?p=myMessages'><span data-feather='mail'></span> {$unreadCount}</a>";
  return $msgsUnreadLink;
}