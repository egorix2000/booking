$(document).ready(function(){
  $(".submit").click(function() {
    var id = this.id;
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
  });
});
