<?php

require_once '../../services/BookingService.class.php';
require_once '../../services/UserFieldService.class.php';
require_once dirname(__FILE__).'/../../global.inc.php';

if (!defined('SITE_ADMIN')){
  header('Location: ../../permissionDenied.php');
  exit;
}

date_default_timezone_set('UTC');

$service = new BookingService();

$todayBookings = $service->getTodayBookings();
$lastThreeBookings = $service->getLastThreeBookings();

$userFieldService = new UserFieldService();
$allFields = $userFieldService->getAllFields();

 ?>

 <html>
 <head>
 <title>Bookings</title>
 <link type="text/css" rel="stylesheet" href="../../styles/style.css"/>
 <link type="text/css" rel="stylesheet" href="../../styles/styleTable.css"/>
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
     <div class="headerLabel">Today Active Bookings</div>
   </div>
   <div class="tableDiv">
     <p><?php
       $headers = "";
       foreach ($allFields as $value) {
         $headers .= "<td class='headerTable'>".$value->label."</td>";
       }
       $table = "<table><tr><td class='headerTable'>id</td><td class='headerTable'>elementId</td><td class='headerTable'>username</td><td class='headerTable'>email</td><td class='headerTable'>phone</td><td class='headerTable'>sendDate</td><td class='headerTable'>beginDate</td><td class='headerTable'>bookingDays</td>".$headers."</tr>";
       foreach ($todayBookings as $entity) {
         $userFieldsValues = "";
         foreach ($allFields as $value) {
           $userFieldsValues .= "<td>".$entity->userFields[$value->label]."</td>";
         }
         $table .= "<tr><td>".$entity->id."</td><td>".$entity->elementId."</td><td>".$entity->username."</td><td>".$entity->email."</td><td>".$entity->phone."</td><td>".date("m/d/Y", $entity->sendDate)."</td><td>".date("m/d/Y", $entity->beginDate)."</td><td>".$entity->bookingDays."</td>".$userFieldsValues."</tr>";
       }
       $table .= "</table>";
       echo $table;
       ?></p>
   </div>

   <div class="header">
     <div class="headerLabel">Last Three Bookings</div>
   </div>
   <div class="tableDiv">
     <p><?php
       $headers = "";
       foreach ($allFields as $value) {
         $headers .= "<td class='headerTable'>".$value->label."</td>";
       }
       $table = "<table><tr><td class='headerTable'>id</td><td class='headerTable'>elementId</td><td class='headerTable'>username</td><td class='headerTable'>email</td><td class='headerTable'>phone</td><td class='headerTable'>sendDate</td><td class='headerTable'>beginDate</td><td class='headerTable'>bookingDays</td>".$headers."</tr>";
       foreach ($lastThreeBookings as $entity) {
         $userFieldsValues = "";
         foreach ($allFields as $value) {
           $userFieldsValues .= "<td>".$entity->userFields[$value->label]."</td>";
         }
         $table .= "<tr><td>".$entity->id."</td><td>".$entity->elementId."</td><td>".$entity->username."</td><td>".$entity->email."</td><td>".$entity->phone."</td><td>".date("m/d/Y", $entity->sendDate)."</td><td>".date("m/d/Y", $entity->beginDate)."</td><td>".$entity->bookingDays."</td>".$userFieldsValues."</tr>";
       }
       $table .= "</table>";
       echo $table;
       ?></p>
   </div>
 </body>
 </html>
