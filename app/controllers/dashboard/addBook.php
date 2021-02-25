<?php
declare(strict_types=1);
/**
 * DASHBOARD/addBook controller
 *
 * Prepare and show a blank book form for entry. If the form is submitted,
 * validate the data, add a new books record and upload the optional image file.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

require_once "../app/models/bookClass.php";
$book = new Book();
require_once "../app/models/uploadImgClass.php";
$uploadImg = new UploadImg();

// Add Book Record if Add POSTed
if (isset($_POST["addBook"])) {
  // If Image File included - Perform initial checks on file
  $initialChecks = false;
  if ($_FILES["imgFilename"]["error"] != 4) {  // File was Uploaded
    $initialChecks = $uploadImg->initialChecks();
  }

  // Only continue adding record if no file uploaded or initial checks passed
  if ($_FILES["imgFilename"]["error"] == 4 || $initialChecks == true) {
    $title = cleanInput($_POST["title"], "string");
    $author = cleanInput($_POST["author"], "string");
    $publisher = cleanInput($_POST["publisher"], "string");
    $ISBN = cleanInput($_POST["ISBN"], "string");
    $price = cleanInput($_POST["price"], "float");
    $quantity = cleanInput($_POST["quantity"], "int");
    $imgFilename = null;
    if ($_FILES["imgFilename"]["error"] == 0) {
      $imgFilename = basename($_FILES["imgFilename"]["name"]);
    }

    // Create Database Entry
    $newBookID = $book->add($title, $author, $publisher, $ISBN, $price, $quantity, $imgFilename);

    // Process File Upload if new book record created & image included
    if ($newBookID) {
      $_POST = [];
      if ($_FILES["imgFilename"]["error"] == 0) {  // Image File included
        // Create dir & upload
        $newUpload = $uploadImg->addBookImg($newBookID, $imgFilename);
      } else {  // No Image File Included
        $updatedMessage = $_SESSION["message"] . " No Image to upload.";
        $_SESSION["message"] = msgPrep("success", $updatedMessage);
      }
      $_FILES = [];
    }
  }
}

// Initialise Book Record
$bookRecord = [
  "Title" => postValue("title"),
  "Author" => postValue("author"),
  "Publisher" => postValue("publisher"),
  "ISBN" => postValue("ISBN"),
  "Price" => postValue("price"),
  "Quantity" => postValue("quantity"),
];

// Prep Book Form Data
$formData = [
  "formUsage" => "Add",
  "formTitle" => "Add Book",
  "submitName" => "addBook",
  "submitText" => "Add Book",
];

// Show Book Form
include "../app/views/dashboard/bookForm.php";
