$(document).ready(function() {
    document.addEventListener('click', function (e) {
      if (hasClass(e.target, 'tdClick')) {
          $.ajax({
            type: 'POST',
            url: 'getDayBooking.php?month='+(currentMonth+1)+'&year='+currentYear+'&day='+e.target.id,
            success: function(data){
              $('#contentDialog').empty();
              $('#contentDialog').append(data);
              $('#dialog').dialog({width: 1000});
            }
         });
      }
    }, false);
    function hasClass(elem, className) {
      return elem.className.split(' ').indexOf(className) > -1;
    }

   var month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
   var date = new Date();
   var currentMonth = date.getMonth();
   var currentYear = date.getFullYear();
   $('.month').html(month[currentMonth] + ' ' + currentYear);

   var days = "<tr><td class='itemCalendar'>Elements</td>";
   for (var t = 0; t < 32 - new Date(currentYear, currentMonth, 32).getDate(); t++){
     if ((new Date(currentYear, currentMonth, t)).getDay() == 6 || (new Date(currentYear, currentMonth, t)).getDay() == 5){
       days += "<td class='weekend'>"+(t+1)+"</td>";
     } else {
       days += "<td>"+(t+1)+"</td>";
     }
   }
   days += "</tr>";
   $('#table').append(days);


   var daysInMonth = (32 - new Date(currentYear, currentMonth, 32).getDate());
   var calendar; //[elementId][day]
   var elementNames;
   $.ajax({
     type: 'POST',
     dataType:"json",
     url: 'getCalendar.php?month='+(currentMonth+1)+'&year='+currentYear+'&days='+daysInMonth,
     success: function(data){
      elementNames = data[0];
      calendar = data[1];
      fillTable(elementNames, calendar);
     }
  });
  function fillTable(elementNames, calendar) {
   for (var t = 0; t < calendar.length; t++){
     var days = "<tr><td class='itemCalendar'>"+elementNames[t]+"</td>";
     for (var i = 0; i < calendar[t].length; i++){
       if (calendar[t][i] == 0){
         days += "<td id="+(i+1)+" class='empty tdClick'></td>";
       } else {
         days += "<td id="+(i+1)+" class='tdClick'>"+calendar[t][i]+"</td>";
       }
     }
     days += "</tr>";
     $('#table').append(days);
   }
 }

   $('#next').click(function() {
     if (currentMonth == 11){
       currentMonth = 0;
       currentYear++;
     } else {
       currentMonth++;
     }
     $('.month').html(month[currentMonth] + ' ' + currentYear);
     $('#table').html('');
     var days = "<tr><td class='itemCalendar'>Elements</td>";
     for (var t = 0; t < 32 - new Date(currentYear, currentMonth, 32).getDate(); t++){
       if ((new Date(currentYear, currentMonth, t)).getDay() == 6 || (new Date(currentYear, currentMonth, t)).getDay() == 5){
         days += "<td class='weekend'>"+(t+1)+"</td>";
       } else {
         days += "<td>"+(t+1)+"</td>";
       }
     }
     days += "</tr>";
     $('#table').append(days);


        var daysInMonth = (32 - new Date(currentYear, currentMonth, 32).getDate());
        var calendar; //[elementId][day]
        var elementNames;
        $.ajax({
          type: 'POST',
          dataType:"json",
          url: 'getCalendar.php?month='+(currentMonth+1)+'&year='+currentYear+'&days='+daysInMonth,
          success: function(data){
           elementNames = data[0];
           calendar = data[1];
           fillTable(elementNames, calendar);
          }
       });
       function fillTable(elementNames, calendar) {
        for (var t = 0; t < calendar.length; t++){
          var days = "<tr><td class='itemCalendar'>"+elementNames[t]+"</td>";
          for (var i = 0; i < calendar[t].length; i++){
            if (calendar[t][i] == 0){
              days += "<td id="+(i+1)+" class='empty tdClick'></td>";
            } else {
              days += "<td id="+(i+1)+" class='tdClick'>"+calendar[t][i]+"</td>";
            }
          }
          days += "</tr>";
          $('#table').append(days);
        }
      }
   });
   $('#previous').click(function() {
     if (currentMonth == 0){
       currentMonth = 11;
       currentYear--;
     } else {
       currentMonth--;
     }
     $('.month').html(month[currentMonth] + ' ' + currentYear);
     $('#table').html('');
     var days = "<tr><td class='itemCalendar'>Elements</td>";
     for (var t = 0; t < 32 - new Date(currentYear, currentMonth, 32).getDate(); t++){
       if ((new Date(currentYear, currentMonth, t)).getDay() == 6 || (new Date(currentYear, currentMonth, t)).getDay() == 5){
         days += "<td class='weekend'>"+(t+1)+"</td>";
       } else {
         days += "<td>"+(t+1)+"</td>";
       }
     }
     days += "</tr>";
     $('#table').append(days);


        var daysInMonth = (32 - new Date(currentYear, currentMonth, 32).getDate());
        var calendar; //[elementId][day]
        var elementNames;
        $.ajax({
          type: 'POST',
          dataType:"json",
          url: 'getCalendar.php?month='+(currentMonth+1)+'&year='+currentYear+'&days='+daysInMonth,
          success: function(data){
           elementNames = data[0];
           calendar = data[1];
           fillTable(elementNames, calendar);
          }
       });
       function fillTable(elementNames, calendar) {
        for (var t = 0; t < calendar.length; t++){
          var days = "<tr><td class='itemCalendar'>"+elementNames[t]+"</td>";
          for (var i = 0; i < calendar[t].length; i++){
            if (calendar[t][i] == 0){
              days += "<td id="+(i+1)+" class='empty tdClick'></td>";
            } else {
              days += "<td id="+(i+1)+" class='tdClick'>"+calendar[t][i]+"</td>";
            }
          }
          days += "</tr>";
          $('#table').append(days);
        }
      }
    });
});
