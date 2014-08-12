<?php 
  $readmeMarkup = '';
  $currentDir = $_SERVER["DOCUMENT_ROOT"] . $uri . '/';
  if (is_dir($currentDir)) {
    if ($dh = opendir($currentDir)) {
      while (($file = readdir($dh)) !== false) {
        // go thru files, find the first README.*
        if( preg_match('/^README(\.[A-z0-9]+)?$/i', $file) && !is_dir($currentDir.$file) ) {
          $readmeFile = $file;
          break;
        }
      }
      closedir($dh);
    }
    
    if (isset($readmeFile)) {
      $fileInfo = pathinfo($readmeFile);
      $ext = isset($fileInfo['extension']) ? $fileInfo['extension'] : '';
      $readmeRaw = file_get_contents($currentDir.$readmeFile);
  
      if ($ext == 'textile') {
        require_once( $_SERVER["DOCUMENT_ROOT"]. $windexPath . '/textile.php');
        $textile = new Textile();
        $readmeContent = $textile->TextileThis($readmeRaw);
      } else if ($ext == 'markdown' || $ext == 'md' || $ext == 'mdown') {
        require_once( $_SERVER["DOCUMENT_ROOT"]. $windexPath . '/functions/markdown.php');
        $readmeContent = Markdown($readmeRaw);
      } else if($ext == 'html' || $ext == 'htm') {
        $readmeContent = $readmeRaw;
      } else {
        $readmeContent = '<pre>'."\n".$readmeRaw."\n".'</pre>';
      }
  
      $readmeMarkup = '<div class="">'."\n"
        .'<h2 class="readme-title"><a href="'.$readmeFile.'">'.$readmeFile.'</a></h2>'
        ."\n".$readmeContent."\n".'</div>'
      ;
    }
  }
?>