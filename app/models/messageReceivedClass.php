<?php
declare(strict_types=1);
/**
 * MessageReceived Class
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

use RecursiveIteratorIterator;

/**
 * Displays messages records received for a particular user in table format
 *
 * Extends the RecursiveIteratorIterator class to display each record of a
 * Message/getListReceived query in table format using HTML
 */
class MessageReceived extends RecursiveIteratorIterator {
  /**
   * Get just the LEAVES data from the query result
   * @param \Traversable $result  Result from Message/getListReceived query
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
      // For MessageID save the current value to $_SESSION but do not output
      $_SESSION["curMessageID"] = $parentValue;
      return;
    } elseif ($parentKey == "SenderID") {
      // For SenderID save the current value to $_SESSION but do not output
      $_SESSION["curSenderID"] = $parentValue;
      return;
    } elseif ($parentKey == "Subject") {
      // For Subject save the current value to $_SESSION and DO output
      $_SESSION["curSubject"] = $parentValue;
      $returnValue = $parentValue;
    } elseif ($parentKey == "AddedTimestamp") {
      // For Date Fields modify date format
      $returnValue = date("d/m/Y H:i T", strtotime($parentValue));
    } elseif ($parentKey == "MessageStatus") {
      // For MessageStatus Codes output texts with update hyperlinks
      $href = "dashboard.php?p=myMessages&id="
            . $_SESSION["curMessageID"]
            . "&cur="
            . $parentValue
            . "&updMessageStatus";
      $returnValue = statusOutput("MessageStatus", $parentValue, $href);
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
   * Add a Reply column and close the current row
   */
  public function endChildren() {
    // Reply Column
    $href = "dashboard.php?p=sendMessage&id="
          . $_SESSION["curMessageID"]
          . "&recID="
          . $_SESSION["curSenderID"]
          . "&sub="
          . $_SESSION["curSubject"]
          . "&reply";
    echo "<td><a class='badge badge-primary' href='{$href}'>Reply</a></td>";
    echo "</tr>";
    unset ($_SESSION["curMessageID"], $_SESSION["curSenderID"], $_SESSION["curSubject"]);
  }
}
