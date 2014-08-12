<?php 
  $titletext = str_replace("%DIR", $uri, $titleFormat). '/';

  // generate title path, with links to each parent folder
  $folders = explode('/',$uri);
  $folderCount = count($folders);
  $pathMarkup = '';
  foreach ($folders as $i => $folder) {
    $link = '';
    $backCount = $folderCount - $i -1;
    for ($j=0; $j < $backCount; $j++) { 
      $link .= '../';
    }
    $pathMarkup .= '<a href="'.$link.'">'.$folder.'/</a>';
  }    
  
  $pathMarkup = $pathMarkup;
  
  $h1text = str_replace("%DIR", $pathMarkup, $titleFormat);
?>