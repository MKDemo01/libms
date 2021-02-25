<?php
declare(strict_types=1);
/**
 * BookIssuedByBook Class
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

use RecursiveIteratorIterator;

/**
 * Displays books_issued records for a particular book in table format
 *
 * Extends the RecursiveIteratorIterator class to display each record of a
 * BookIssued/getListByBook query in table format using HTML
 */
class BookIssuedByBook extends RecursiveIteratorIterator {
  /**
   * Get just the LEAVES data from the query result
   * @param \Traversable $result  Result from BookIssued/getListByBook query
   */
  public function __construct($result) {
    parent::__construct($result, self::LEAVES_ONLY);
  }

  /**
   * Imbed the current key=>value data into relevant HTML code
   * @return string  HTML element containing key=>value data
   */
  public function current() {
    $parentKey = parent::key();
    $parentValue = parent::current();
    $returnValue = "";
    if (($parentKey == "IssuedDate" || $parentKey == "ReturnDueDate") && (!empty($parentValue))) {
      // For Non-Empty Date Fields modify date format
      $returnValue = date("d/m/Y", strtotime($parentValue));
    } else {
      // For all others output original value
      $returnValue = $parentValue;
    }
    return "<td>{$returnValue}</td>";
  }

  /**
   * Initialise the count of issued books
   */
  public function beginIteration() {
    $_SESSION["countIssued"] = 0;
  }

  /**
   * Start the current row
   */
  public function beginChildren() {
    $_SESSION["countIssued"] += 1;
    echo "<tr>";
  }

  /**
   * Close the current row
   */
  public function endChildren() {
    echo "</tr>";
  }

  /**
   * Add final summary row of total issued and unset the count
   */
  public function endIteration() {
    echo "<tr class='table-info'><td colspan='3'>"
       . "<b>Total currently issued: {$_SESSION["countIssued"]}</b>"
       . "</td></tr>";
    unset($_SESSION["countIssued"]);
  }
}
