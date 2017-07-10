<?php
require_once dirname(__FILE__).'/../../global.inc.php';

 ?>
 <html>
 <head>
 <title>Bookings</title>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <link rel="stylesheet" href="/resources/demos/style.css">
 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$(document).ready(function(){
      $( "#dialog" ).dialog({
        resizable: false,
        height: "auto",
        width: 400,
        modal: true,
        buttons: {
          "Cancel reservation": function() {
            $.ajax({
              type: 'POST',
              url: 'delete.php?id='+<?= $_REQUEST['id'] ?>,
              success: function(data){
                if (data != 1){
                  alert(data);
                } else {
                  alert('Your reservation has already been cancelled');
                }
              }
            });
            $( this ).dialog( "close" );
          },
          "Don't cancel": function() {
            $( this ).dialog( "close" );
          }
        }
      });
});
  </script>

</head>
<body>
  <div id="dialog" title="Cancel reservation?" style="display:none">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Reservation will be cancelled. Are you sure?</p>
  </div>
</body>
</html>
