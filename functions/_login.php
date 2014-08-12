<?php
  
	// The list of people who are authorized to edit
  include 'auth.php';

	 // Check the Approved List
  function is_user_approved($user, $pass){
    global $approved_users;
    if (in_array($user, $approved_users)) {
      $ldap = check_ldap($user, $pass);
      return true;
    } else {
      return false;
    }
  }

  // Check LDAP
  function check_ldap($user, $pass){
    return true;
  }

  $username = $_POST['username'];
  // $password = $_POST['password'];

  if (isset($_POST['username']) && !empty($_POST['username'])) {

    $user = is_user_approved($username);
    if ($user === true) {
	    if (isset($_POST['remember'])) {
	      /* Set cookie to last 30 days */
	      // echo "AAA";
	      setcookie('designdev', $_POST['username'], time()+60*60*24*30, '/'); // was 1 year — 60*60*24*365
	    
	    } else {
	    	// echo "BBB";
	      /* Cookie expires when browser closes */
	      setcookie('designdev', $_POST['username'], false, '/');
	    }
	    // die(print_r($_SERVER));
	    header('Location:' . $_SERVER['HTTP_REFERER']);
    } else {
    	header('Location:' . $_SERVER['HTTP_REFERER'] . '?login=false');
    }

  }

?>