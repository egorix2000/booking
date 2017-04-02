<?php
require_once dirname(__FILE__).'/../../global.inc.php';

if (!defined('SITE_ADMIN')){
  header('Location: ../../permissionDenied.php');
  exit;
}
 ?>

 <html>
 <head>
 <title>Bookings</title>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <link rel="stylesheet" href="/resources/demos/style.css">
 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <script type="text/javascript" src="script.js"></script>
 <script>
 $(document).ready(function(){
   document.addEventListener('click', function (e) {
     if (hasClass(e.target, 'submit')) {
       var id = e.target.id;
       $( "#dialog" ).dialog({
         resizable: false,
         height: "auto",
         width: 400,
         modal: true,
         buttons: {
           "Delete item": function() {
             $.ajax({
               type: 'POST',
               url: 'delete.php?id='+id,
               success: function(data){
                 if (data != 1){
                   alert(data);
                 } else {
                   location.reload();
                 }
               }
             });
             $( this ).dialog( "close" );
           },
           Cancel: function() {
             $( this ).dialog( "close" );
           }
         }
       });
     }
   }, false);
   function hasClass(elem, className) {
     return elem.className.split(' ').indexOf(className) > -1;
   }

   var currentPage = 0;
   var allPages = 0;
   var headers;
   var bookings;
   var table;
   $.ajax({
     dataType:"json",
     type: 'POST',
     url: 'getAllBookings.php',
     success: function(data){
       console.log(data);
       allPages = data[0];
       headers = data[1];
       bookings = data[2];
       table = "<table><tr>";
       for (var t = 0; t < headers.length; t++){
         table += "<td class='headerTable'>"+headers[t]+"</td>";
       }
       table += "</tr>";
       var end = 30;
       if (bookings.length < 30){
         end = bookings.length;
       }
       for (var t = 0; t < end; t++){
         table += "<tr>";
         for (var i = 0; i < bookings[t].length; i++){
           table += "<td>"+bookings[t][i]+"</td>";
         }
         table += "<td><div class='tableActions' style='display: inline-block;'><form class='tableActions' action='update.php' method='post'><input hidden name='id' value="+bookings[t][0]+"><input class='tableButtonUpdate' type='submit' value='Update' name='updateFromIndex' /></form></div><div class='tableActions' style='display: inline-block;'><button class='tableButtonDelete submit' id="+bookings[t][0]+">Delete</button></div></td></tr>";
       }
       table += "</table>";
       $('#tableP').append(table);
       $('.currentPage').append('Page: '+(currentPage+1)+' from: '+allPages);
     }
   });

   $("#previous").click(function() {
     if (currentPage != 0) {
       currentPage--;
       table = "<table><tr>";
       for (var t = 0; t < headers.length; t++){
         table += "<td class='headerTable'>"+headers[t]+"</td>";
       }
       table += "</tr>";
       var begin = 30*currentPage;
       var end = begin + 30;
       if (bookings.length < begin+30){
         end = bookings.length;
       }
       for (var t = begin; t < end; t++){
         table += "<tr>";
         for (var i = 0; i < bookings[t].length; i++){
           table += "<td>"+bookings[t][i]+"</td>";
         }
         table += "<td><div class='tableActions' style='display: inline-block;'><form class='tableActions' action='update.php' method='post'><input hidden name='id' value="+bookings[t][0]+"><input class='tableButtonUpdate' type='submit' value='Update' name='updateFromIndex' /></form></div><div class='tableActions' style='display: inline-block;'><button class='tableButtonDelete submit' id="+bookings[t][0]+">Delete</button></div></td></tr>";
       }
       table += "</table>";
       $('#tableP').empty();
       $('.currentPage').empty();
       $('#tableP').append(table);
       $('.currentPage').append('Page: '+(currentPage+1)+' from: '+allPages);
     }
    });

    $("#next").click(function() {
      if (currentPage != allPages-1) {
        currentPage++;
        table = "<table><tr>";
        for (var t = 0; t < headers.length; t++){
          table += "<td class='headerTable'>"+headers[t]+"</td>";
        }
        table += "</tr>";
        var begin = 30*currentPage;
        var end = begin + 30;
        if (bookings.length < begin+30){
          end = bookings.length;
        }
        for (var t = begin; t < end; t++){
          table += "<tr>";
          for (var i = 0; i < bookings[t].length; i++){
            table += "<td>"+bookings[t][i]+"</td>";
          }
          table += "<td><div class='tableActions' style='display: inline-block;'><form class='tableActions' action='update.php' method='post'><input hidden name='id' value="+bookings[t][0]+"><input class='tableButtonUpdate' type='submit' value='Update' name='updateFromIndex' /></form></div><div class='tableActions' style='display: inline-block;'><button class='tableButtonDelete submit' id="+bookings[t][0]+">Delete</button></div></td></tr>";
        }
        table += "</table>";
        $('#tableP').empty();
        $('.currentPage').empty();
        $('#tableP').append(table);
        $('.currentPage').append('Page: '+(currentPage+1)+' from: '+allPages);
      }
     });

 });
 </script>
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
     <div class="headerLabel">Bookings</div>
   </div>
   <div class="tableDiv">
     <p id="tableP"></p>
     <div id="navigation"><button id="previous" style='display: inline-block;'>&#8592;</button><div class="currentPage" style='display: inline-block; margin-right: 10px; margin-left: 10px;'></div><button id="next" style='display: inline-block;'>&#8594;</button></div>
   </div>

   <div id="dialog" title="Delete item?" style="display:none">
     <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Item will be permanently deleted and cannot be recovered. Are you sure?</p>
   </div>
 </body>
 </html>
