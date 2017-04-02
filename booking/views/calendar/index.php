<?php

ini_set('display_errors',1);
error_reporting(E_ALL);

require_once '../../services/BookingService.class.php';
require_once '../../services/UserFieldService.class.php';
require_once dirname(__FILE__).'/../../global.inc.php';

if (!defined('SITE_ADMIN')){
  header('Location: ../../permissionDenied.php');
  exit;
}

date_default_timezone_set('UTC');
$service = new BookingService();
$entities = $service->getAllBookings();

$userFieldService = new UserFieldService();
$allFields = $userFieldService->getAllFields();
?>

 <html>
 <head>
 <title>Bookings</title>
 <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
   <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <link type="text/css" rel="stylesheet" href="../../styles/style.css"/>
 <link type="text/css" rel="stylesheet" href="../../styles/calendarStyle.css"/>
 <script type="text/javascript" src="script.js"></script>
 </head>
 <body>
   <div class="menu">
     <div class="item"><form class="buttonItem" action="../bookingViews/viewPage.php" method="post"><input class="buttonItem" type="submit" value="View Page" name="menu" /></form></div>
     <div class="item"><form class="buttonItem" action="../bookingViews/index.php" method="post"><input class="buttonItem" type="submit" value="Bookings" name="menu" /></form></div>
     <div class="item"><form class="buttonItem" action="../bookingElementViews/index.php" method="post"><input class="buttonItem" type="submit" value="Booking elements" name="menu" /></form></div>
     <div class="item"><form class="buttonItem" action="../calendar/index.php" method="post"><input class="buttonItem" type="submit" value="Calendar" name="menu" /></form></div>
     <div class="item"><form class="buttonItem" action="../userFieldViews/index.php" method="post"><input class="buttonItem" type="submit" value="User Fields" name="menu" /></form></div>
   </div>
   <div class="header">
     <div class="headerLabel">Booking Calendar</div>
   </div>
   <div class="calendar">
     <div class="calendarHeader">
       <button id="previous">&#8592;</button><div class="month"></div><button id="next">&#8594;</button>
     </div>
     <div class="calendarTable">
       <table id="table">
       </table>
    </div>
   </div>

   <div id="dialog" title="Bookings" style="display:none">
     <p><div id="contentDialog"></div></p>
   </div>
 </body>
 </html>
