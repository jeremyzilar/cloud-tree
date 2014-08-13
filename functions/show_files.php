<?php

function file_type($type, $ext){
  if ($type == 'dir') {
    $ext = 'dir';
  }
  
  $img = array('jpg','jpeg','png','PNG','gif','bmp','tif','tiff','ico');
  if (in_array($ext, $img)) {
    $ext = 'img';
  }
  
  $doc = array('doc','docx'); 
  if (in_array($ext, $doc)) {
    $ext = 'doc';
  }
  
  $xls = array('xls','xlsx'); 
  if (in_array($ext, $xls)) {
    $ext = 'xls';
  }
  
  $textmate = array('json','js','css','html','htm','php','jsonp','htaccess', 'svn-base'); 
  if (in_array($ext, $textmate)) {
    $ext = 'textmate';
  }

  return $ext;
}

function show_files($files){
  global $dr, $d, $r, $ordered_files;
	// print_r($files);
	if (!file_exists($d)){
		// include '../404.php';
		// return;
	}
  if (empty($files['items'])) {
		include TDIR . 'empty-dir.php';
  } else {
    ?>
    <table class="table col-xs-12 allfiles tablesorter">
      <thead>
      <tr>
        <th class="hide_file"></th>
        <th></th>
        <th>Name</th>
        <th>Last Modified</th>
        <th></th>
        <th class="delete"></th>
      </tr>
      </thead>
      <tbody><tbody></table><?php
  }
}


function show_file_names($image_names){
  // print_r($image_names);
  $first_name = $image_names['items'][0]['name'];
  $first_num = $image_names['items'][0]['num'];
  echo <<<EOF
  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span>$first_num</span> $first_name <b class="caret"></b></a>
  <ul class="dropdown-menu">
EOF;
  foreach ($image_names['items'] as $item) {
    $name = $item['name'];
    $num = $item['num'];
    echo <<<EOF
    <li><a href="#"><span>$num</span> $name</a></li>
EOF;
  }
  echo '</ul>';
}

?>