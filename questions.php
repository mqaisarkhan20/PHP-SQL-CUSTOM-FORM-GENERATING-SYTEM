<?php 

require 'includes/config.php';

if (!isset($_SESSION['username'])) {
	header('Location: ' . URL);
	exit;
} else if ($_SESSION['user_role'] == 'normal') {
	header('Location: ' . URL . 'answers.php');
	exit;
}

if (isset($_POST['save_question'])) {
	$form_id = clean_input($_POST['form_id']);
	$question = clean_input($_POST['question']);
	$textbox_op = clean_input($_POST['textbox_op']);
	$image_option = clean_input($_POST['image_option']);
	$answer_op = clean_input($_POST['answer_op']);

	$data = Array(
		'form_id' => $form_id,
		'question' => $question,
		'textbox_op' => ($textbox_op == 'yes') ? 1 : 0,
		'image_option' => $image_option,
		'answer_op' => $answer_op,
		'datetime' => date("Y-m-d H:i:s")
	);

	if ($db->insert('questions', $data)) {
		$_SESSION['message'] = '<div class="alert alert-success alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <strong>Success!</strong> Question saved successfully.
		</div>';

		header("Location: " . URL . "questions.php?form_id=" . $form_id);
		exit;
	}
}

if (isset($_POST['update_question'])) {
	$question_id = clean_input($_POST['question_id']);
	$form_id = clean_input($_POST['form_id']);
	$question = clean_input($_POST['question']);
	$image_option = clean_input($_POST['image_option']);
	$textbox_op = clean_input($_POST['textbox_op']);
	$answer_op = clean_input($_POST['answer_op']);

	$data = Array(
		'form_id' => $form_id,
		'question' => $question,
		'image_option' => $image_option,
		'textbox_op' => $textbox_op,
		'answer_op' => $answer_op,
		'datetime' => date("Y-m-d H:i:s")
	);
	$condition = Array(
		'id' => $question_id
	);

	if ($db->update('questions', $data, $condition)) {
		$_SESSION['message'] = '<div class="alert alert-success alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <strong>Success!</strong> Question updated successfully.
		</div>';

		header("Location: " . URL . "questions.php");
		exit;
	}
}

if (isset($_GET['delete_id'])) {
	$question = clean_input($_GET['delete_id']);
	$condition = Array(
		'id' => $question
	);

	if ($db->delete('questions', $condition)) {
		$_SESSION['message'] = '<div class="alert alert-success alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <strong>Success!</strong> Form deleted successfully.
		</div>';

		header("Location: " . URL . "questions.php");
		exit;
	}
}

