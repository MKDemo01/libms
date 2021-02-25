<?php
declare(strict_types=1);
/**
 * BookList Class
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

use RecursiveIteratorIterator;

/**
 * Displays books records in table format
 *
 * Extends the RecursiveIteratorIterator class to display each record of a
 * Book/getList query in table format using HTML
 */
class BookList extends RecursiveIteratorIterator {
  /**
   * Get just the LEAVES data from the query result
   * @param \Traversable $result  Result from Book/getList query
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
    if ($parentKey == "BookID") {
      // For BookID save the current value to $_SESSION & skip output
      $_SESSION["curBookID"] = $parentValue;
      return;
    } elseif ($parentKey == "Title") {
      // For Title output edit hyperlink
      $href = "dashboard.php?p=bookDetails&id="
            . $_SESSION["curBookID"];
      $returnValue = "<a href='{$href}'>{$parentValue}</a>";
    } elseif ($parentKey == "AddedTimestamp") {
      // For Date/Time Fields modify date format
      $returnValue = date("d/m/Y", strtotime($parentValue));
    } elseif ($parentKey == "RecordStatus") {
      // For RecordStatus output value with update hyperlink
      $href = "dashboard.php?p=listEditBooks&id="
            . $_SESSION["curBookID"]
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
    unset ($_SESSION["curBookID"]);
  }
}
