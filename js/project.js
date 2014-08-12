// Send Alert or Notification
function ping(txt){
  $('.ping').fadeTo(400, 1).delay(800).fadeTo(400, 0);
  $('.ping span').text(txt);
}

function get_project_data(){
  var login;
  // Project Data
  var project_data = {};
  project_data['version'] = $('body').attr('data-version');
  project_data['locked'] = false;
  project_data['private'] = false;
  project_data['template'] = $('#theme_switcher a.active').attr('data-theme');
  project_data['created'] = moment().format(); //...
  project_data['updated'] = moment().format(); //...
  project_data['designer'] = login || 'Jeremy Zilar';
  project_data['title'] = $('#page-head h2').text();
  project_data['desc'] = $('#page-head h5').text();
  project_data['section'] = 'National';
  project_data['database'] = $('body').attr('data-base');
  project_data['editable'] = $('body').attr('editable');

  // Project Items
  // This pushes all the project items into the object
  project_data['items'] = [];
  $('tr.i').each(function (e, i) {
    project_data['items'].push({
      'ext': $('.icon', this).attr('data-ext'),
      'type': $('.icon', this).attr('data-ext'),
      'name': $('.file a:first-child', this).text(),
      'slug': $('.file a:first-child', this).attr('href'),
      'hidden': $(this).hasClass('quiet'),
    })
  });

  // Path
  project_data['path'] = path;
  // console.log(project_data.path);

  return project_data;
}


function save(){
  var login;

  var project_data = get_project_data();
  
  // // Log this save.
  // var log_data = {};
  // log_data['designer'] = login || 'Jeremy Zilar';
  // log_data['log_status'] = 'Edited';
  // log_data['project_name'] = $('#head h2').text();
  // log_data['project_desc'] = $('#head h5').text();
  
  // // Post Log Data
  // var post = $.post("/windex/functions/_log.php", {
  //   log_data : JSON.stringify(project_data),
  //   log_path: project_data.path,
  //   log_type: 'edit',
  //   log_designer: project_data.designer,
  //   project_db: project_data.database
  // });
  // post.done(function (){
  //   var txt = 'logged'; // confirmation text
  //   ping(txt);
  // });

  add_log(project_data, 'edit');


  // Post Project Data
  var post = $.post("/windex/functions/_project.php", {
    project_data : JSON.stringify(project_data),
    project_url: project_data.path,
    project_db: project_data.database,
    project_editable: project_data.editable
  });
  post.done(function (){
    ping('Project saved');
  });
  
  return false;
};