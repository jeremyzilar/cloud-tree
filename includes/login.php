<?php 
$username = '';
$login_msg = '';
if (isset($_COOKIE['designdev'])) {
  $username = $_COOKIE['designdev'];
} else {
	if (isset($_GET['login']) && $_GET['login'] === 'false') {
		$login_msg = 'OOPS!';
	}
}

// print_r($_COOKIE);
?>

<section id="login-bar">
  <div class="container">
    <div class="row">
      <form id="login" class="form-inline" method="post" role="form" action="<?php echo $windexPath . '/functions/_login.php' ?>">
        <div class="form-group">
          <label class="sr-only" for="login-username">username</label>
          <input type="text" name="username" class="form-control input-sm" id="login-username" placeholder="username">
        </div>
        <div class="form-group">
          <label class="sr-only" for="login-password">Password</label>
          <input type="password" name="password" class="form-control input-sm" id="login-password" placeholder="password" value="">
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" name="remember"> Remember me
          </label>
        </div>
        <button type="submit" name="submit" class="btn btn-sm btn-default">Log in</button>
        <p class="error-msg"><?php echo $login_msg; ?></p>
        <p class="msg">Ooops. It looks like you forgot something.</p>

      </form>
    </div>
  </div>
</section>