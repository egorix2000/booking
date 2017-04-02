<html>
<head>
  <style>.menu {
    background-color: #000000;
    height: 10%;
    width: 100%;
  }

  .item {
    height: 100%;
    margin-left: 10px;
    display:inline-block;
  }

  .buttonItem {
    height: 100%;
    width: 100%;
    background-color: #000000;
    color: white;
    border: none;
    font-size: 15px;
  }

  .header {
    margin-top: 30px;
  }

  .headerLabel {
    font-size: 30px;
    margin-left: 3%;
    display:inline-block;
  }

  .headerDivButton {
    display:inline-block;
    float: right;
    margin-right: 5%;
  }

  .headerButton {
    background-color: #3CB371;
    font-size: 15px;
    border-radius: 5px;
    color: white;
    border: 1px #40E0D0 solid;
    height: 40px;
  }

  .error {
    color: red;
    margin-top: 70px;
    margin-left: 15%;
  }
  .input {
    padding: 6px;
    font-size: 15px;
    text-align: left;
    border-width: 1px;
    border-radius: 8px;
    border-style: solid;
    display: inline;
  }

  .select {
    width: 200px;
    font-size: 20px;
    text-align: left;
    border-width: 1px;
    border-radius: 25px;
    border-style: solid;
  }

  .label {
    display: inline;
    font-size: 20px;
  }

  .inputDiv{
    margin-top: 20px;
    width: 40%;
  }

  .field {
    margin-left: 15%;
    margin-top: 20px;
  }

  .headerButtonInput {
    background-color: #3CB371;
    font-size: 15px;
    border-radius: 5px;
    color: white;
    border: 1px #40E0D0 solid;
    height: 30px;
    width: 20%;
    float: right;
    margin-right: 25%;
    margin-top: 20px;
  }

</style>
<title>Bookings</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( ".datepicker" ).datepicker();
  } );
  $(document).ready(function(){
    document.addEventListener('mouseover', function (e) {
      $( ".datepicker" ).datepicker();
    }, false);

    var labels;

    // SET PATH HERE
    var path = ""; // EXAMPLE: var path = "http://mySite/path/booking/";

    $("#submit").click(function() {
      var url = path + 'views/bookingViews/create1.php?elementId='+$('#elementIds').val()+'&username='+$('#username').val()+'&email='+$('#email').val()+'&phone='+$('#phone').val()+'&dateFrom='+$('#dateFrom').val()+'&bookingDays='+$('#bookingDays').val();
      for (var t = 0; t < labels.length; t++){
        url += '&'+labels[t]+'='+$('#'+labels[t]+'').val();
      }
      $('#message').html('Waiting');
      $( "#dialog" ).dialog();
      $.ajax({
        type: 'POST',
        url: url,
        success: function(data){
          $('#error').html(data);
          $('#message').html(data);
          $( "#dialog" ).dialog();
        }
      });
    });
    $.ajax({
      dataType:"json",
      type: 'POST',
      url: path + 'views/bookingViews/initCreate.php',
      success: function(data){
        $('#elementIds').append(data[0]);
        $('#formCreateBooking').append(data[1]);
        labels = data[2];
      }
    });


  });
  </script>
</head>
<body>

  <div class="header">
    <div class="headerLabel">Booking</div>
  </div>

  <div class="inputDiv" >
    <div class="field"><div class="label">Element: </div><select class="select" id="elementIds"></select></div>
    <div class="field"><div class="label">Username: </div><input class="input" type="text" placeholder = "Name" id="username" /></div>
    <div class="field"><div class="label">Email: </div><input class="input" type="text" placeholder = "my@email.com" id="email" /></div>
    <div class="field"><div class="label">Phone: </div><input class="input" type="text" id="phone" /></div>
    <div class="field"><div class="label">Date From: </div><input type="text" class="datepicker input" id="dateFrom"></div>
    <div class="field"><div class="label">Booking Days: </div><input class="input" type="number" id="bookingDays" /></div>
    <div id="formCreateBooking"></div>
    <button class="headerButtonInput" id="submit">Create</button>
    <div class="error" id="error"></div>
  </div>

  <div id="dialog" style="display:none">
    <p><div id="message"></div></p>
  </div>

</body>
</html>