$forms = $db->multiple_row("SELECT * FROM forms");
$questions = $db->multiple_row("SELECT questions.id, questions.form_id, questions.question, questions.image_option, questions.textbox_op, questions.answer_op, questions.datetime, forms.name FROM questions
INNER JOIN forms ON questions.form_id = forms.id
ORDER BY forms.name
");

$html_title = 'Questions';
$nav_active = 'questions';

require 'includes/header.php';
require 'includes/navigation.php';

?>


<div class="container pt-4">
	<img src="includes/header.jpg" alt="" class="header_image">
	<div class="row">
		<div class="col-md-8">
			<?= isset($_SESSION['message']) ? $_SESSION['message']: false; ?>
			<?php unset($_SESSION['message']); ?>
			<h1 class="bold-upper">New question:</h1>
			<form action="" method="POST" name="save_question">
				<div class="form-group">
					<select name="form_id" id="form_id" class="form-control">
						<option value="">Select form</option>
						<?php foreach($forms as $form): ?>
							<option value="<?= $form['id'] ?>"
							<?= (isset($_GET['form_id']) && $_GET['form_id'] == $form['id']) ? 'selected': false; ?>><?= $form['name'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="form-group">
					<label for="" class="bold-upper">Question:</label>
					<textarea name="question" id="" cols="30" rows="2" class="form-control"></textarea>
				</div>

				<div class="form-group">
					<label for="">Textbox option:</label><br>
					<div class="custom-control custom-radio custom-control-inline col-4">
				    <input type="radio" class="custom-control-input" id="yes1007" name="textbox_op" value="yes">
				    <label class="custom-control-label" for="yes1007">Yes</label>
				  </div>
				  <div class="custom-control custom-radio custom-control-inline col-4">
				    <input type="radio" class="custom-control-input" id="no1007" name="textbox_op" value="no">
				    <label class="custom-control-label" for="no1007">No</label>
				  </div>
				</div>

				<div class="form-group">
					<label for="">Image option:</label><br>
					<div class="custom-control custom-radio custom-control-inline col-2">
				    <input type="radio" class="custom-control-input" id="yes1006" name="image_option" value="1">
				    <label class="custom-control-label" for="yes1006">Yes</label>
				  </div>
				  <div class="custom-control custom-radio custom-control-inline col-2">
				    <input type="radio" class="custom-control-input" id="no1006" name="image_option" value="2">
				    <label class="custom-control-label" for="no1006">No</label>
				  </div>
				  <!-- multiple pics option -->
				  <div class="custom-control custom-radio custom-control-inline col-2">
				    <input type="radio" class="custom-control-input" id="mu1006" name="image_option" value="3">
				    <label class="custom-control-label" for="mu1006">Multiple</label>
				  </div>
				</div>
				

				<div class="form-group">
					<label for="">Answer option:</label><br>
					<div class="custom-control custom-radio custom-control-inline col-4">
				    <input type="radio" class="custom-control-input" id="anyesno" name="answer_op" value="1">
				    <label class="custom-control-label" for="anyesno">Yes / No</label>
				  </div>
				  <div class="custom-control custom-radio custom-control-inline col-4">
				    <input type="radio" class="custom-control-input" id="anoknok" name="answer_op" value="2">
				    <label class="custom-control-label" for="anoknok">Ok / Not ok</label>
				  </div>
				</div>
				<div class="form-group">
					<input type="submit" name="save_question" class="btn btn-primary btn-sm" value="Save">
				</div>
			</form>
		</div>
	</div>

	<hr>

  <h1>Questions:</h1>
  <table class="table table-bordered">
  	<thead>
  		<tr>
  			<th>Form</th>
  			<th>Question</th>
  			<th>Textbox</th>
  			<th>Image</th>
  			<th>Answer option</th>
  			<th>Datetime</th>
  			<th>Action</th>
  		</tr>
  	</thead>
<!-- tabel in formulier /questions -->
  	<tbody>
  		<?php if (count($questions) > 0): ?>
  		<?php foreach ($questions as $question): ?>
			<tr>
			<td class="question-form"><?= $question['name'] ?></td>
  			<td class="qs"><?= $question['question'] ?></td>
  			<td class="tx_op"><?= ($question['textbox_op'] == 1) ? 'Yes' : 'No'  ?></td>
			<!--<td class="im_op"><?= ($question['image_option'] == 1) ? 'Yes' : 'No' ?> </td> --> <!-- Dit nog aanpassen als onderstaand-->
  			
  			<td class="im_op" data-answer-op="<?= $question['image_option'] ?>"><?php
  			if ($question['image_option'] == 1) {
  				echo 'Yes';
  			} else if ($question['image_option'] == 2) {
  				echo 'No';
  			} else {
  				echo "jooo";
  			}
  			?></td>
  			<td class="an_op" data-answer-op="<?= $question['answer_op'] ?>"><?php
  			if ($question['answer_op'] == 1) {
  				echo 'Yes / No';
  			} else if ($question['answer_op'] == 2) {
  				echo 'Ok / Not ok';
  			}
  			?></td>
  			<td><?= ($question['datetime'] != NULL) ? $question['datetime']->format('Y-m-d H:i:s'): false; ?></td>
  			<td>
  				<a onclick="return confirm('Are you sure?');" href="<?= URL . 'questions.php?delete_id='. $question['id'] ?>">Delete</a>
  				<span data-form-id="<?= $question['form_id'] ?>" data-id="<?= $question['id'] ?>" class="text-success cursor_pointer edit">Edit</span></td>

  		</tr>
  		<?php endforeach; ?>
			<?php else: ?>
			<tr><td colspan="2"><i>No question saved yet!</i></td></tr>
			<?php endif; ?>
  	</tbody>
  </table>

  <!--einde tabel in formulier /questions -->

<!-- The Modal -->
<div class="modal" id="update_question_modal">
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
	$('form[name="save_question"]').submit(function(e) {
		$('.alert').remove();
		var form_id = $('select[name="form_id"]').val();
		var question = ($('textarea[name="question"]').val()).trim();
		var textbox_op = $('input[name="textbox_op"]:checked');
		var image_option = $('input[name="image_option"]:checked');
		var answer_op = $('input[name="answer_op"]:checked');

		if (question == '' || form_id == '' || textbox_op.length == 0 || image_option.length == 0 || answer_op.length == 0) {
			e.preventDefault();
			var message = $(`<div class="alert alert-danger alert-dismissible" style="display: none;">
			  <button type="button" class="close" data-dismiss="alert">&times;</button>
			  <strong>Error!</strong> All fields are required
			</div>`);
			$('form[name="save_question"]').prepend(message);
			$(message).fadeIn();
		}
	});

	$('.edit').click(function() {
		var id = $(this).attr('data-id');
		var form_id = $(this).attr('data-form-id');
		
		var question = $(this).closest('tr').find('td.qs').text();
		var image_op = $(this).closest('tr').find('td.im_op').text();
		var answer_op = $(this).closest('tr').find('td.an_op').attr('data-answer-op');
		var textbox_op = $(this).closest('tr').find('td.tx_op').text();
		/* console.log(
			'id:' + id +
			'\nform id: ' + form_id + 
			'\nquestion:' + question + 
			'\nImage option:' + image_op + 
			'\nAnswer option:' + answer_op + 
			'\nTextbox option' + textbox_op); */ // throw new Error('Qaisar is testing');

		var form_select_html = '<option value="">Select form</option>';
		$.ajax({
			url: 'ajax.php',
			method: 'GET',
			data: {get_all_forms: true},
			success: function(data) {
				if (typeof data['forms'] !== undefined) {
					data['forms'].forEach(function(item, index) {
						var selected = '';
						(item['id'] == form_id) ? selected = 'selected': selected = '';
						form_select_html += `<option value="${item['id']}" ${selected}>${item['name']}</option>`;
					});

					var form_body = $('#update_question_modal').find('.modal-body');
					$('form[name="update_question"]').remove();
					var update_form = $(`
						<form action="" method="POST" name="update_question">
							<input type="hidden" name="question_id" value="${id}">
							<div class="form-group">
								<label>Select form:</label>
								<select name="form_id" id="form_id" class="form-control">${form_select_html}</select>
							</div>

							<div class="form-group">
								<label for="">Question:</label>
								<textarea name="question" id="" cols="30" rows="2" class="form-control"></textarea>
							</div>

							<div class="form-group">
								<label for="">Textbox option:</label><br>
								<div class="custom-control custom-radio custom-control-inline col-4">
							    <input type="radio" class="custom-control-input" id="qu_top_y" name="textbox_op" value="1" ${(textbox_op == 'Yes')?'checked':''}>
							    <label class="custom-control-label" for="qu_top_y">Yes</label>
							  </div>
							  <div class="custom-control custom-radio custom-control-inline col-4">
							    <input type="radio" class="custom-control-input" id="qu_top_n" name="textbox_op" value="0" ${(textbox_op == 'No')?'checked':''}>
							    <label class="custom-control-label" for="qu_top_n">No</label>
							  </div>
							</div>

							<div class="form-group">
								<label for="">Image option:</label><br>
								<div class="custom-control custom-radio custom-control-inline col-4">
							    <input type="radio" class="custom-control-input" id="qu_im_yes" name="image_option" value="1" ${(image_op == 'Yes')?'checked':''}>
							    <label class="custom-control-label" for="qu_im_yes">Yes</label>
							  </div>
							  <div class="custom-control custom-radio custom-control-inline col-4">
							    <input type="radio" class="custom-control-input" id="qu_im_no" name="image_option" value="0" ${(image_op == 'No')?'checked':''}>
							    <label class="custom-control-label" for="qu_im_no">No</label>
							  </div>
							</div>

							<div class="form-group">
								<label for="">Answer option:</label><br>
								<div class="custom-control custom-radio custom-control-inline col-4">
							    <input type="radio" class="custom-control-input" id="qu_ano_y" name="answer_op" value="1" ${(answer_op == 1)?'checked':''}>
							    <label class="custom-control-label" for="qu_ano_y">Yes / No</label>
							  </div>
							  <div class="custom-control custom-radio custom-control-inline col-4">
							    <input type="radio" class="custom-control-input" id="qu_ano_n" name="answer_op" value="2" ${(answer_op == 2)?'checked':''}>
							    <label class="custom-control-label" for="qu_ano_n">Ok / Not ok</label>
							  </div>
							</div>

							<div class="form-group">
								<input type="submit" name="update_question" class="btn btn-primary btn-sm" value="Save">
							</div>
						</form>
						`);
					$(form_body).append(update_form);
					$(update_form).find("textarea").val(question);
					$(update_form).submit(function(e) {
						$('.alert').remove();
						var form_id = $('form[name="update_question"]').find('select[name="form_id"]').val();
						var question = ($('form[name="update_question"]').find('textarea[name="question"]').val()).trim();
						// var image_option = $('form[name="update_question"]').find('input[name="image_option"]:checked');
						// console.log(image_option.length);

						if (question == '' || form_id == '') {
							e.preventDefault();
							var message = $(`<div class="alert alert-danger alert-dismissible" style="display: none;">
							  <button type="button" class="close" data-dismiss="alert">&times;</button>
							  <strong>Error!</strong> All fields are required.
							</div>`);
							$('form[name="update_question"]').prepend(message);
							$(message).fadeIn();
						}
					});
					$('#update_question_modal').modal();
				}
			},
			error: function(error) {
				console.log(error);
			}
		});
	});
});
</script>
</body>
</html>