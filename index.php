<?php 

require 'includes/config.php';

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
  header('Location: ' . URL . 'forms.php');
  exit;
} else if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'normal') {
  header('Location: ' . URL . 'answers.php');
  exit;
}

if (isset($_POST['login'])) {
  $username = clean_input($_POST['username']);
  $password = clean_input($_POST['password']);

  $user = $db->single_row("SELECT * FROM users WHERE username = '$username'");
  if (isset($user['username']) && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_role'] = $user['role'];

    header("Location: " . URL . "forms.php");
    exit;
  } else {
    $_SESSION['message'] = '<div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <strong>Error!</strong> Wrong username or password.
    </div>';

    header("Location: " . URL);
    exit;
  }
}

$html_title = 'Login';
require 'includes/header.php';

?>
<div class="container pt-4">
  <img src="includes/header.jpg" alt="" class="header_image">
  <div class="row d-flex justify-content-center">
    <div class="col-md-4">
      <h3 class="text-center">Login:</h3>
      <?= (isset($_SESSION['message'])) ? $_SESSION['message']: false; ?>
      <?php unset($_SESSION['message']); ?>

      <form action="" method="POST" name="login_form">
        <div class="form-group">
          <label for="">Username:</label>
          <input type="text" name="username" class="form-control">
        </div>

        <div class="form-group">
          <label for="">Password:</label>
          <input type="password" name="password" class="form-control">
        </div>

        <div class="form-group">
          <input type="submit" name="login" class="btn btn-primary btn-sm" value="Login">
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  $('form[name="login_form"]').submit(function(e) {
    $('.alert').remove();
    var username = ($('input[name="username"]').val()).trim();
    var password = ($('input[name="password"]').val()).trim();

    if (username == '' || password == '') {
      e.preventDefault();
      var message = $(`<div class="alert alert-danger alert-dismissible" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Error!</strong> Enter username and password.
      </div>`);
      $('form[name="login_form"]').prepend(message);
      $(message).fadeIn();
    }
  });

  $('.edit').click(function() {
  });
});
</script>
</body>
</html>