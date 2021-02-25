<?php
declare(strict_types=1);
/**
 * UserList Class
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

use RecursiveIteratorIterator;

/**
 * Displays users records in table format
 *
 * Extends the RecursiveIteratorIterator class to display each record of a
 * User/getList query in table format using HTML
 */
class UserList extends RecursiveIteratorIterator {
  /**
   * Get just the LEAVES data from the query result
   * @param \Traversable $result  Result from User/getList query
   */
  public function __construct($result) {
    parent::__construct($result, self::LEAVES_ONLY);
  }

  /**
   * Imbed the current key=>value data into relevant HTML code
   * @return string|null  HTML element containing key=>value data or null
   */
  public function current() {
    $parentKey = parent::key();
    $parentValue = parent::current();
    $returnValue = "";
    if ($parentKey == "UserID") {
      // For UserID save the current value to $_SESSION & skip output
      $_SESSION["curUserID"] = $parentValue;
      return;
    } elseif ($parentKey == "Username") {
      // For Username output value with edit hyperlink
      $href = "dashboard.php?p=userDetails&id="
            . $_SESSION["curUserID"];
      $returnValue = "<a href='{$href}'>{$parentValue}</a>";
    } elseif ($parentKey == "IsAdmin") {
      // For IsAdmin output with value update hyperlink
      $href = "dashboard.php?p=listEditUsers&id="
            . $_SESSION["curUserID"]
            . "&cur="
            . $parentValue
            . "&updIsAdmin";
      $returnValue = statusOutput("IsAdmin", $parentValue, $href);
    } elseif ($parentKey == "UserStatus") {
      // For UserStatus output value with update hyperlink
      $href = "dashboard.php?p=listEditUsers&id="
            . $_SESSION["curUserID"]
            . "&cur="
            . $parentValue
            . "&updUserStatus";
      $returnValue = statusOutput("UserStatus", $parentValue, $href);
    } elseif ($parentKey == "RecordStatus") {
      // For RecordStatus output value with update hyperlink
      $href = "dashboard.php?p=listEditUsers&id="
            . $_SESSION["curUserID"]
            . "&cur="
            . $parentValue
            . "&updRecordStatus";
      $returnValue = statusOutput("RecordStatus", $parentValue, $href);
    } else {
      // For all others output original value
      $returnValue = $parentValue;
    }
    return "<td>{$returnValue}</td>";
  }

  /**
   * Start the current row
   */
  public function beginChildren() {
    echo "<tr>";
  }

  /**
   * Close the current row
   */
  public function endChildren() {
    echo "</tr>";
    unset ($_SESSION["curUserID"]);
  }
}
