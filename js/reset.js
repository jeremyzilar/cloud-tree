$('.reset').click(function(e) {
  e.preventDefault();
  // console.log(path);
  reset();
  $('#reset_modal').modal('hide');
});

function reset(){
  console.log(path);
  var project_data = get_project_data();
  add_log(project_data, 'reset');
  
  var project_db = $('body').attr('data-base');
  var project_editable = $('body').attr('editable');
  var post = $.post("/windex/functions/_reset.php", {
    project_url: path,
    project_db: project_db,
    project_editable: project_editable
  });
};

// add_log({'item': data}, type);