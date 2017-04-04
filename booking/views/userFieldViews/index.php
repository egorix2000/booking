<?php

require_once '../../services/UserFieldService.class.php';
require_once dirname(__FILE__).'/../../global.inc.php';

if (!defined('SITE_ADMIN')){
  header('Location: ../../permissionDenied.php');
  exit;
}

$service = new UserFieldService();
$entities = $service->getAllFields();

 ?>

 <html>
 <head>
 <title>User Fields</title>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <link rel="stylesheet" href="/resources/demos/style.css">
 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <script type="text/javascript" src="script.js"></script>
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
     <div class="headerLabel">User Fields</div>
     <div class="headerDivButton"><form action="create.php" method="post"><input class="headerButton" type="submit" value="Create field" name="fromIndex" /></form></div>
   </div>
   <div class="tableDiv">
     <p><?php
       $table = "<table><tr><td class='headerTable'>id</td><td class='headerTable'>access</td><td class='headerTable'>type</td><td class='headerTable'>isRequired</td><td class='headerTable'>placeholder</td><td class='headerTable'>label</td><td class='headerTable'>actions</td></tr>";
       foreach ($entities as $entity) {
         $table .= "<tr><td>".$entity->id."</td><td>".$entity->access."</td><td>".$entity->type."</td><td>".$entity->isRequired."</td><td>".$entity->placeholder."</td><td>".$entity->label."</td>
         <td><div class='tableActions' style='display: inline-block;'><form class='tableActions' action='update.php' method='post'><input hidden name='id' value=".$entity->id.">
         <input class='tableButtonUpdate' type='submit' value='Update' name='updateFromIndex' /></form></div>
         <div class='tableActions' style='display: inline-block;'>
         <button class='tableButtonDelete submit' id=".$entity->id." value=".$entity->label.">Delete</button></div></td></tr>";
       }
       $table .= "</table>";
       echo $table;
       ?></p>
     </div>

     <div id="dialog" title="Delete item?" style="display:none">
       <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Item will be permanently deleted and cannot be recovered. Are you sure?</p>
     </div>
 </body>
 </html>
