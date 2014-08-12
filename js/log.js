
function add_log(project_data, type){
  // console.log(project_data);
  var post = $.post("/windex/functions/_log.php", {
    log_data : JSON.stringify(project_data),
    log_path: project_data.path,
    log_type: type,
    log_designer: project_data.designer,
    project_db: project_data.database
  });
  post.done(function (){
    ping('Log Updated');
  });
  return false;
};


// (function($){
//   $.fn.serializeObject = function(){

//     var self = this,
//     json = {},
//     push_counters = {},
//     patterns = {
//       "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
//       "key":      /[a-zA-Z0-9_]+|(?=\[\])/g,
//       "push":     /^$/,
//       "fixed":    /^\d+$/,
//       "named":    /^[a-zA-Z0-9_]+$/
//     };


//     this.build = function(base, key, value){
//       base[key] = value;
//       return base;
//     };

//     this.push_counter = function(key){
//       if(push_counters[key] === undefined){
//         push_counters[key] = 0;
//       }
//       return push_counters[key]++;
//     };

//     $.each($(this).serializeArray(), function(){
//       // skip invalid keys
//       if(!patterns.validate.test(this.name)){
//         return;
//       }

//       var k,
//           keys = this.name.match(patterns.key),
//           merge = this.value,
//           reverse_key = this.name;

//       while((k = keys.pop()) !== undefined){
//         // adjust reverse_key
//         reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');
//         // push
//         if(k.match(patterns.push)){
//           merge = self.build([], self.push_counter(reverse_key), merge);
//         }
//         // fixed
//         else if(k.match(patterns.fixed)){
//           merge = self.build([], k, merge);
//         }
//         // named
//         else if(k.match(patterns.named)){
//           merge = self.build({}, k, merge);
//         }
//       }
//       json = $.extend(true, json, merge);
//     });
//     return json;
//   };
// })(jQuery);


// $update_log_status_height = $('#update_log_status').height();
// $table_height = Math.round($('#main table').height() - $update_log_status_height);
// $log_height = $('#log_roll ul').height();
// if ($('#main table').height() > '600') {
// 	$('#log_roll .viewport').css({ "height": $table_height + 'px' });
// }
// $("#log_roll").tinyscrollbar();
// $('#log_box').css({ "height": (wh - 80) + 'px' });

// $('#update_log_status').submit(function(e){
//   e.preventDefault();
//   data = $(this).serializeObject();
//   type = $(this).attr('data-type');
//   console.log(data);
//   add_log({'item': data}, type);
// 	this.reset();
// });

// $('#update_log_showtell .btn').submit(function(e){
//   e.preventDefault();
//   console.log('HALALAL');
//   data = $(this).serializeObject();
//   type = $(this).attr('data-type');
//   console.log(data);
//   console.log(type);
//   add_log({'item': data}, type);
//   $(this).hide('fast');
// });

// // Log & Log Roll
// // $('.add_log').click(function(e){
// //   e.preventDefault();
// //   $('#update_log_status').toggle();
// //   $('.add_log').toggle();
// //   $("#update_log_status #status").focus();
// // });
// // 
// // $('#update_log_status .cancel').click(function(e){
// //   e.preventDefault();
// //   $('#update_log_status').toggle();
// //   $('.add_log').toggle();
// // });

// // Reset !
// $('#update_log_reset').submit(function(e){
//   e.preventDefault();
//   data = $(this).serializeObject();
//   type = $(this).attr('data-type');
//   add_log({'item': data}, type);
//   reset();
//   $('#reset_modal').modal('hide');
//   // location.reload(); // refresh page
// });


// //turn to inline mode
// $.fn.editable.defaults.mode = 'popup';
// $('.btn-fav').editable({
//   ajaxOptions: {
//     dataType: 'json', //assuming json response
//     data: $(this)
//   },
//   pk:1,
//   type: 'textarea',
//   mode: 'popup',
//   url: '/windex/functions/_showtell.php',
//   placement : 'bottom',
//   name: 'showtell',
//   onblur: 'cancel'
// });




// var st = $('.showtell').html();
// $('.showtell').remove();
// $('.btn-fav').popover({ 
//   html : true,
//   content: function () {
//     return $('#popovercontent').html();
//   }
// });

// $('.btn-fav').click(function(e){
//   e.preventDefault();
//   // {"item":{"designer":"Jeremy Zilar","project_name":"Sections","path":"/project/sections/","log_status":"yep"}}
//   // data = '12345';
//   var fav = {};
//   fav['designer'] = $('#user .designer').text();
//   fav['project_name'] = $('#page-head h2').text();
//   fav['path'] = path;
//   fav['log_status'] = '';
//   data = fav;
//   type = $(this).attr('data-type');
//   add_log({'item': data}, type);
// });






