<?php 

require 'includes/config.php';

if (!isset($_SESSION['username'])) {
	header('Location: ' . URL);
	exit;
}
//  else if ($_SESSION['user_role'] == 'normal') {
// 	header('Location: ' . URL . 'answers.php');
// 	exit;
// }


// $users = $db->multiple_row("SELECT * FROM users WHERE role = 'normal'");
// $forms = $db->multiple_row("SELECT * FROM forms");

if (isset($_GET['answer_id'])) {
	$answer_id = clean_input($_GET['answer_id']);
}

$html_title = 'Form';
$nav_active = 'answers';

require 'includes/header.php';
require 'includes/nu_navigation.php';



// HIER EEN SUBMITTED_ANSWRS van maken
	$d_form_ids = $db->multiple_row("SELECT DISTINCT form_v_id FROM answers WHERE user_id = $_SESSION[user_id]");
	$submitted_forms = Array();
	foreach ($d_form_ids as $form_id) {
		$data = $db->single_row("SELECT answers.*,forms.name FROM answers RIGHT JOIN forms ON forms.id = answers.form_id WHERE answers.form_v_id = '$form_id[form_v_id]'");
		array_push($submitted_forms, $data);
	}
	?>

	<div class="row">
		<div class="col-md-12">
			<h3>Submitted forms:</h3>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Form vid</th>
						<th>Form name</th>
						<th>Datetime</th>
						<th>Action</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach($submitted_forms as $form): ?>
						<tr>
							<td><?= $form['form_v_id']; ?></td>
							<td><?= $form['name']; ?></td>
							<td><?= $form['datetime']->format('Y-m-d H:i:s'); ?></td>
							<td>
								<a href="<?= URL ?>show_answers.php?answer_id=<?= $form['id'] ?>">show</a>
								<a href="<?= URL ?>edit_answers.php?answer_id=<?= $form['id'] ?>">edit</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
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
	$('select[name="form_id"]').change(function() {
		var form_id = ($(this).val()).trim();

		if (form_id != '') {
			$('form[name="select_form"]').submit();
		}
	});

	function readURL(input) {
		var image_preview = $(input).parent().parent().find('.image_preview');
		$(image_preview).show();
		if (input.files && input.files[0]) {
		  var reader = new FileReader();
		  
		  reader.onload = function (e) {
				$(image_preview).attr('src', e.target.result);
		  }
		  
		  reader.readAsDataURL(input.files[0]);
		}
  }

	$('.custom-file-input').change(function() {
		readURL(this);
		var fileName = $(this).val();
		// $(this).parent().after(`<i class="fas fa-check ml-2"></i>`);
	});

	<?php if (isset($questions) && count($questions) > 0): ?>
	$('form[name="questions_form"]').submit(function(e) {
		$('.alert').remove();

		var project_number = ($('input[name="project_number"]').val()).trim();
		var bwkng_el_value = $('select[name="bewerking"]').val();
		var bwkng_error = typeof bwkng_el_value !== undefined && bwkng_el_value == '';

		var questions = 
		<?php
		echo "'";
			foreach($questions as $key => $question):
				$key++;
				echo ($key != count($questions)) ? "input:radio[name=ans_$question[id]]:checked,": "input:radio[name=ans_$question[id]]:checked";
			endforeach;
		echo "';";
		?>

		if (project_number.length != 6) {
			e.preventDefault();
			var message = $(`<div class="alert alert-danger alert-dismissible col-8" style="display: none;">
			  <button type="button" class="close" data-dismiss="alert">&times;</button>
			  <strong>Error!</strong> Project number must have 6 characters.
			</div>`);
			$('form[name="questions_form"]').prepend(message);
			$(message).fadeIn();
		} else if ($(questions).length != <?= count($questions) ?> || project_number == '' || bwkng_error) { //  //
			e.preventDefault();
			var message = $(`<div class="alert alert-danger alert-dismissible col-8" style="display: none;">
			  <button type="button" class="close" data-dismiss="alert">&times;</button>
			  <strong>Error!</strong> All fields are required.
			</div>`);
			$('form[name="questions_form"]').prepend(message);
			$(message).fadeIn();
		}
	});
	<?php endif; ?>
});
</script>
</body>
</html>