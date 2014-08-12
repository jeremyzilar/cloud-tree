  </div> <!-- #container -->
</section> <!-- #main -->

<!-- Show Log Roll -->
<?php 
  // Log Roll
  if (EDITABLE === true && DATABASE === true) {
    include TDIR . 'show_logroll.php';
  }

// Show ReadMe
require_once 'functions/readme.php';
if (!empty($readmeMarkup)) { ?>
<section id="readme" class="hiddens">
  <div class="container">
    <?php echo $readmeMarkup; ?>
  </div>
</section>
<?php }

?>


<!-- Footer -->
<section id="footer">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <p><?php echo $org_name . ' / ' . $app_name . ' / ' . $app_version; ?></p>
      </div>
    </div>
  </div>
</section>


<!-- JS -->
<script type="text/javascript" charset="utf-8">
  // Get the current path. Essential for saving path to database.
  var path = window.location.pathname; 
  var designer = 'Jeremy Zilar';
  var designer_ldap = 'zilarjd';
</script>

<!-- jQuery -->
<script src="http://code.jquery.com/jquery-1.10.0.min.js" type="text/javascript" charset="utf-8"></script>
<!-- jQuery UI -->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<!-- Bootstrap JS -->
<script src="<?php echo $windexPath; ?>/js/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>
<!-- Tablesorter -->
<script src="<?php echo $windexPath; ?>/js/jquery.tablesorter.min.js" type="text/javascript" charset="utf-8"></script>
<!-- Relative Dates/times -->
<script src="<?php echo $windexPath; ?>/js/moment.min.js" type="text/javascript" charset="utf-8"></script>
<!-- Slideshow JS -->
<script src="<?php echo $windexPath; ?>/js/slideshow.js" type="text/javascript" charset="utf-8"></script>
<!-- Custom JS -->
<script src="<?php echo $windexPath; ?>/js/custom.js?=120" type="text/javascript" charset="utf-8"></script>


<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
  <tr class="template-upload fade">
    <td class="hide_file"><i class="fa fa-eye"></i></td>
    <td valign="top" class="icon file" data-ext="new"><a href="{%=file.url%}"><img src="/windex/icons/new.png" alt="new" width="24" height="24"></a></td>
    <td>
      <p class="name">{%=file.name%}</p>
      <strong class="error text-danger"></strong>
    </td>
    <td>
      <p class="size">Processing...</p>
      <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
    </td>
    <td class="hidden">
      {% if (!i && !o.options.autoUpload) { %}
        <button class="btn btn-primary start" disabled>
          <i class="glyphicon glyphicon-upload"></i>
          <span>Start</span>
        </button>
      {% } %}
      {% if (!i) { %}
          <button class="btn btn-warning cancel">
            <i class="glyphicon glyphicon-ban-circle"></i>
            <span>Cancel</span>
          </button>
      {% } %}
    </td>
  </tr>
{% } %}
</script>

<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
  <tr class="i new-file fade">
    <td class="hide_file"><i class="fa fa-eye"></i></td>
    <td valign="top" class="icon file" data-ext="new"><a href="{%=file.url%}"><img src="/windex/icons/new.png" alt="new" width="24" height="24"></a></td>
    <td class="file">
      <a href="{%=file.url%}">{%=file.name%}</a>
      {% if (file.error) { %}
        <div><span class="label label-danger">Error</span> {%=file.error%}</div>
      {% } %}
    </td>
    <td class="modified">{%=o.formatFileSize(file.size)%}</td>
    <td class="action download"><a href="<?php echo $r; ?>{%=file.name%}" download="{%=file.name%}"><i class="fa fa-download"></i></a></td>
    <td class="action delete" data-url="<?php echo $r; ?>"><i class="fa fa-trash-o"></i></td>
  </tr>
{% } %}
</script>

<!-- File Upload JS -->
<script type="text/javascript">
  var handler =  '<?php echo $windexPath; ?>/server/php/';
  var custom_path = '<?php echo $_SERVER["DOCUMENT_ROOT"]; echo $uri; ?>/';
  // var custom_path = 'http://localhost<?php echo $uri; ?>/';
  var local_path = '<?php echo $uri; ?>/';

  // console.log(handler);
  // console.log(custom_path);
</script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="http://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="http://blueimp.github.io/JavaScript-Load-Image/js/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="http://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo $windexPath; ?>/js/upload/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo $windexPath; ?>/js/upload/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="<?php echo $windexPath; ?>/js/upload/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="<?php echo $windexPath; ?>/js/upload/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="<?php echo $windexPath; ?>/js/upload/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="<?php echo $windexPath; ?>/js/upload/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin. Ignore for now -->
<!-- <script src="<?php echo $windexPath; ?>/js/upload/jquery.fileupload-validate.js"></script> -->
<!-- The File Upload user interface plugin -->
<script src="<?php echo $windexPath; ?>/js/upload/jquery.fileupload-ui.js"></script>
<!-- The main application script. Apparently this is indeed being used, not just an example. Check why. -->
<script src="<?php echo $windexPath; ?>/js/upload/main.js"></script>
<!-- An extension to File Upload to support empty folders -->
<script src="<?php echo $windexPath; ?>/js/jquery.fileupload.foldersupport.js"></script>
<!-- The custom upload scripts -->
<script src="<?php echo $windexPath; ?>/js/upload.js"></script>
<!-- Custom actions scripts -->
<script src="<?php echo $windexPath; ?>/js/actions.js"></script>


<!-- Log Actions -->
<script src="<?php echo $windexPath; ?>/js/log.js" type="text/javascript" charset="utf-8"></script>
<!-- Save Project -->
<script src="<?php echo $windexPath; ?>/js/project.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo $windexPath; ?>/js/reset.js" type="text/javascript" charset="utf-8"></script>

</body>
</html>
