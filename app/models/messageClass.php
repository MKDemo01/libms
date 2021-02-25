<?php
declare(strict_types=1);
/**
 * Message Class
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

use PDO, PDOException;

/**
 * Access the messages table and process SQL queries
 */
class Message {
  /**
   * PDO database connection object
   */
  private $conn;

  /**
   * Create the database connection object
   */
  public function __construct() {
    try {
      $connDetails = parse_ini_file("../inifiles/mariaDBCon.ini");
      $connDetails["database"] = Constants::getDefaultValues()["database"];
      $connString = "mysql:host=" . $connDetails["servername"] . ";dbname=" . $connDetails["database"];
      $this->conn = new PDO($connString, $connDetails["username"], $connDetails["password"]);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $err) {
      $_SESSION["message"] = msgPrep("danger", "Error - Message/DB Connection Failed: {$err->getMessage()}");
    }
  }

  /**
   * Add message Record
   * @param int $senderID    Sender User ID
   * @param int $receiverID  Receiver User ID
   * @param string $subject  Message Subject
   * @param string $body     Message Body
   * @return int|null        Message ID of added record or null
   */
  public function add($senderID, $receiverID, $subject, $body) {
    try {
      $sql = "INSERT INTO `messages` (`SenderID`, `ReceiverID`, `Subject`, `Body`)
              VALUES ({$senderID}, {$receiverID}, '{$subject}', '{$body}')";
    $this->conn->exec($sql);
    $newMsgID = $this->conn->lastInsertId();
    // Do Not set Session Message here as it overwrites when new user added
    return $newMsgID;
    } catch (PDOException $err) {
      $_SESSION["message"] = msgPrep("danger", "Error - Message/add Failed: {$err->getMessage()}");
    }
  }

  /**
   * Count ACTIVE Unread Messages from ACTIVE senders for User ID using
   * messages_view
   * @param int $userID  User ID
   * @return int|null    Count of ACTIVE Unread Messages from ACTIVE senders
   *                     for $userID or null
   */
  public function countUnreadByUserID($userID) {
    try {
      $sql = "SELECT COUNT(*)
              FROM `messages_view`
              WHERE `ReceiverID` = {$userID} AND `MessageStatus` = 0 AND
                    `RecordStatus` = 1 AND `SenderStatus` = 1";
      $stmt = $this->conn->query($sql, PDO::FETCH_ASSOC);
      $result = $stmt->fetchColumn();
      return $result;
    } catch (PDOException $err) {
      $_SESSION["message"] = msgPrep("danger", "Error - Message/countUnreadByUserID Failed: {$err->getMessage()}");
    }
  }

  /**
   * Retrieve list of messages RECEIVED BY Receiver ID (optionally by RecordStatus
   * and/or SenderStatus) from messages_view
   * @param int $receiverID    Receiver User ID
   * @param int $recordStatus  Record Status (Optional)
   * @param int $senderStatus  Sender User Status (Optional)
   * @return array|null        Returns all messages received by Receiver ID
   *                           (AddedTimeStamp Descending order) or null
   */
  public function getListReceived($receiverID, $recordStatus = null, $senderStatus = null) {
    try {
      // Build WHERE clause
      $whereClause = "WHERE `ReceiverID` = {$receiverID} ";
      if (!empty($recordStatus)) {
        $whereClause .= "AND `RecordStatus` = {$recordStatus} ";
      }
      if (!empty($senderStatus)) {
        $whereClause .= "AND `SenderStatus` = {$senderStatus} ";
      }
      // Build SQL & Execute
      $sql = "SELECT `MessageID`, `AddedTimestamp`, `SenderID`, `SenderName`,
                     `Subject`, `Body`, `MessageStatus`
              FROM `messages_view`
              {$whereClause}
              ORDER BY `AddedTimestamp` DESC";
      $stmt = $this->conn->query($sql, PDO::FETCH_ASSOC);
      $result = $stmt->fetchAll();
      return $result;
    } catch (PDOException $err) {
      $_SESSION["message"] = msgPrep("danger", "Error - Message/getListReceived Failed: {$err->getMessage()}");
    }
  }

  /**
   * Retrieve list of messages SENT BY Sender ID (optionally by RecordStatus
   * and/or ReceiverStatus) from messages_view
   * @param int $senderID        Sender User ID
   * @param int $recordStatus    Record Status (Optional)
   * @param int $receiverStatus  Receiver User Status (Optional)
   * @return array|null          Returns all messages sent by Sender ID
   *                             (AddedTimeStamp Descending order) or null
   */
  public function getListSent($senderID, $recordStatus = null, $receiverStatus = null) {
    try {
      // Build WHERE clause
      $whereClause = "WHERE `SenderID` = {$senderID} ";
      if (!empty($recordStatus)) {
        $whereClause .= "AND `RecordStatus` = {$recordStatus} ";
      }
      if (!empty($receiverStatus)) {
        $whereClause .= "AND `ReceiverStatus` = {$receiverStatus} ";
      }
      // Build SQL & Execute
      $sql = "SELECT `MessageID`, `AddedTimestamp`, `ReceiverName`, `Subject`, `Body`,
                     `MessageStatus`, `RecordStatus`
              FROM `messages_view`
              {$whereClause}
              ORDER BY `AddedTimestamp` DESC";
      $stmt = $this->conn->query($sql, PDO::FETCH_ASSOC);
      $result = $stmt->fetchAll();
      return $result;
    } catch (PDOException $err) {
      $_SESSION["message"] = msgPrep("danger", "Error - Message/getListSent Failed: {$err->getMessage()}");
    }
  }

  /**
   * Update the relevant Status Code of a messages record
   * @param string $field   Field in messages table to be updated
   * @param int $messageID  Message ID of record to update
   * @param int $newStatus  New Status code for field
   * @return int|null       Number of records updated (=1) or null
   */
  public function updateStatus($field, $messageID, $newStatus) {
    try {
      $sql = "UPDATE `messages`
              SET `{$field}` = $newStatus
              WHERE `MessageID` = {$messageID}";
      $result = $this->conn->exec($sql);
      return $result;
    } catch (PDOException $err) {
      $_SESSION["message"] = msgPrep("danger", "Error - Message/updateStatus Failed: {$err->getMessage()}");
    }
  }
}
