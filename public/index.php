<?php
declare(strict_types=1);
/**
 * INDEX
 * 
 * Main HTML frame facilitating user registration, login and logout
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

session_start();

require_once "../app/helpers/helperFunctions.php";
require_once "../app/config/config.php";

// Get Page Details
$page = "login";
if (isset($_GET["p"])) {
  $page = cleanInput($_GET["p"], "string");
}

// Check Valid Page is entered
if (!in_array($page, Constants::getValidPages()["index"])) {
  $_SESSION["message"] = msgPrep("danger", "Error - Page Not Found.");
  $page = "login";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="description" content="Library Management System" />
  <meta name="author" content="Malarena SA" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Library MS - <?= ucfirst($page) ?></title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
  <link rel="stylesheet" href="css/extra.css" />

  <link rel="shortcut icon" href="images/favicon-96x96.png" type="image/x-icon" />
</head>

<body>
  <div class="text-center container-fluid">
    <!-- Header -->
    <div class="row justify-content-center">
      <h1>Library Management System</h1>
    </div><?php

    // Display selected page
    include "../app/controllers/index/{$page}.php"; ?>

  </div>
</body>
</html>
