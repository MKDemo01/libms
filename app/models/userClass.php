<?php
declare(strict_types=1);
/**
 * User Class
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

use PDO;
use PDOException;

/**
 * Access the users table and process SQL queries
 */
class User {
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
      $_SESSION["message"] = msgPrep("danger", "Error - User/DB Connection Failed: {$err->getMessage()}");
    }
  }

  /**
   * Check if a Username already exists in the table
   * @param string $username  Username
   * @return int|null         User ID of record with selected Name or null
   */
  public function exists($username) {
    try {
      $sql = "SELECT `UserID`
              FROM `users`
              WHERE `Username` = '{$username}'";
      $stmt = $this->conn->query($sql, PDO::FETCH_ASSOC);
      $userID = $stmt->fetchColumn();
      return $userID;
    } catch (PDOException $err) {
      $_SESSION["message"] = msgPrep("danger", "Error - User/exists Failed: {$err->getMessage()}");
    }
  }

  /**
   * Register (Add) a new user
   * @param string $username   Username
   * @param string $password   User Password
   * @param string $firstName  User First Name
   * @param string $lastName   User Last Name
   * @param string $email      User Email Address
   * @param string $contactNo  User Contact Number
   * @param int $isAdmin       User is Admin (Optional)
   * @param int $userStatus    User Status (Optional)
   * @param int $recordStatus  Record Status (Optional)
   * @return int|null          User ID of added record or null
   */
  public function register($username, $password, $firstName, $lastName, $email, $contactNo, $isAdmin = 0, $userStatus = 0, $recordStatus = 1) {
    try {
      // Check Username does not already exist
      $exists = $this->exists($username);
      if (!empty($exists)) {  // Username is not unique
        $_SESSION["message"] = msgPrep("danger", "Error - Username '{$username}' is already in use! Please try again.");
        return null;
      } else {  // Insert User Record
        $passwordHash = password_hash($password, PASSWORD_ARGON2ID);
        $sql = "INSERT INTO `users` (`Username`, `Password`, `FirstName`, `LastName`,
                            `Email`, `ContactNo`, `IsAdmin`, `UserStatus`, `RecordStatus`)
                VALUES ('{$username}', '{$passwordHash}', '{$firstName}', '{$lastName}',
                        '{$email}', '{$contactNo}', {$isAdmin}, {$userStatus},
                         {$recordStatus})";
        $this->conn->exec($sql);
        $newUserID = $this->conn->lastInsertId();
        $_SESSION["message"] = msgPrep("success", "Registration of '{$username}' was successful.<br />They will receive an email once their account is approved.");
        return $newUserID;
      }
    } catch (PDOException $err) {
      $_SESSION["message"] = msgPrep("danger", "Error - User/register Failed: {$err->getMessage()}");
    }
  }

  /**
   * Check username & password match table & if OK set Session variables
   * @param string $username  Username
   * @param string $password  User Password
   * @return bool|null        True or False if user details validated or null
   */
  public function login($username, $password) {
    try {
      // Check User exists
      $exists = $this->exists($username);
      if (empty($exists)) {  // User does not exist
        $_SESSION["message"] = msgPrep("danger", "Incorrect Username or Password entered!");
        return false;
      } else {
        // Confirm Password
        $sql = "SELECT `UserID`, `Username`, `Password`, `IsAdmin`, `UserStatus`,
                       `RecordStatus`
                FROM `users`
                WHERE `Username` = '{$username}'";
        $stmt = $this->conn->query($sql, PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        $passwordStatus = password_verify($password, $result["Password"]);
        $userID = $result["UserID"];
        $usernameDB = $result["Username"];
        $userIsAdmin = $result["IsAdmin"];
        $userStatus = $result["UserStatus"];
        $recordStatus = $result["RecordStatus"];
        $result = null;
        if ($passwordStatus != true) {  // Password invalid
          $_SESSION["message"] = msgPrep("danger", "Incorrect Username or Password entered!");
          return false;
        }
        if ($recordStatus != 1) {  // User record is inactive
          $_SESSION["message"] = msgPrep("danger", "Error - User Account Inactive!");
          return false;
        }
        if ($userStatus != 1) {  // User is unapproved
          $_SESSION["message"] = msgPrep("warning", "Sorry - User has not yet been approved!");
          return false;
        }
        // User Validated, Active and Approved, so set Session Variables
        $_SESSION["userLogin"] = true;
        $_SESSION["userIsAdmin"] = $userIsAdmin;
        $_SESSION["userID"] = $userID;
        $_SESSION["username"] = $usernameDB;  // Use username from DB
        return true;
      }
    } catch (PDOException $err) {
      $_SESSION["message"] = msgPrep("danger", "Error - User/Login Failed: {$err->getMessage()}");
    }
  }

  /**
   * Logout user
   * @return bool|null  True if function success or null
   */
  public function logout() {
    try {
      unset($_SESSION["userLogin"], $_SESSION["userIsAdmin"], $_SESSION["userID"], $_SESSION["username"]);
      $_SESSION["message"] = msgPrep("success", "You are successfully logged out. Thanks for using the LibraryMS.");
      return true;
    } catch (PDOException $err) {
      $_SESSION["message"] = msgPrep("danger", "Error - User/Logout Failed: {$err->getMessage()}");
    }
  }

  /**
   * Retrieve list of ALL user records (optionally based on Username and
   * optionally excluding current user)
   * @param string $username  Username (Optional)
   * @param bool $exCur       True=Exclude current user (Optional)
   * @return array|null       Returns all/selected user records (Username order)
   *                          or null
   */
  public function getList($username = null, $exCur = false) {
    try {
      // Build WHERE clause
      $whereClause = null;
      if (!empty($username)) {
        $whereClause = "WHERE `Username` LIKE '%{$username}%' ";
      }
      if (!empty($exCur)) {
        // Insert correct pre-fix
        if (empty($whereClause)) {
          $whereClause = "WHERE ";
        } else {
          $whereClause .= "AND ";
        }
        $whereClause .= "`UserID` != {$_SESSION["userID"]} ";
      }
      // Build SQL & Execute
      $sql = "SELECT `UserID`, `Username`, `FirstName`, `LastName`, `Email`, `ContactNo`,
                     `IsAdmin`, `UserStatus`, `RecordStatus`
              FROM `users`
              {$whereClause}
              ORDER BY `Username`";
      $stmt = $this->conn->query($sql, PDO::FETCH_ASSOC);
      $result = $stmt->fetchAll();
      return $result;
    } catch (PDOException $err) {
      $_SESSION["message"] = msgPrep("danger", "Error - User/getList Failed: {$err->getMessage()}");
    }
  }

  /**
   * Retrieve all Approved & Active User IDs with Username
   * @return array|null  Returns UserID and Username of all Approved & Active
   * records or null
   */
  public function getUserIDs() {
    try {
      $sql = "SELECT `UserID`, `Username`
              FROM `users`
              WHERE `UserStatus` = 1 AND `RecordStatus` = 1
              ORDER BY `Username`";
      $stmt = $this->conn->query($sql, PDO::FETCH_ASSOC);
      $result = $stmt->fetchAll();
      return $result;
    } catch (PDOException $err) {
      $_SESSION["message"] = msgPrep("danger", "Error - User/getUserIDs Failed: {$err->getMessage()}");
    }
  }

  /**
   * Retrieve user record based on ID
   * @param int $userID  User ID of required record
   * @return array|null  Returns user record for $userID or null
   */
  public function getRecord($userID) {
    try {
      $sql = "SELECT `UserID`, `Username`, `FirstName`, `LastName`, `Email`, `ContactNo`
              FROM `users`
              WHERE `UserID` = '{$userID}'";
      $stmt = $this->conn->query($sql, PDO::FETCH_ASSOC);
      $result = $stmt->fetch();
      return $result;
    } catch (PDOException $err) {
      if (isset($_SESSION["message"])) {
        $_SESSION["message"] .= msgPrep("danger", "Error - User/getRecord Failed: {$err->getMessage()}");
      } else {
        $_SESSION["message"] = msgPrep("danger", "Error - User/getRecord Failed: {$err->getMessage()}");
      }
    }
  }

  /**
   * Update an existing user record
   * @param int $userID        User ID of record to update
   * @param int $username      Username
   * @param string $firstName  User First Name
   * @param string $lastName   User Last Name
   * @param string $email      User Email Address
   * @param string $contactNo  User Contact Number
   * @return int|null          Number of records updated (=1) or null
   */
  public function updateRecord($userID, $username, $firstName, $lastName, $email, $contactNo) {
    try {
      // Check update username does not already exist (other than in current record)
      $exists = $this->exists($username);
      if (!empty($exists) && $exists != $userID) {  // Username is NOT unique
        $_SESSION["message"] = msgPrep("danger", "Error - Username '{$username}' is already in use! Please try again.");
        return null;
      } else {  // Update User Record
        $sql = "UPDATE `users`
                SET `Username` = '{$username}',
                    `FirstName` = '{$firstName}',
                    `LastName` = '{$lastName}',
                    `Email` = '{$email}',
                    `ContactNo` = '{$contactNo}'
                WHERE `UserID` = {$userID}";
        $result = $this->conn->exec($sql);
        if ($result == 0) {  // No Changes made
          $_SESSION["message"] = msgPrep("warning", "Warning - No changes made to User ID '{$userID}'.");
        } elseif ($result == 1) {  // Only 1 record should have been updated
          $_SESSION["message"] = msgPrep("success", "Update of User ID: '{$userID}' was successful.");
          if ($userID == $_SESSION["userID"]) {  // User has updated their own record
            $_SESSION["username"] = $username;
          }
        } else {
          throw new PDOException("Update unsuccessful or multiple records updated.");
        }
        return $result;
      }
    } catch (PDOException $err) {
      $_SESSION["message"] = msgPrep("danger", "Error - User/updateRecord Failed: {$err->getMessage()}");
    }
  }

  /**
   * Update the Password of a user record
   * @param int $userID               User ID of record to update
   * @param string $newPassword       Users New Password
   * @param string $existingPassword  Users Existing Password (Optional for Admin Reset)
   * @param bool $adminReset          Admin Reset of Password (Optional)
   * @return int|null                 Number of records updated (=1) or null
   */
  public function updatePassword($userID, $newPassword, $existingPassword = null, $adminReset = false) {
    try {
      if ($adminReset == false || ($adminReset == true && $_SESSION["userIsAdmin"] != 1)) {
        // Verify existing password if not adminReset or not userIsAdmin
        $sqlChk = "SELECT `UserID`, `Password`
                   FROM `users`
                   WHERE `UserID` = {$userID}";
        $stmtChk = $this->conn->query($sqlChk, PDO::FETCH_ASSOC);
        $resultChk = $stmtChk->fetch();
        $passwordStatus = password_verify($existingPassword, $resultChk["Password"]);
        $resultChk = null;
        if ($passwordStatus != true) {  // Incorrect Existing Password Entered
          $_SESSION["message"] = msgPrep("danger", "Error - Incorrect Existing Password!");
          return null;
        }
      }
      // Update Password with $newPassword
      $newPasswordHash = password_hash($newPassword, PASSWORD_ARGON2ID);
      $sql = "UPDATE `users`
              SET `Password` = '{$newPasswordHash}'
              WHERE `UserID` = {$userID}";
      $result = $this->conn->exec($sql);
      if ($result == 0) {  // No Changes made
        $_SESSION["message"] = msgPrep("warning", "Warning - No changes made to User ID '{$userID}'.");
      } elseif ($result == 1) {  // Only 1 record should have been updated
        $_SESSION["message"] = msgPrep("success", "Password Successfully Updated.");
      } else {
        throw new PDOException("Update unsuccessful or multiple records updated.");
      }
      return $result;
    } catch (PDOException $err) {
      $_SESSION["message"] = msgPrep("danger", "Error - User/updatePassword Failed: {$err->getMessage()}");
    }
  }

  /**
   * Update the relevant Status Code of a users record
   * @param string $field   Field in users table to be updated
   * @param int $userID     User ID of record to update
   * @param int $newStatus  New Status code for field
   * @return int|null       Number of records updated (=1) or null
   */
  public function updateStatus($field, $userID, $newStatus) {
    try {
      $sql = "UPDATE `users`
              SET `{$field}` = '$newStatus'
              WHERE `UserID` = '{$userID}'";
      $result = $this->conn->exec($sql);
      return $result;
    } catch (PDOException $err) {
      $_SESSION["message"] = msgPrep("danger", "Error - User/updateStatus Failed: {$err->getMessage()}");
    }
  }
}
