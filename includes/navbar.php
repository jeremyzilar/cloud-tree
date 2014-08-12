<nav class="navbar navbar-default" role="navigation">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="http://localhost/"><img src="<?php echo $windexPath; ?>/img/nytimes20.png" alt="The New York Times">Design</a>
    </div>

    <ul class="auth nav navbar-nav navbar-right">
      <li><a class="btn btn-xs btn-default" href="#">log in</a></li>
    </ul>    
      </div>
    </div>
    

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse">
      <ul class="slideshow-controls nav navbar-nav navbar-left">
        <li><a class="slide-prev" href="#"><i class="fa fa-arrow-left"></i></a></li>
        <li><a class="slide-next" href="#"><i class="fa fa-arrow-right"></i></a></li>
      </ul>
      <ul class="slideshow-tools nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php get_image_dropdown(); ?>
            <li class="divider"></li>
            <li class="grid-view"><a href="#"><i class="fa fa-th"></i> Grid View</a></li>
          </ul>
        </li>

        <li class="zoom-msg zoom"><a href=""></a></li>
        <li class="img_reset"><a href=""><i class="fa fa-bullseye"></i></a></li>
        <li class="zoom-in zoom"><a href="#" title="Zoom +"><i class="fa fa-plus-circle"></i></a></li>

        <li class="zoom-out zoom"><a href="#" title="Zoom +"><i class="fa fa-minus-circle"></i></a></li>
        <?php if (!empty($images['items'])) { ?>
        <li data-dropper="slideshow" class="show-slideshow"><a href="#show" title=""><i class="fa fa-bullseye"></i></a></li>
        <?php } ?>
        <li class="trigger_close"><a href="#" title="Close"><i class="fa fa-times"></i></a></li>
      </ul>

    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>