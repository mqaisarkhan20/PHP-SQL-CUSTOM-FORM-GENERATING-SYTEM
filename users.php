<?php 

require 'includes/config.php';

if (!isset($_SESSION['username'])) {
	header('Location: ' . URL);
	exit;
} else if ($_SESSION['user_role'] == 'normal') {
	header('Location: ' . URL . 'answers.php');
	exit;
}

if (isset($_POST['save_user'])) {
	$username = clean_input($_POST['username']);
	$password = clean_input($_POST['password']);

	$previous_user = $db->single_row("SELECT * FROM users WHERE username = '$username'");
	if (isset($previous_user['username'])) {
		$_SESSION['message'] = '<div class="alert alert-danger alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <strong>Error!</strong> User with same username already exist.
		</div>';

		header("Location: " . URL . "users.php");
		exit;
	} else {
		$data = Array(
			'username' => $username,
			'password' => password_hash($password, PASSWORD_BCRYPT, array('cost' => 14)),
			'role' => 'normal',
			'datetime' => date("Y-m-d H:i:s")
		);

		if ($db->insert('users', $data)) {
			$_SESSION['message'] = '<div class="alert alert-success alert-dismissible">
			  <button type="button" class="close" data-dismiss="alert">&times;</button>
			  <strong>Success!</strong> Question saved successfully.
			</div>';

			header("Location: " . URL . "users.php");
			exit;
		}
	}
}

if (isset($_POST['update_user'])) {
	$user_id = clean_input($_POST['user_id']);
	$username = clean_input($_POST['username']);
	$password = clean_input($_POST['password']);

	$previous_user = $db->single_row("SELECT * FROM users WHERE username = '$username' AND id != $user_id");
	if (isset($previous_user['username'])) {
		$_SESSION['message'] = '<div class="alert alert-danger alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <strong>Error!</strong> User with same username already exist.
		</div>';

		header("Location: " . URL . "users.php");
		exit;
	} else {
		$data = Array(
			'username' => $username,
			'password' => password_hash($password, PASSWORD_BCRYPT, array('cost' => 14)),
			'datetime' => date("Y-m-d H:i:s")
		);
		$condition = Array(
			'id' => $user_id
		);

		if ($db->update('users', $data, $condition)) {
			$_SESSION['message'] = '<div class="alert alert-success alert-dismissible">
			  <button type="button" class="close" data-dismiss="alert">&times;</button>
			  <strong>Success!</strong> Question updated successfully.
			</div>';

			header("Location: " . URL . "users.php");
			exit;
		}
	}
}

if (isset($_GET['delete_id'])) {
	$user_id = clean_input($_GET['delete_id']);
	$condition = Array(
		'id' => $user_id
	);

	if ($db->delete('users', $condition)) {
		$_SESSION['message'] = '<div class="alert alert-success alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <strong>Success!</strong> User deleted successfully.
		</div>';

		header("Location: " . URL . "users.php");
		exit;
	}
}

$users = $db->multiple_row("SELECT * FROM users WHERE role = 'normal'");

$html_title = 'Users';
$nav_active = 'users';

require 'includes/header.php';
require 'includes/navigation.php';

?>


<div class="container pt-4">
	<img src="includes/header.jpg" alt="" class="header_image">
	<div class="row">
		<div class="col-md-4">
			<?= isset($_SESSION['message']) ? $_SESSION['message']: false; ?>
			<?php unset($_SESSION['message']); ?>
			<h1>New user:</h1>
			<?= (isset($_SESSION['message'])) ? $_SESSION['message']: false; ?>
			<?php unset($_SESSION['message']); ?>
			<form action="" method="POST" name="save_user">
				<div class="form-group">
					<label for="">Username:</label>
					<input type="text" name="username" class="form-control">
				</div>

				<div class="form-group">
					<label for="">Password:</label>
					<input type="password" name="password" class="form-control">
				</div>

				<div class="form-group">
					<label for="">Confirm password:</label>
					<input type="password" name="confirm_password" class="form-control">
				</div>


				<div class="form-group">
					<input type="submit" name="save_user" class="btn btn-primary btn-sm" value="Save">
				</div>
			</form>
		</div>
	</div>

	<hr>

  <h1>Users:</h1>
  <table class="table table-bordered">
  	<thead>
  		<tr>
  			<th>Username</th>
  			<th>Datetime</th>
  			<th>Action</th>
  		</tr>
  	</thead>

  	<tbody>
  		<?php if (count($users) > 0): ?>
  		<?php foreach ($users as $user): ?>
			<tr>
				<td><?= $user['username'] ?></td>
				<td><?= $user['datetime']->format('Y-m-d H:i:s') ?></td>
  			<td>
  				<a onclick="return confirm('Are you sure?');" href="<?= URL . 'users.php?delete_id='. $user['id'] ?>">Delete</a>
  				<span data-id="<?= $user['id'] ?>" class="text-success cursor_pointer edit">Edit</span></td>

  		</tr>
  		<?php endforeach; ?>
			<?php else: ?>
			<tr><td colspan="2"><i>No user saved yet!</i></td></tr>
			<?php endif; ?>
  	</tbody>
  </table>

