// Images


// Make Circle Images
var dfd = $('.circle img').imagesLoaded(); // save a deferred object
dfd.done( function( $images ){
  var img = $images;
  $.each(img, function(i,c) {
    $(this).wrap(function(){
      return '<span class="image-wrap ' + $(this).attr('class') + '" style="position:relative; display:inline-block; background:url(' + $(this).attr('src') + ') no-repeat center center; width: ' + $(this).width() + 'px; height: ' + $(this).height() + 'px;" />';
    });
    $(this).css("opacity","0");
  });
});