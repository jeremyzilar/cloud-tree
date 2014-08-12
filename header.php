<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Cloud Tree</title>

  <!-- Fonts: Typekit -->
  <script type="text/javascript" src="//use.typekit.net/nug1zni.js"></script>
  <script type="text/javascript">try{Typekit.load();}catch(e){}</script>

  <?php wp_head() ?>
</head>

<body class="" data-version="<?php echo $app_version; ?>" data-base="<?php echo DATABASE ? 'true' : 'false'; ?>" editable="<?php echo EDITABLE ? 'true' : 'false'; ?>" logging="<?php echo EDITABLE ? 'true' : 'false'; ?>" auth="<?php echo LOGGEDIN ? 'true' : 'false'; ?>" user="<?php echo $username; ?>">

  <?php
    include TDIR . 'login.php';
    include TDIR . 'navbar.php';
    include TDIR . 'slideshow.php';
    //Search
    if (DATABASE === true) {
      include('search.php');
    }
  ?>

  <section id="main" class="">
    <div class="container">

      <!-- PING — Message Bar -->
      <div class="row ping">
        <p><i class="fa fa-refresh fa-spin"></i> <span></span></p>
      </div>

      <!-- Page Head -->
      <div id="page-head" class="row">
        <div class="">
          <h2 placeholder="Add Title"><?php echo $project_title; ?></h2>
        </div>
      </div>
      <!-- end #page-head -->

      <div class="row">
        <div class="col-xs-12">

          <?php

            // Breadcrumb Navigation
            include TDIR . 'breadcrumb-nav.php';

            // File Upload
            include TDIR . 'file-upload.php';

            // Edit Tools
            if (EDITABLE === true) {
              include TDIR . 'tools.php';
            }

            // Files / Table
            include TDIR . 'show_files.php';

          ?>

        </div> <!-- end .col-xs-12 -->
      </div> <!-- end .row -->