<!-- The Modal -->
<div class="modal" id="update_user_modal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Update form:</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
</div>

<script>
$(document).ready(function() {
	$('form[name="save_user"]').submit(function(e) {
		$('.alert').remove();
		var username = ($('input[name="username"]').val()).trim();
		var password = ($('input[name="password"]').val()).trim();
		var confirm_password = ($('input[name="confirm_password"]').val()).trim();

		if (username == '' || password == '' || confirm_password == '') {
			e.preventDefault();
			var message = $(`<div class="alert alert-danger alert-dismissible" style="display: none;">
			  <button type="button" class="close" data-dismiss="alert">&times;</button>
			  <strong>Error!</strong> All fields are required.
			</div>`);
			$('form[name="save_user"]').prepend(message);
			$(message).fadeIn();
		} else if (password != confirm_password) {
			e.preventDefault();
			var message = $(`<div class="alert alert-danger alert-dismissible" style="display: none;">
			  <button type="button" class="close" data-dismiss="alert">&times;</button>
			  <strong>Error!</strong> Incorrect confirm password.
			</div>`);
			$('form[name="save_user"]').prepend(message);
			$(message).fadeIn();
		}
	});

	$('.edit').click(function() {
		var id = $(this).attr('data-id');
		var username = $(this).parent().prev().prev().text();

		var form_body = $('#update_user_modal').find('.modal-body');
		$('form[name="update_user"]').remove();
		$(form_body).html(`
			<form action="" method="POST" name="update_user">
				<input type="hidden" name="user_id" value="${id}">
				<div class="form-group">
					<label for="">Username:</label>
					<input type="text" name="username" class="form-control" value="${username}">
				</div>

				<div class="form-group">
					<label for="">Password:</label>
					<input type="password" name="password" class="form-control" value="">
				</div>

				<div class="form-group">
					<label for="">Confirm password:</label>
					<input type="password" name="confirm_password" class="form-control" value="">
				</div>

				<div class="form-group">
					<input type="submit" name="update_user" class="btn btn-primary btn-sm" value="Update">
				</div>
			</form>
			`);
		$('form[name="update_user"]').submit(function(e) {
			$('.alert').remove();
			var username = ($('form[name="update_user"]').find('input[name="username"]').val()).trim();
			var password = ($('form[name="update_user"]').find('input[name="password"]').val()).trim();
			var confirm_password = ($('form[name="update_user"]').find('input[name="confirm_password"]').val()).trim();

			if (username == '' || password == '' || confirm_password == '') {
				e.preventDefault();
				var message = $(`<div class="alert alert-danger alert-dismissible" style="display: none;">
				  <button type="button" class="close" data-dismiss="alert">&times;</button>
				  <strong>Error!</strong> All fields are required.
				</div>`);
				$('form[name="update_user"]').prepend(message);
				$(message).fadeIn();
			} else if (password != confirm_password) {
				e.preventDefault();
				var message = $(`<div class="alert alert-danger alert-dismissible" style="display: none;">
				  <button type="button" class="close" data-dismiss="alert">&times;</button>
				  <strong>Error!</strong> Incorrect confirm password.
				</div>`);
				$('form[name="update_user"]').prepend(message);
				$(message).fadeIn();
			}
		});
		$('#update_user_modal').modal();
	});
});
</script>
</body>
</html>