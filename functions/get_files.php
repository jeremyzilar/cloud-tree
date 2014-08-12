<?php

function is_edited(){
  global $r, $u;
  $results = '';
  // If EDITABLE and but does not have a DATABASE
  if (EDITABLE === true && DATABASE === false) {
    if (file_exists($u . 'root.json')) {
      $results = file_get_contents($u . 'root.json');
      $results = json_decode($results, true);
    }
  }
  // If EDITABLE and has a DATABASE
  if (EDITABLE === true && DATABASE === true) {
    $mysqli = new mysqli("127.0.0.1", "root", "", "design");
    $query = "SELECT project_data FROM projects WHERE project_url = '$r'";
    $results = $mysqli->query( $query );
    $rows = '';
    if ($results) {
      while ( $result = $results->fetch_assoc()) {
        $rows = $result['project_data'];
      }
      $results = json_decode($rows, true);
      // print_r($results);
      // return $results;
    }
  }
  
  // if (!empty($results)) {
  //   $results = json_decode($results, true);
  // } else {
  //   // $results = '';
  // }
  return $results;
}

// Gets the files as they exist in the DIR
function get_actual_files($kind){
  global $r, $u;
  $rootfiles['items'] = array(); // All files in the DIR
  $rootimages['items'] = array(); // All images in the DIR

  foreach (new DirectoryIterator($u) as $fn) {
    $size = $filesize = $fn->getSize();
    $name = $fn->getFilename();
    $path = $fn->getPath();
    $owner = posix_getpwuid(fileowner($path.'/'.$name));
    $type = $fn->getType();
    $ext = $fn->getExtension();
    $created = $fn->getATime();
    $modified = date("Y-m-d h:i:s", $fn->getMTime());
    $octal_perms = substr(sprintf('%o', $fn->getPerms()), -4);
    $file['size'] = $size;
    $file['name'] = $name;
    $file['owner'] = $owner['gecos'];
    $file['path'] = $path;
    $file['type'] = $type;
    $file['ext'] = $ext;
    $file['created'] = $created;
    $file['modified'] = $modified;
    $file['octal_perms'] = $octal_perms;
    $file['hidden'] = false;

    // Sorting the files based on type
    // All files
    if ($name != "." && $name != ".." && $name != ".DS_Store") {
      array_push($rootfiles['items'], $file);
    }
    // Images Only
    $img = array('jpg','jpeg','png','gif','PNG','JPG','GIF','svg');
    if ($type == 'file' && in_array($ext, $img)) {
      array_push($rootimages['items'], $file);
    }
  }
  if ($kind == 'files') {
    return $rootfiles['items']; // files
  }
  if ($kind == 'imgs') {
    return $rootimages['items']; // images
  }
}


// Get Files does two things
// — It gets all the files as they exist in the actual filesystem
// — And it gets all the files as they exist in the saved record (database or root.json file).
// - Then it attempts to compare them.
function get_files($project_data){
  global $r, $u, $dr, $uri;

  $rootfiles['items'] = get_actual_files('files'); // All files in the project
  $rootimages['items'] = get_actual_files('imgs'); // All images in the project
  $files['items'] = array();
  $newfiles['items'] = array();
  $images['items'] = array();

// Take the files that are in the record
// check to see that the file exists
// For those files that exist, push them to array
// Then look for the files that don't exist in the root and push those to the array as well.
  
  
  // If record doesn't exist
  if (empty($project_data)) {
    $files['items'] = $rootfiles['items'];
    $images['items'] = $rootimages['items'];
    // Hide '_thumbs' folder from the display
    foreach ($files['items'] as $key => $item) {
      if ($item['name'] === '_thumbs') {
        unset($files['items'][$key]);
      }
    }

  } else {
    // If record does exist
    $i = 0;
    foreach ($project_data['items'] as &$file) {
      $name = $file['name'];

      foreach ($rootfiles['items'] as $key => $item) {
        if ($item['name'] === $name) {
          unset($rootfiles['items'][$key]);
        }
      }

      $f = $dr.$file['slug'];
      
      if (file_exists($f)) {
        $ext = $file['ext'];
        $size = filesize($f);
        $owner = posix_getpwuid(fileowner($f));
        $path = $file['slug'];
        $created = filectime($f);
        $modified = date("Y-m-d h:i:s", filemtime($f));
        $octal_perms = substr(sprintf('%o', fileperms($f)), -4);

        $file['size'] = $size;
        $file['owner'] = $owner['gecos'];
        $file['path'] = $path;
        $file['created'] = $created;
        $file['modified'] = $modified;
        $file['octal_perms'] = $octal_perms;
        array_push($files['items'], $file);
        if ($ext == 'img') {
          array_push($images['items'], $file);
        }
      }
    }
    // Merge the Arrays
    $files['items'] = array_merge($files['items'],$rootfiles['items']);
    $images['items'] = array_merge($images['items'],$rootimages['items']);

    // Hide '_thumbs' and 'root.json' files
    foreach ($files['items'] as $key => $item) {
      if ($item['name'] === 'root.json' || $item['name'] === '_thumbs') {
        unset($files['items'][$key]);
      }
    }

  }
  return array($files, $images);
}





