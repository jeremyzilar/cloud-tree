$slideshow = $('#slideshow');
$slideshow_nav = $('#slideshow .navbar');
$('#slideshow .navbar .container').attr('class', 'container-fluid');
$img_reset = $('.img_reset a');
$all_imgs = $('#slideshow .item img').each(function(){});
$hash = location.hash.substr(1);
$totalslides = $('#slideshow .slideshow-inner .item').length;
$oops = $('#slideshow .slideshow-inner .oops');


// - - - - - - - - - - - - - - - 


// Zoom In to Image
$('.zoom-in').click(function(e){
  e.preventDefault();
  $z = (get_current_zoom() + parseFloat(.20)).toFixed(2);
  if (get_current_zoom() <= parseFloat(.15)) {
    $z = (get_current_zoom() + parseFloat(.05)).toFixed(2);
  };
  if (get_current_zoom() <= parseFloat(.04)) {
    $z = (get_current_zoom() + parseFloat(.01)).toFixed(2);
  };
  $p = ($z*100);
  zoom_percent($p);
  $slideshow.attr('data-zoom', $z);
  $all_imgs.each(function(){
    $(this).css({
      '-moz-transform': 'scale('+$z+','+$z+')',
      '-webkit-transform': 'scale('+$z+','+$z+')',
      'transform': 'scale('+$z+','+$z+')',
      'transform-origin': '50% 0'
    });
  });
});

// Zoom Out to Image
$('.zoom-out').click(function(e){
  e.preventDefault();
  $z = (get_current_zoom() - parseFloat(.20)).toFixed(2);
  if (get_current_zoom() <= parseFloat(.20)) {
    $z = (get_current_zoom() - parseFloat(.05)).toFixed(2);
  };
  if (get_current_zoom() <= parseFloat(.05)) {
    $z = (get_current_zoom() - parseFloat(.01)).toFixed(2);
  };
  $slideshow.attr('data-zoom', $z);
  $p = ($z*100);
  zoom_percent($p);
  $all_imgs.each(function(){
    $(this).css({
      '-moz-transform': 'scale('+$z+','+$z+')',
      '-webkit-transform': 'scale('+$z+','+$z+')',
      'transform': 'scale('+$z+','+$z+')',
      'transform-origin': '50% 0'
    });
  });
  make_draggable(n = $('#slideshow .active img'));
});

function get_current_zoom(){
  var zoom = parseFloat($slideshow.attr('data-zoom'));
  return zoom;
}

// Set the visual indicator for percentage zoomed
function zoom_percent(p){
  if ($p == 0) {
    $p = '';
  } else {
    if ($p > 0) {
      $p = $p + '%';
      show_img_reset($p);
    } else {
      $p = Math.abs($p);
      $p = $p + '%';
      show_img_reset($p);
    }
  };
  $('.zoom-msg a').html($p);
}

// Show Image Reset button 
function show_img_reset(p){
  $img_reset = $('.img_reset a');
  if ($p !== '') {
    $img_reset.css('opacity', 1);
  };
}

function img_reset(){
  $all_imgs.each(function(){
    $(this).css({
      'left': '0',
      'top': '0',
      '-webkit-transform':'scale(1,1)',
      'transform':'scale(1,1)',
      '-moz-transform':'scale(1,1)'
    });
    zoom_percent($p = 0);
  });
  $slideshow.attr('data-zoom', 1.0);
  $img_reset.css('opacity', 0);
}

// Image Reset — zoom and centering
$img_reset.click(function(e, all_imgs){
  e.preventDefault();
  img_reset();
});

// Make the .active image draggable.
// Start, Drag, Stop drags — http://jqueryui.com/draggable/#events
function make_draggable(e){
  e.draggable({
    drag: function() {
      $p = $(this).attr('style');
      show_img_reset($p);
    }
  });
}
make_draggable(n = $('#slideshow .active img'));



// = = = = = = = = = = = = = = = = = = = = = = = = = = = 


// Start Slideshow
$('.btn-slideshow').click(function(e){
  e.preventDefault();
  $slideshow.show();
  $('body').addClass('slideshow_time');
});


