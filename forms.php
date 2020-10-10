<?php 

require 'includes/config.php';

if (!isset($_SESSION['username'])) {
	header('Location: ' . URL);
	exit;
} else if ($_SESSION['user_role'] == 'normal') {
	header('Location: ' . URL . 'answers.php');
	exit;
}

if (isset($_POST['save_form'])) {
	$form_name = clean_input($_POST['form_name']);
	$header_op = clean_input($_POST['header_op']);
	$footer_op = clean_input($_POST['footer_op']);
	$data = Array(
		'name' => $form_name,
		'header_op' => $header_op,
		'footer_op' => $footer_op,
		'datetime' => date("Y-m-d H:i:s")
	);

	if ($db->insert('forms', $data)) {
		$_SESSION['message'] = '<div class="alert alert-success alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <strong>Success!</strong> Form saved successfully.
		</div>';

		header("Location: " . URL . "forms.php");
		exit;
	}
}

if (isset($_POST['update_form'])) {
	$form_id = clean_input($_POST['form_id']);
	$form_name = clean_input($_POST['form_name']);
	$data = Array(
		'name' => $form_name,
		'header_op' => $header_op,
		'footer_op' => $footer_op,
		'datetime' => date("Y-m-d H:i:s")
	);
	$condition = Array(
		'id' => $form_id
	);

	if ($db->update('forms', $data, $condition)) {
		$_SESSION['message'] = '<div class="alert alert-success alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <strong>Success!</strong> Form updated successfully.
		</div>';

		header("Location: " . URL . "forms.php");
		exit;
	}
}

if (isset($_GET['delete_id'])) {
	$form_id = clean_input($_GET['delete_id']);
	$condition = Array(
		'id' => $form_id
	);

	if ($db->delete('forms', $condition)) {
		$_SESSION['message'] = '<div class="alert alert-success alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <strong>Success!</strong> Form deleted successfully.
		</div>';

		header("Location: " . URL . "forms.php");
		exit;
	}
}

$forms = $db->multiple_row("SELECT * FROM forms");

$html_title = 'Forms';
$nav_active = 'forms';

require 'includes/header.php';
require 'includes/navigation.php';

?>


<div class="container pt-4">
	<img src="includes/header.jpg" alt="" class="header_image">
	<div class="row">
		<div class="col-md-5">
			<?= isset($_SESSION['message']) ? $_SESSION['message']: false; ?>
			<?php unset($_SESSION['message']); ?>
			<h1>New form:</h1>
			<form action="" method="POST" name="save_form">
				<div class="form-group">
					<label for="">Name:</label>
					<input type="text" name="form_name" class="form-control">
				</div>
				
				<div class="form-group">
					<label for="">Header option:</label><br>
					<div class="custom-control custom-radio custom-control-inline col-5">
				    <input type="radio" class="custom-control-input" id="yes1007" name="header_op" value="1">
				    <label class="custom-control-label" for="yes1007">Header 1  <br><i>with dropdown</i> </label>
				    
				  </div>
				  <div class="custom-control custom-radio custom-control-inline col-5">
				    <input type="radio" class="custom-control-input" id="no1007" name="header_op" value="2">
				    <label class="custom-control-label" for="no1007">Header 2 <br><i>no dropdown</i> </label>
				  </div>
				
				</div>
				<div class="form-group">
					<label for="">Footer option:</label><br>
					
					<div class="custom-control custom-radio custom-control-inline col-5">
				    <input type="radio" class="custom-control-input" id="yesfoo1" name="footer_op" value="1">
				    <label class="custom-control-label" for="yesfoo1">Footer 1  <br><i>no pictures</i> </label>
				    </div>

				  <div class="custom-control custom-radio custom-control-inline col-5">
				    <input type="radio" class="custom-control-input" id="yesfoo2" name="footer_op" value="2">
				    <label class="custom-control-label" for="yesfoo2">Footer 2 <br><i>multiple pictures</i> </label>
				  </div>

				  <div class="custom-control custom-radio custom-control-inline col-5">
				    <input type="radio" class="custom-control-input" id="yesfoo3" name="footer_op" value="3">
				    <label class="custom-control-label" for="yesfoo3">Footer 3 <br><i>pictures for eind controle form</i> </label>
				  </div>
				</div>			

				<div class="form-group">
					<input type="submit" name="save_form" class="btn btn-primary btn-sm" value="Save">
				</div>
			</form>
		</div>
	</div>

	<hr>

  <h1>Forms</h1>
  <table class="table table-bordered">
  	<thead>
  		<tr>
  			<th>Name</th>
  			<th>Header option</th>
			<th>Footer option </th>
  			<th>Datetime</th>
  			<th>Action</th>
  		</tr>
  	</thead>

  	<tbody>
  		<?php if (count($forms) > 0): ?>
  		<?php foreach ($forms as $form): ?>
			<tr>
  			<td class="td_fn"><?= $form['name'] ?></td>
  			<td class="td_ho"><?= $form['header_op'] ?></td>
			<td class="td_fo"><?= $form['footer_op'] ?></td>
  			<td><?= ($form['datetime'] != NULL) ? $form['datetime']->format('Y-m-d H:i:s'): false; ?></td>
  			<td>
  				<a onclick="return confirm('Are you sure?');" href="<?= URL . 'forms.php?delete_id='. $form['id'] ?>">Delete</a>
  				<span data-id="<?= $form['id'] ?>" class="text-success cursor_pointer edit">Edit</span></td>

  		</tr>
  		<?php endforeach; ?>
			<?php else: ?>
			<tr><td colspan="3"><i>No forms saved yet!</i></td></tr>
			<?php endif; ?>
  	</tbody>
  </table>

