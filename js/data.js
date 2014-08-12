
function get_data(d){
  console.log(d);
  fData =
  $.ajax({
    url:d,
    dataType: 'json',
    async: false,
    success : function(data) {
      fData = data;
    }
  });
  fData.done(function(data){ // ...when fData is done
    // results.fData = data.assets;
    console.log(data);
  })
  .fail (function(){
    console.log('Failed!');
  })
  .always(function(){
  });
}

var path = window.location.pathname; // Get the current path
var d = 'http://localhost/windex/js/list.json'; // contents of the folder
get_data(d);