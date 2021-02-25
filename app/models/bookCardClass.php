<?php
declare(strict_types=1);
/**
 * BookCard Class
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

use RecursiveIteratorIterator;

/**
 * Displays books records in card format
 *
 * Extends the RecursiveIteratorIterator class to display each record of a
 * Book/getDisplay query in card format using HTML
 */
class BookCard extends RecursiveIteratorIterator {
  /**
   * Get just the LEAVES data from the query result
   * @param \Traversable $result  Result from Book/getDisplay query
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
      // For BookID save the current value to $_SESSION
      $_SESSION["curBookID"] = $parentValue;
      return;
    } elseif ($parentKey == "ImgFilename") {
      // For ImgFilename output image
      $srcFile = getFilePath($_SESSION["curBookID"], $parentValue);
      $returnValue = "<img class='img-thumbnail float-left mt-3 mb-3 mr-3' "
                   . "style='width:140px; height:220px;' src='"
                   . $srcFile
                   . "' alt='"
                   . $parentValue
                   . "' /><div class='mt-3'>";
    } elseif ($parentKey == "Price") {
      // For Price output Price (Currency): & value
      $returnValue = "<b>Price (" . Constants::getDefaultValues()["currency"] . "): </b>"
                   . $parentValue
                   . "<br />";
    } elseif ($parentKey == "QtyTotal") {
      // ForQtyTotal output Total Qty: & value
      $returnValue = "<b>Total Qty: </b>{$parentValue} - ";
    } elseif ($parentKey == "QtyAvail") {
      // ForQtyAvail output Available: & value
      $parentValue <= 0 ? $returnClass = "text-danger" : $returnClass = "text-success";
      $returnValue = "<b>Available: <span class='"
                   . $returnClass
                   . "'>"
                   . $parentValue
                   . "</span></b><br /></div>";
    } elseif ($parentKey == "RecordStatus") {
      // For RecordStatus skip output
      return;
    } else {
      // For all others output original key: value
      $returnValue = "<b>{$parentKey}: </b>{$parentValue}<br />";
    }
    return $returnValue;
  }

  /**
   * Initialise the column count
   */
  public function beginIteration() {
    $_SESSION["curColumn"] = 0;
  }

  /**
   * Start the current column div (and row div if required)
   */
  public function beginChildren() {
    $_SESSION["curColumn"] += 1;
    // Create new row div if this is the first column
    if ($_SESSION["curColumn"] == 1) {
      echo "<div class='row'>";
    }
    // Create column div
    echo "<div class='col-4 border rounded'>";
  }

  /**
   * Include record hyperlinks and close the column div (and row div if required)
   */
  public function endChildren() {
    // Add Hyperlink to Show Books Issued for book
    $href = "dashboard.php?p=booksIssuedByBook&id="
          . $_SESSION["curBookID"];
    echo "<a class='badge badge-info' href='{$href}'>Show Currently Issued</a>";
    // Close column div
    echo "</div>";
    unset ($_SESSION["curBookID"]);
    // If 3 columns reached close row div & reset column count
    if ($_SESSION["curColumn"] == 3) {
      echo "</div>";
      $_SESSION["curColumn"] = 0;
    }
  }

  /**
   * Close final column/row divs and unset column count
   */
  public function endIteration() {
    // If in middle of a row then pad blank columns
    if ($_SESSION["curColumn"] > 0) {
      for ($count = $_SESSION["curColumn"]; $count < 3; $count += 1) {
        echo "<div class='col-4'></div>"; // Add Blank Columns
      }
      echo "</div>"; // Close final row
    }
    unset ($_SESSION["curColumn"]);
  }
}