<!-- The Modal -->
<div class="modal" id="update_form_modal">
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
	$('form[name="save_form"]').submit(function(e) {
		$('.alert').remove();
		var form_name = ($('input[name="form_name"]').val()).trim();
		var header_op = $('input[name="header_op"]:checked');

		if (form_name == '' || header_op.length == 0) {
			e.preventDefault();
			var message = $(`<div class="alert alert-danger alert-dismissible" style="display: none;">
			  <button type="button" class="close" data-dismiss="alert">&times;</button>
			  <strong>Error!</strong> All fields are required!
			</div>`);
			$('form[name="save_form"]').prepend(message);
			$(message).fadeIn();
		}
	});

	$('.edit').click(function() {
		var id = $(this).attr('data-id');
		var form_id = $(this).attr('data-form-id');
		var header_op = $(this).closest('tr').find('td.td_ho').text();
		var form_name = $(this).closest('tr').find('td.td_fn').text();

		var form_body = $('#update_form_modal').find('.modal-body');
		$('form[name="update_form"]').remove();
		$(form_body).html(`
			<form action="" method="POST" name="update_form">
				<input type="hidden" name="form_id" value="${id}">
				<div class="form-group">
					<label for="">Name:</label>
					<input type="text" name="form_name" class="form-control" value="${form_name}">
				</div>

				<div class="form-group">
					<label for="">Header option:</label><br>
					<div class="custom-control custom-radio custom-control-inline col-5">
				    <input type="radio" class="custom-control-input" id="up1007" name="header_op" value="1" ${(header_op == 1) ? 'checked="checked"': false}>
				    <label class="custom-control-label" for="up1007">Header 1</label>
				  </div>
				  <div class="custom-control custom-radio custom-control-inline col-5">
				    <input type="radio" class="custom-control-input" id="ab1007" name="header_op" value="2" ${(header_op == 2) ? 'checked="checked"': false}>
				    <label class="custom-control-label" for="ab1007">Header 2</label>
				  </div>
				</div>

				<div class="form-group">
					<input type="submit" name="update_form" class="btn btn-primary btn-sm" value="Update">
				</div>
			</form>
			`);
		$('form[name="update_form"]').submit(function(e) {
			$('.alert').remove();
			var form_name = ($('form[name="update_form"]').find('input[name="form_name"]').val()).trim();
			var header_op = $('form[name="update_form"]').find('input[name="header_op"]:checked');

			if (form_name == '' || header_op.length == 0) {
				e.preventDefault();
				var message = $(`<div class="alert alert-danger alert-dismissible" style="display: none;">
				  <button type="button" class="close" data-dismiss="alert">&times;</button>
				  <strong>Error!</strong> All fields are required.
				</div>`);
				$('form[name="update_form"]').prepend(message);
				$(message).fadeIn();
			}
		});
		$('#update_form_modal').modal();
	});
});
</script>
</body>
</html>