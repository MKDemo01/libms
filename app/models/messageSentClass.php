<?php
declare(strict_types=1);
/**
 * MessageSent Class
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

use RecursiveIteratorIterator;

/**
 * Displays messages records sent for a particular user in table format
 *
 * Extends the RecursiveIteratorIterator class to display each record of a
 * Message/getListSent query in table format using HTML
 */
class MessageSent extends RecursiveIteratorIterator {
  /**
   * Get just the LEAVES data from the query result
   * @param \Traversable $result  Result from Message/getListSent query
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
    if ($parentKey == "MessageID") {
      // For MessageID save the current value to $_SESSION but don't output
      $_SESSION["curMessageID"] = $parentValue;
      return;
    } elseif ($parentKey == "AddedTimestamp") {
      // For Date Fields modify date format
      $returnValue = date("d/m/Y H:i T", strtotime($parentValue));
    } elseif ($parentKey == "MessageStatus") {
      // For MessageStatus Codes output texts with NO update hyperlinks
      $returnValue = statusOutput("MessageStatus", $parentValue, null);
    } elseif ($parentKey == "RecordStatus") {
      // For RecordStatus Codes output texts with update hyperlinks
      $href = "dashboard.php?p=myMessages&id="
            . $_SESSION["curMessageID"]
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
    unset ($_SESSION["curMessageID"]);
  }
}
