<?php
declare(strict_types=1);
/**
 * Global Configuration Details defined via Constants Class
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

/**
 * Defines the Global Constants
 */
class Constants {
  /**
   * Default values for application
   */
  private static $defaultValues = [
    "environment" => "production",  // Set to "development" or "production"
    "database" => "libraryms",  // Database containing application tables
    "maxUploadSize" => 200000,  // Maximum PHP upload file size in bytes - see phpInfo
    "booksImgPath" => "uploads/imgBooks/",  // Path to books images
    "noImgUploaded" => "images/noImage.jpg" ,  // Default Image if no image file uploaded
    "currency" => "CHF",  // Default Currency
    "returnDuration" => 14,  // Issued Book Return Duration
    "userAdminUserID" => 1,  // UserID for User Management Administrator
  ];

  /**
   * Valid pages used within INDEX and DASHBOARD frames
   */
  private static $validPages = [
    "index" => [
      "login",
      "logout",
      "register",
    ],
    "dashboard" => [
      "home",
      "myMessages",
      "displayBooks",
      "booksIssuedByBook",
      "myIssuedBooks",
      "sendMessage",
      "myProfile",
    ],
    "dashboard_admin" => [
      "issueBook",
      "listReturnIssuedBooks",
      "addBook",
      "listEditBooks",
      "bookDetails",
      "listEditUsers",
      "userDetails",
    ],
  ];

  /**
   * Text and Badge Class settings for each Status Field / Code combination
   */
  private static $statusCodes= [
    "RecordStatus" => [  // All Tables / RecordStatus Field
      0 => [
        "text" => "Inactive",
        "badge" => "danger",
      ],
      1 => [
        "text" => "Active",
        "badge" => "success",
      ],
    ],
    "IsAdmin" => [  // users Table / IsAdmin Field
      0 => [
        "text" => "No",
        "badge" => "secondary",
      ],
      1 => [
        "text" => "Yes",
        "badge" => "primary",
      ],
    ],
    "UserStatus" => [  // users Table / UserStatus Field
      0 => [
        "text" => "UnApproved",
        "badge" => "danger",
      ],
      1 => [
        "text" => "Approved",
        "badge" => "success",
      ],
    ],
    "MessageStatus" => [  // messages Table / MessageStatus Field
      0 => [
        "text" => "UnRead",
        "badge" => "info",
      ],
      1 => [
        "text" => "Read",
        "badge" => "light",
      ],
    ],
  ];

  /**
   * Retrieve default values
   * @return array  Default Values
   */
  public static function getDefaultValues() {
    return self::$defaultValues;
  }

  /**
   * Retrieve valid pages
   * @return array  Valid Pages
   */
  public static function getValidPages() {
    return self::$validPages;
  }

  /**
   * Retrieve status code information
   * @return array  Valid status code data
   */
  public static function getStatusCodes() {
    return self::$statusCodes;
  }

}
