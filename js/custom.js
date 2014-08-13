(function($){
var wh = $(window).height();

$(document).ready(function(){
  
  $body = $('body');
  $trigger_close = $('.trigger_close');
  
  // Relative Dates and Time using Moment.js ===========================================================================
  // http://momentjs.com/docs/#/displaying/format/
  function realTime(el){
    $(el).each(function(){
      var utc = $(this).text();
      var date = moment($(this).attr('title')).format('LLLL');
      moment(utc).format();

      var t = moment(utc).fromNow();
      moment.lang('en', {
        relativeTime : {
          future: "in %s",
          past:   "%s ago",
          s:  "secs",
          m:  "a min",
          mm: "%d mins",
          h:  "1 hour",
          hh: "%d hours",
          d:  "1 day",
          dd: "%d days",
          M:  "1 month",
          MM: "%d months",
          y:  "a year",
          yy: "%d years"
        }
      });
      $(this).html(t);
      $(this).attr('title', date);
    });
  };
  realTime('#log .log_time');
  realTime('#log_roll .log_time');
  realTime('.table .log_time');
  realTime('.edited_by .updated');
  
  
  $('#log_time').val(moment().format());
  $('.time').html(moment().format('LLLL'));
  


  // Main Table ===========================================================================
  // $('#main .table').show();
  $('#main table').last().remove();
  $('#main table tr.i').each(function(){
    $('td:nth-child(3n)').addClass('file');
  });
  
  // Tablesorter - http://tablesorter.com/docs/
  // $("#main .table").tablesorter();   

  
  // The Editing Buttons =================================================================
  
  $title = $('#page-head h2'); // Project Title
  $edit = $('.btn-edit'); // Edit button
  $done = $('.btn-done'); // Done Button
  $del = $('.delete'); // Delete file Button
  $hide_file = $('.hide_file'); // Hide File Button
  
  function edit(t){
    $(t).hide();
    $done.show();
    $edit.hide();
    $hide_file.show();
    $del.show();
    if ($title.text() == '') {
      $title.html('Add Title');
    }
    $title.attr('contenteditable','true').addClass('editable');
    var fixHelper = function(e, ui) {
    	ui.children().each(function() {
    		$(this).width($(this).width());
    	});
    	return ui;
    };
    $("#main table.table tbody").sortable({
      items: "tr.i",
      helper: fixHelper
      // out: function() {save()} // Save each time you sort
    });
  };
  
  function done(t){
    $(t).hide();
    $edit.show();
    $hide_file.hide();
    $del.hide();
    if ($title.text() == 'Add Title') {
      $title.html('');
    }
    $title.attr('contenteditable','false').removeClass('editable');
    $('#main table.table tbody').sortable("destroy");
    save();
  };
  
  // EDIT
  $edit.click(function(e) {
    e.preventDefault();
    edit(this);
  });
  // Done
  $done.click(function(e) {
    e.preventDefault();
    done(this);
  });
  // Hide File
  $(".hide_file i").click(function(e){
    e.preventDefault();
    if ($(this).closest('tr.i').hasClass('quiet')) {
      $(this).closest('tr.i').removeClass('quiet');
    } else {
      $(this).closest('tr.i').addClass('quiet');
    };
    save();
  });
   
  
  // Search & Queries ===========================================================================
  function queryValue() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++) {
      hash = hashes[i].split('=');
      vars.push(hash[0]);
      vars[hash[0]] = hash[1];
    }
    return vars;
  }
  
  // $search = $('#search');
  // var search = queryValue()["s"]; // Checks to see if there is a search query string
  // if (search === undefined) {
  //   $body.removeClass("open"); // if not, closes the dropper
  //   $search.hide();
  // } else {
  //   $('#search-box').attr('value', search);
  //   $search.show('fast',function() {
  //     $body.addClass("open"); // if there is, it opens the dropper
  //     $trigger_close.show();
  //   });
  // };
  
  function clear_queries(){
    if(typeof search != 'undefined') {
      console.log(search);
      setTimeout(function(){
        window.location.href = window.location.href.split('?')[0];
      }, 200);
      // window.location.href = window.location.href.split('?')[0];
    } 
  };
  

  // Login Bar ===========================================================================

  function get_login_state(){
    var state = $('body').attr('auth');
    return state;
  }

  // Get Auth State
  $auth_state = get_login_state();
  $auth_link = $('.auth li a');
  $login_bar = $('#login-bar');

  // If logged out
  if ($auth_state == 'false') {
    $auth_link.click(function(e) {
      e.preventDefault();
      if ($login_bar.hasClass('open')) {
        $login_bar.animate({
          marginTop: '-75',
        }, 'fast');
        $login_bar.removeClass('open');
        $auth_link.html('log in');
      } else {
        $login_bar.animate({
          marginTop: '0',
        }, 'fast');
        $login_bar.addClass('open');
        $auth_link.html('<i class="fa fa-times-circle"></i> close');
      }
    });
  // If Logged in
  } else {
    $auth_link.html('Log out');
    $auth_link.click(function(e) {
      // Delete the Cookie
      document.cookie = 'designdev=;Path=/;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
      $auth_link.html('Log in');
      $('body').attr('auth','false');
      $('body').attr('user','');
    });
  }

  // Login Form
  $( "#login .btn" ).click(function(e){
    var u = $('#login-username').val();
    var p = $('#login-password').val();
    if (u == '' || p == '') {
      e.preventDefault();
      $("#login .msg").animate({
        opacity: '1',
      }, 'fast', function() {
        // Animation complete.
      });
    };
  });



  // var log_state = $('body').attr('logged-in');
  // if (log_state == 'true') {
  //   $('.login').addClass('logged-in').removeClass('logged-out').find('a').text('Log out');
  // } else {
  //   // $('.login').addClass('logged-out').removeClass('logged-out').find('a').text('Log out');
  // }


 
  // $("#login").submit();

  // $('.login a.login-open').click(function(e) {
  //   e.preventDefault();
  //   console.log('123');
  //   $('#login-bar').animate({
  //     marginTop: '-75'
  //   }, 'fast', function() {
  //   });
  //   $(this).text('Login').removeClass('login-open');
  // });
  
  
  
  

 });
})(jQuery);