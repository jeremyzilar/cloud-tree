function show_log(log_data) {
  console.log(log_data);
  var log_item = '\
  <div class="log_item">\
    <img class="log_designer_img" src="http://placehold.it/36x36/999999" alt="Updated by ' + log_data['log_designer'] + '">\
    <div class="log_status">\
      <h5 class="log_designer"> ' + log_data['log_designer'] + '</h5>\
      <h6 class="log_project_name"><a href="#"> ' + log_data['log_project_name'] + '</a></h6>\
      <p><a class="log_time" href="#"> ' + log_data['log_time'] + '</a> ' + log_data['log_status'] + '</p>\
    </div>\
  </div><!-- .log_item -->';
  $(log_data).each(function(i){
    if (this.type == 'image') {
      var img = '<div class="item"><div class="item-inner"><p>'+ i++ +'Slide Title</p><img src="'+ url + this.slug+'" alt="'+this.name+'" title="'+this.name+'"/></div></div>';
      $('#slideshow_stage .carousel-inner').append(img);
      slides.push(this.slug);
    };
  });
  // $('#log').append(log_item)
};

function show_project_log_data(project_log_data) {
  console.log(project_log_data);
  var item = '<li><img src="http://placehold.it/14x14/999999" alt="Updated by Jeremy Zilar"><a href="#">Apr 9, 2013</a></li>';
  
  $(project_log_data).each(function(i){
    $('#log_roll ul').append(item);
  });
};

