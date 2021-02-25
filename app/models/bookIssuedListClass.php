<?php
declare(strict_types=1);
/**
 * BookIssuedList Class
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

use RecursiveIteratorIterator;

/**
 * Displays books_issued records in table format
 *
 * Extends the RecursiveIteratorIterator class to display each record of a
 * BookIssued/getList query in table format using HTML
 */
class BookIssuedList extends RecursiveIteratorIterator {
  /**
   * Get just the LEAVES data from the query result
   * @param \Traversable $result  Result from BookIssued/getList query
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
    if ($parentKey == "IssuedID") {
      // For IssuedID save the current value to $_SESSION & skip output
      $_SESSION["curIssuedID"] = $parentValue;
      return;
    } elseif ($parentKey == "BookID") {
      // For BookID save the current value to $_SESSION & skip output
      $_SESSION["curBookID"] = $parentValue;
      return;
    } elseif ($parentKey == "UserID") {
      // For UserID skip output
      return;
    } elseif (($parentKey == "IssuedDate" || $parentKey == "ReturnDueDate") && (!empty($parentValue))) {
      // For Non-Empty Date Fields modify date format
      $returnValue = date("d/m/Y", strtotime($parentValue));
    } elseif ($parentKey == "ReturnActualDate") {
      // For ReturnActualDate, if NULL provide Return hyperlink, or return ReturnDate
      if (empty($parentValue)){
        $_SESSION["curOutstanding"] = true;
        $href = "dashboard.php?p=listReturnIssuedBooks&id="
              . $_SESSION["curIssuedID"]
              . "&bookID="
              . $_SESSION["curBookID"]
              . "&updReturn";
        $returnValue = "<a class='badge badge-primary' href='{$href}'>Return</a>";
      } else {
        $_SESSION["curOutstanding"] = false;
        $returnValue = date("d/m/Y", strtotime($parentValue));
      }
    } elseif ($parentKey == "RecordStatus") {
      // Update the counts based on the Record Status
      if ($parentValue == 0) {
        // Current record is Inactive
        $_SESSION["countInactive"] += 1;
      } else {
        // Current record is Active
        $_SESSION["countIssued"] += 1;
        if ($_SESSION["curOutstanding"] == true) {
          // Current record is also outstanding
          $_SESSION["countOutstanding"] += 1;
        }
      }
      // For RecordStatus output value with update hyperlink
      $href = "dashboard.php?p=listReturnIssuedBooks&id="
            . $_SESSION["curIssuedID"]
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
   * Initialise the count of issued, outstanding & inactive books
   */
  public function beginIteration() {
    $_SESSION["countIssued"] = 0;
    $_SESSION["countOutstanding"] = 0;
    $_SESSION["countInactive"] = 0;
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
    unset($_SESSION["curIssuedID"], $_SESSION["curBookID"], $_SESSION["curOutstanding"]);
  }

  /**
   * Add final summary row of total issued, outstanding and inactive and
   * unset the counts
   */
  public function endIteration() {
    echo "<tr class='table-info'><td colspan='6'>"
       . "<b>Total issued: {$_SESSION["countIssued"]} "
       . "(Outstanding: {$_SESSION["countOutstanding"]}) "
       . "+ Inactive: {$_SESSION["countInactive"]}</b>"
       . "</td></tr>";
    unset($_SESSION["countIssued"], $_SESSION["countOutstanding"], $_SESSION["countInactive"]);
  }
}
