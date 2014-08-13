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
        <p><?php echo VERSION; ?></p>
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


</body>
</html>
