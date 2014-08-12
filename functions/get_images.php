<?php

function get_images(){
  global $r;
  global $images;
  global $project_title;

  $title_card = '';
  if (!empty($project_title)) {
    $title_card = 'title_card';
    $slide = '<h1>'.$project_title.'</h1>';
    $s = <<<EOF
    <div class="item active $title_card">
      $slide
    </div>
EOF;
    echo $s;
  }

  $i = 1;
  foreach ($images['items'] as $item) {
    $name = $item['name'];
    $slide = '<img draggable="true" src="'.$r . $name.'" alt="'.$name.'" title="'.$name.'"/>';
    $card = '';
    $active = '';
    if ($i == 1 && empty($project_title)){$active = 'active';};
    $s = <<<EOF
    <div class="item $active $title_card" data-slide="$i">
      $slide
    </div>
EOF;
    echo $s;
    $i++;
  }
}

function get_image_dropdown(){
  global $r;
  global $images;
  global $project_title;

  $i = 1;
  foreach ($images['items'] as $item) {
    $name = $item['name'];
    $active = '';
    $s = <<<EOF
    <li data-slide-to="$i"><a href="#$i"><span>$name</span></a></li>
EOF;
    echo $s;
    $i++;
  }
}

function get_image_indicators(){
  global $r;
  global $images;
  global $project_title;

  $i = 1;
  foreach ($images['items'] as $item) {
    $name = $item['name'];
    $active = '';
    $s = <<<EOF
    <li data-target="#slideshow" data-slide-to="$i"></li>
EOF;
    echo $s;
    $i++;
  }
}

?>