// Check to see if the current folder exists in the database OR in the root.json file
$project_data = is_edited(); 
list($files,$images) = get_files($project_data);
if (!empty($project_data)) {
  $project_title = $project_data['title'];
  $project_desc = $project_data['desc'];
  $project_designer = $project_data['designer'];
  $project_updated = $project_data['updated'];
  if ($app_version == $project_data['version']) {
    $project_version = '<span class="version">version '.$project_data['version'].'</span>';
  } else {
    $project_version = '<span class="update_project">update project</span> <span class="version">version <span>'.$project_data['version'].'</span></span>';
  }
}




function get_image_names($images){
  $image_names['items'] = array();
  $i = 1;
  foreach ($images['items'] as $item) {
    // print_r($item);
    $img['name'] = $item['name'];
    $img['num'] = $i;
    $i++;
    array_push($image_names['items'], $img);
  }
  return $image_names;
}


// SEARCH
// =============================================
// http://stackoverflow.com/questions/2913768/glob-filesearch-question
// http://thephpeffect.com/recursive-glob-vs-recursive-directory-iterator/

function search(){
  global $search, $u;
  // print_r($search);
  $ite = new RecursiveDirectoryIterator($u);
  $bytestotal=0;
  $nbfiles=0;
  
  
  $files['items'] = array();
  $images['items'] = array();

  foreach (new RecursiveIteratorIterator($ite) as $filename=>$cur) {
    $str = (string)$cur;
    if(strpos($str, $search) !== false){

      $size = $filesize = $cur->getSize();
      $name = $cur->getFilename();
      $owner = $cur->getOwner();
      $path = $cur->getPath();
      $owner = posix_getpwuid(fileowner($path.'/'.$name));
      $type = $cur->getType();
      $ext = $cur->getExtension();
      $created = $cur->getATime();
      $modified = $cur->getMTime();
      $octal_perms = substr(sprintf('%o', $cur->getPerms()), -4);

      $file['size'] = $size;
      $file['name'] = $name;
      $file['owner'] = $owner['gecos'];
      $file['path'] = $path;
      $file['type'] = $type;
      $file['ext'] = $ext;
      $file['created'] = $created;
      $file['modified'] = $modified;
      $file['octal_perms'] = $octal_perms;
      $file['hidden'] = false;
      // // print_r($file);
      // 
      // foreach ($file as $f) {
      //   // print_r($file);
      //   // $files['items'] = $file;
      //   // array_push($files['items'], $file);
      // }
      // $files['items'] = $file;
      array_push($files['items'], $file);
      
      $img = array('jpg','jpeg','png','gif');
      if ($type == 'file' && in_array($ext, $img)) {
        array_push($images['items'], $file);
      }
      
      // $bytestotal+=$filesize;
      // $nbfiles++;
      
      
      // echo "$filename => $filesize" . ' <br />';
    }

  }
  
  // $bytestotal=number_format($bytestotal);
  // $files['items']['bytes'] = $bytestotal;
  // $files['items']['nbfiles'] = $nbfiles;
  // print_r($files);
  return array($files, $images);
  
}


// OLD
// if (isset($b)) {
//   foreach($b as $key => $val){
//     foreach ($val as $k => $v) {
//       if (file_exists($u . $v['name'])) {
//         // echo $v['name']." exists <br />";
//         if(($oo = array_search($v['name'], $a['items'])) !== false) {
//           // print_r($a['items'][$oo]);
//           unset($a['items'][$oo]);
//         }
//       } else {
//         // echo "<strong>".$v['name']."</strong> does not exist";
//         // echo '<br />';
//       }
//     }
//   }
//   // print_r($a);
// }

?>