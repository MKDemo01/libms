<?php
declare(strict_types=1);
/**
 * DASHBOARD/sidebar controller
 *
 * Retrieve the count of unread messages for the current logged-in user, and
 * display the sidebar menu.
 *
 * For the full copyright and license information, please view the
 * {@link https://github.com/MKen212/libraryms/blob/master/LICENSE LICENSE}
 * file that was included with this source code.
 */

namespace LibraryMS;

// Get Unread Received Message count in HTML Link for current user
$msgsUnreadLink = getMsgsUnreadLink();

// Display Sidebar Menu View
include "../app/views/dashboard/sidebarMenu.php";