// Starts Slideshow is has is in URL
// Only for linking directly to a slide
function start_on_slide(num){
 if ($.isNumeric(num)) {
  $slideshow.show();
  $('body').addClass('slideshow_time');
  console.log('Oh, hello ' + num);
  if (num > $totalslides) {
    slide_not_found();
  };
  update_hash(num);

  fire_slideshow(parseFloat(num));
  update_dropdown(num);
 };
}
start_on_slide($hash);


// Advance Slideshow by clicking on slide
$('#slideshow .slideshow-inner .item').click(function(e){
  e.preventDefault();
  var to = (parseFloat(get_active_slide()) + 1);
  if (to > $totalslides) {
    var to = 1;
  };
  update_hash(to);
});

// Next — Advance Slideshow
$('#slideshow .slideshow-controls .slide-next').click(function(e){
  e.preventDefault();
  var to = (parseFloat(get_active_slide()) + 1);
  if (to > $totalslides) {
    var to = 1;
  };
  update_hash(to);
});

// Prev — Advance Slideshow
$('#slideshow .slideshow-controls .slide-prev').click(function(e){
  e.preventDefault();
  var to = (parseFloat(get_active_slide()) - 1);
  if (to < 1) {
    var to = $totalslides;
  };
  update_hash(to);
});

// Update the Hash
function update_hash(to){
  window.location.hash = to;
}

// Listen for the #Hash Change
$(window).bind('hashchange', function() {
  var num = location.hash.substr(1);
  if (num <= $totalslides) {
    fire_slideshow(parseFloat(num));
    update_dropdown(num);
  } else {
    slide_not_found();
  }
}); 



// Fire the Slideshow / Pause it from moving
function fire_slideshow(slide){
  $('#slideshow .slideshow-inner .active').removeClass('active');
  $act = $("#slideshow .slideshow-inner").find('[data-slide="'+slide+'"]').addClass("active");
  make_draggable(n = $('#slideshow .active img'));
}


function slide_not_found(){
  $('#slideshow .slideshow-inner .active').removeClass('active');
  $oops.show();
}


// clear hash
$('.clear_hash').click(function(e){
  e.preventDefault();
  window.location.hash = '';
  $slideshow.hide();
  $('body').removeClass('slideshow_time');
  $oops.hide();
});

// clear_404
$('.clear_404').click(function(e){
  $oops.hide();
});

// Close Slideshow
$('.trigger_close a').click(function(e){
  e.preventDefault();
  history.pushState('', document.title, window.location.pathname);
  $slideshow.hide();
  img_reset();
  $('body').removeClass('slideshow_time');
});


function update_dropdown(slide){
  $("#slideshow .navbar .dropdown ul").find('[class="active"]').removeClass("active");
  var active = $("#slideshow .navbar .dropdown ul").find('[data-slide-to="'+slide+'"]').addClass("active");
  var active_name = active.text();
  $('.dropdown-toggle').html(active_name + ' <span class="caret"></span>');
}

// Close Slideshow
$('.dropdown-menu li').click(function(e){
  e.preventDefault();
  var to = $(this).attr('data-slide-to');
  update_hash(to);
});

function get_active_slide(){
  var active = $('#slideshow .slideshow-inner .active').attr('data-slide');
  return active;
}


// Next & Prev for Slideshow with Keyboard
$(document).bind('keyup', function(e) {
  if(e.which == 39){
    $('.carousel').carousel('next');
  }
  else if(e.which == 37){
    $('.carousel').carousel('prev');
  }
});

// // Hide the Slideshow navbar after certain time
// $slideshow_nav_height = $slideshow_nav.height();
// $slideshow_nav.css('top', 0);
// var math = -Math.abs($slideshow_nav_height);
// setTimeout(function() {
//   $slideshow_nav.css('top', math);
// }, 2000);

// // On mousemove, hide navbar
// $slideshow.mousemove(function(e){
//   var m = e.pageY;
//   toggle_nav(m);
// }); 

// function toggle_nav(m){
//   if (m < 150) {
//     animate_nav(s = 'open');
//   } else {
//     animate_nav(s = 'close');
//   };
// };

// function animate_nav(s){
//   if (s == 'open') {
//     var math = 0;
//     $slideshow_nav.css('top', math);
//   } else {
//     var math = -Math.abs($slideshow_nav_height);
//     setTimeout(function() {
//       $slideshow_nav.css('top', math);
//     }, 2000);
//   };
  
// }
