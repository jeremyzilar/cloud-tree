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
    echo <<<EOF
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
      <tbody>
EOF;

    foreach ($files['items'] as $file) {
      $size = $file['size'];
      $name = $file['name'];
      $owner = $file['owner'];
      $path = str_replace($dr,'',$file['path']);
      if ($path == $r) {
        $path = '';
      }
      $link = $r.$name;
      $created = $file['created'];
      $modified = $file['modified'];
      $octal_perms = $file['octal_perms'];
      if ($file['hidden'] == true) {
        $hidden = 'quiet';
      } else {
        $hidden = '';
      }
      // Check File types and extensions
      $type = $file['type'];
      $ext = $file['ext'];
      $ext = file_type($type, $ext);
      echo <<<EOF
      <tr class="i $hidden">
        <td class="hide_file"><i class="fa fa-eye"></i></td>
        <td valign="top" class="icon $type" data-ext="$ext"><a href="$link"><img src="/windex/icons/$ext.png" alt="$ext" width="24" height="24"></a></td>
        <td class="file"><a href="$link">$name</a></td>
        <td class="modified"><span class="log_time" title='$modified'>$modified</span></td>
        <td class="action download"><a href="$link" download="$name"><i class="fa fa-download"></i></a></td>
        <td class="action delete" data-uri="$link"><i class="fa fa-trash-o"></i></td>
      </tr>
EOF;
    }
    echo '<tbody></table>';
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