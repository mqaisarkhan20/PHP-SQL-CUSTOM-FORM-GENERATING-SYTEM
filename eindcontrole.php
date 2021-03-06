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

if (isset($_FILES) && false) {
	echo '<pre>';
	var_dump($_FILES);
	echo '</pre>';exit;
}


if (isset($_GET['form_id'])) {
	$form_id = clean_input($_GET['form_id']);
	$form = $db->single_row("SELECT * FROM forms WHERE id = $form_id");
	$bewerkings = $db->multiple_row("SELECT * FROM bewerkings");

	if (!isset($form['id'])) {
		$_SESSION['message'] = '<div class="alert alert-danger alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <strong>Error!</strong> No form found with this id.
		</div>';

		header("Location: " . URL . "answers.php");
		exit;
	}

	$questions = $db->multiple_row("SELECT * FROM questions WHERE form_id = $form_id");

	if (count($questions) == 0) {
		$_SESSION['message'] = '<div class="alert alert-danger alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <strong>Error!</strong> No question saved in <em>'.ucfirst($form['name']).'</em> form.
		</div>';

		header("Location: " . URL . "answers.php");
		exit;
	}
}
// Posting the answer
if (isset($_POST['save_answers'])) {
	$form_id = clean_input($_POST['form_id']);
	$project_number = clean_input($_POST['project_number']);
	$bewerking = (isset($_POST['bewerking'])) ? clean_input($_POST['bewerking']): NULL;

	$pre_form_ans = $db->single_row("SELECT * FROM answers WHERE form_id = $form_id AND user_id = $_SESSION[user_id] ORDER BY id DESC");

	if (isset($pre_form_ans['form_id'])) {
		$v_id = explode('V', $pre_form_ans['form_v_id']);
		$form_v_id = $form_id . '_' . $project_number . '_V' . ($v_id[1] + 1);
	} else {
		$form_v_id = $form_id . '_' . $project_number . '_V1';
	}
	

	$data = Array();
	$total_questions = Array();
	$remarks = Array();
	$data_that_will_be_saved = Array();
	foreach ($_POST as $key => $value) {
		if (gettype($value) != 'array') {
			

			if (strpos($key, 'remarks') !== false && $key != 'save_answers') {
			  $user_remarks = explode('_', $key);
			  $question_id = $user_remarks[1];

			  $remarks[$question_id] = $value;
			}
		}
	}
// File uploading voor foto 1


		$all_files_test = Array();
	if (isset($_FILES)) {
		foreach ($_FILES as $key => $value) {
			if (!empty($value['name'])) {
				$temp = explode('_', $key);
			  $question_id = $temp[1];

			  /* FILE UPLOAD PROCESS */
			  $path_parts = pathinfo($value['name']);
			  $file_type = strtolower(pathinfo($value['name'], PATHINFO_EXTENSION));
			  $file_name = ucfirst($path_parts['
			  	']);
			  $new_file_name = preg_replace('/\s+/', '_', $file_name) . '_'.$project_number.'_'.$form_id.'_'.$question_id .'.' . $project_number. '.' . $file_type;

			  $target_dir = "uploaded_files/";
			  $target_file = $target_dir . $new_file_name;

			  move_uploaded_file($value["tmp_name"], $target_file);
			  /* ../ FILE UPLOAD PROCESS */
			  $all_files_test[$question_id] = $new_file_name;
			}
		}
	}

			$all_files_prod = Array();
	if (isset($_FILES)) {
		foreach ($_FILES as $key => $value) {
			if (!empty($value['name'])) {
				$temp = explode('_', $key);
			  $question_id = $temp[1];

			  /* FILE UPLOAD PROCESS */
			  $path_parts = pathinfo($value['name']);
			  $file_type = strtolower(pathinfo($value['name'], PATHINFO_EXTENSION));
			  $file_name = ucfirst($path_parts['
			  	']);
			  $new_file_name = preg_replace('/\s+/', '_', $file_name) . '_'.$project_number.'_'.$form_id.'_'.$form_id.'_'.$form_id.'_'.$question_id .'.'. '3'. '.' . $file_type;

			  $target_dir = "uploaded_files/";
			  $target_file = $target_dir . $new_file_name;

			  move_uploaded_file($value["tmp_name"], $target_file);
			  /* ../ FILE UPLOAD PROCESS */
			  $all_files_prod[$question_id] = $new_file_name;
			}
		}
	}

	$all_files_uploaded = Array();
	if (isset($_FILES)) {
		foreach ($_FILES as $key => $value) {
			if (!empty($value['name'])) {
				$temp = explode('_', $key);
			  $question_id = $temp[1];

			  /* FILE UPLOAD PROCESS */
			  $path_parts = pathinfo($value['name']);
			  $file_type = strtolower(pathinfo($value['name'], PATHINFO_EXTENSION));
			  $file_name = ucfirst($path_parts['
			  	']);
			  $new_file_name = preg_replace('/\s+/', '_', $file_name) . '_'.$project_number.'_'.$form_id.'_'.$question_id . '.'.$question_id. '.' . $file_type;

			  $target_dir = "uploaded_files/";
			  $target_file = $target_dir . $new_file_name;

			  move_uploaded_file($value["tmp_name"], $target_file);
			  /* ../ FILE UPLOAD PROCESS */
			  $all_files_uploaded[$question_id] = $new_file_name;
			}
		}
	}



	foreach ($_POST as $key => $value) {
		if (gettype($value) != 'array') {
			if (strpos($key, 'ans') !== false && $key != 'save_answers') {
			  $answer = explode('_', $key);
			  $question_id = $answer[1];
			  
			  // vd($remarks, false);
			  $data_that_will_be_saved['question_id'] = $question_id;
			  $data_that_will_be_saved['answer'] = ($value == 'yes') ? 1 : 0;
			  if (isset($remarks[$question_id])) {
			  	$data_that_will_be_saved['remarks'] = $remarks[$question_id];
			  } else {
			  	$data_that_will_be_saved['remarks'] = '';
			  }

			  if (isset($all_files_test[$achterkantfoto])) {
			  	$data_that_will_be_saved['filename'] = $all_files_test[$achterkantfoto];
			  } else {
			  	$data_that_will_be_saved['filename'] = '';
			  }

			  if (isset($all_files_prod[$jooo])) {
			  	$data_that_will_be_saved['ecimage'] = $all_files_prod[$jooo];
			  } else {
			  	$data_that_will_be_saved['ecimage'] = '';
			  }
			  if (isset($all_files_uploaded[$question_id])) {
			  	$data_that_will_be_saved['ecimage2'] = $all_files_uploaded[$question_id];
			  } else {
			  	$data_that_will_be_saved['ecimage2'] = '';
			  }

			  array_push($total_questions, 1);
			  array_push($data, $data_that_will_be_saved);
			}
		}
	}

	foreach ($total_questions as $key => $value) {
		$data[$key]['form_id'] = $form_id;
		$data[$key]['user_id'] = $_SESSION['user_id'];
		$data[$key]['form_v_id'] = $form_v_id;
		$data[$key]['project_number'] = $project_number;
		$data[$key]['bewerking'] = $bewerking;
		$data[$key]['datetime'] = date("Y-m-d H:i:s");
	}

	foreach ($data as $key => $item) {
		$db->insert('answers', $item);
	}

	$_SESSION['message'] = '<div class="alert alert-success alert-dismissible">
	  <button type="button" class="close" data-dismiss="alert">&times;</button>
	  <strong>Success!</strong> Form submitted successfully.
	</div>';

	header("Location: " . URL . "answers.php");
	exit;
}

if (isset($_POST['save_user'])) {
}

if (isset($_POST['update_user'])) {
}

if (isset($_GET['delete_id'])) {
}

$users = $db->multiple_row("SELECT * FROM users WHERE role = 'normal'");
$forms = $db->multiple_row("SELECT * FROM forms");

$html_title = 'Form';
$nav_active = 'answers';

require 'includes/header.php';
require 'includes/nu_navigation.php';


?>


<div class="container pt-4">
	<img src="includes/header.jpg" alt="" class="header_image">
	<div class="row">
		<div class="col-md-6">
			<?= isset($_SESSION['message']) ? $_SESSION['message']: false; ?>
			<?php unset($_SESSION['message']); ?>
			<h3>Forms:</h3>
			<?php if (count($forms) == 0): ?>
			<div class="alert alert-danger alert-dismissible">
			  <button type="button" class="close" data-dismiss="alert">&times;</button>
			  <strong>Error!</strong> No form saved yet!
			</div>
			<?php endif; ?>
			<form action="" method="GET" name="select_form">
				<div class="form-group">
					<label for=""></label>
					<select name="form_id" id="" class="form-control">
						<option value="">Select form</option>
						<?php foreach ($forms as $item): ?>
							<option value="<?= $item['id'] ?>" 
								<?= (isset($_GET['form_id']) && $_GET['form_id'] == $item['id']) ? 'selected': false; ?>><?= ucfirst($item['name']) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</form>

			<hr>
		</div>
	</div>

	<!-- <hr> -->

	<?php if (isset($questions) && count($questions) > 0): ?>
		<div class="row">
			<div class="col-md-12">
				<!-- <h3>Questions:</h3> -->
				<?php $i = 1; ?>
				<form action="" method="POST" name="questions_form" enctype="multipart/form-data">
				<input type="hidden" name="form_id" value="<?= (isset($form_id)) ? $form_id: false; ?>">


				<?php if ($form['header_op'] == 1): ?>
				<div class="form-group row">
			    <label for="inputEmail3" class="col-md-2 col-form-label">Project number:</label>
			    <div class="col-md-4">
			      <input type="text" class="form-control" name="project_number" id="projectNumber" placeholder="Project number">
			    </div>
			  </div>
			  <!-- Dit aan de onderzijde van formulier doen, zodat $form footer_op gebruikt kan worden voor het laden van foto's mte placeholders-->

			  <div class="form-group row">
			    <label for="inputEmail3" class="col-md-2 col-form-label">Bewerking:</label>
			    <div class="col-md-4">
			      <select name="bewerking" id="" class="form-control">
			      	<option value="">Select</option>
			      	<option value="anything" selected></option>
			      	<?php foreach ($bewerkings as $item): ?>
								<option value="<?= $item['name'] ?>"><?= $item['name'] ?></option>
			      	<?php endforeach; ?>
			      </select>
			    </div>
			  </div>
			  	<!-- zolang statment uit 2 opties bestaat moet het elseif in 'else' veranderd worden -->
				<?php elseif ($form['header_op'] == 2): ?>
				<div class="form-group row">
			    <label for="inputEmail3" class="col-md-2 col-form-label">Project number:</label>
			    <div class="col-md-4">
			      <input type="text" class="form-control" name="project_number" id="projectNumber" placeholder="Project number">
			    </div>
			  </div>

				<?php endif; ?>
				<?php foreach ($questions as $question): ?>
					<div class="form-group">
						<label for=""><?= $i . ') ' . $question['question'] ?></label><br>
						<div class="custom-control custom-radio custom-control-inline col-1">
					    <input type="radio" class="custom-control-input" id="yes<?= $question['id'] ?>" name="<?= 'ans_'.$question['id'] ?>" value="yes">
					    <label class="custom-control-label" for="yes<?= $question['id'] ?>">
					    	<?= ($question['answer_op'] == 1) ? 'Yes' : 'Ok'; ?>
					    </label>
					  </div>
					  <div class="custom-control custom-radio custom-control-inline col-1">
					    <input type="radio" class="custom-control-input" id="no<?= $question['id'] ?>" name="<?= 'ans_'.$question['id'] ?>" value="no">
					    <label class="custom-control-label" for="no<?= $question['id'] ?>">
								<?= ($question['answer_op'] == 1) ? 'No' : 'Not ok'; ?>
					    </label>
					  </div>
					  <?php if ($question['textbox_op'] == 1): ?>
					  <div class="form-group custom-control-inline col-4">
					  	<label for="">Remarks:&nbsp;</label>
					  	<textarea name="remarks_<?= $question['id'] ?>" id="" cols="30" rows="2" class="form-control ml-1"></textarea>
					  </div>
						<?php endif; ?><br>
								<?php $i++; ?>

				<!-- dit is de 'save' button. Zowel met Header1,2 als footer 1,2,3 wordt hier naar gekeken -->
				<?php endforeach; ?>

				<h3>Eindcontrole foto's</h3>
								<!-- begin footer test -->
				<div class="form-group">
				<input type="hidden" name="form_id" value="<?= (isset($form_id)) ? $form_id: false; ?>">

				<div class="form-group row">
		
					
  
<br></div>
<!-- Script voor eindcontrole. vooraf gedefinieerde hoeken waarin foto's gemaakt moeten worden, gebruiker kan nieuwe selecteren indien niet goed' -->



<!-- eind javascript test --> 




<p>Achterkant trailer</p>
<div class="gallery">
	
  <div class="image-upload">
    <label for="inputFile1"><img id="image_upload_preview1"  class="img-responsive mainPic" src="preset/01achterkant.png"/></label>
<!--    <input id="inputFile1" class="custom-file-input" type="file" name="achterkantfoto"/>-->
    <div class="image-upload-footer" style="display:none">
      <button type="reset" custattr="preset/01achterkant.png" class="btn btn-red reset">
        <span class="fa fa-times"aria-hidden="true"></span>
      </button>
<!--      <p>remove</p>-->
    </div>
  </div>
</div>
<br><br><br>

<p> Bestuurderskant achter trailer</p>
<div class="gallery">
  <div class="image-upload">
  <label for="inputFile2"><img id="image_upload_preview2"  class="img-responsive" src="preset/02Bestuurderskantachter.png" alt=""/></label>
<!--  <input id="inputFile2" class="input-file" type="file" name="jooo"/>-->
  <div class="image-upload-footer" style="display:none">
    <button type="button" custattr="preset/02Bestuurderskantachter.png" class="btn btn-red reset">
      <span class="fa fa-times"aria-hidden="true"></span>
    </button>
<!--    <p>remove</p>-->
  </div>
</div>
</div>

<p>deze werkt</p>
<div class="custom-file mt-3 col-3">
	 <!-- <input type="file" class="custom-file-input" id="multipleImageInput" name="<?= 'image_'.$question['id'] ?>" multiple>  -->
	 <!-- Select -->
		<label class="custom-file-label" for="customFile">Select Images</label>
</div>

<br>
<div class="form-group">
    <p>Gallery</p>
<div class="myGallery">
  
</div>

<!-- <input type="file" name="qaisarkhan[]" class="form-control">Select file -->
<div id="pbDemo">
  
</div>

</div>
<!-- <p> zijkant bestuurderskant</p>
<div class="gallery">
	
  <div class="image-upload">
  <label for="inputFile3">
    <img id="image_upload_preview3"  class="img-responsive" src="preset/03Bestuurderskant.png" alt=""/>
  </label>
  <input id="inputFile3" class="input-file" type="file"/>
  <div class="image-upload-footer" style="display:none">
    <button type="button" custattr="preset/03Bestuurderskant.png" class="btn btn-red reset">
      <span class="fa fa-times"aria-hidden="true">X</span>
    </button>
    <p>remove</p>
  </div>
</div>
</div>
<p>bestuurderskant voor</p>
<div class="gallery">
	
  <div class="image-upload">
  <label for="inputFile4">
    <img id="image_upload_preview4"  class="img-responsive" src="preset/04Bestuurderskantvoor.png" alt=""/>
  </label>
  <input id="inputFile4" class="input-file" type="file"/>
  <div class="image-upload-footer" style="display:none">
    <button type="button" custattr="preset/04Bestuurderskantvoor.png" class="btn btn-red reset">
      <span class="fa fa-times"aria-hidden="true">X</span>
    </button>
    <p>remove</p>
  </div>
</div>
</div> -->


			</div>

				<div class="form-group">
					<input type="submit" name="save_answers" class="btn btn-primary btn-sm" value="Save">
				</div>
				</form>
			</div>

		</div>
	<?php endif ?>

	<?php 
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
  
$("#pbDemo").spartanMultiImagePicker({
  image: 'assets/icons/uploadIcon.png',
  width: '150px',
  fieldName: 'multiple_images[]',
  maxCount: 5
});

  
//  MULTIPLE FILE SELECT PREVIEW
$(function() {
  // Multiple images preview in browser
  var imagesPreview = function(input, placeToInsertImagePreview) {

      if (input.files) {
          var filesAmount = input.files.length;
         window.abc = input.files;
        

          for (i = 0; i < filesAmount; i++) {
              var reader = new FileReader();

              reader.onload = function(event) {
                  $($.parseHTML('<img>')).attr('class', 'imgPreviews').attr('src', event.target.result).attr('width', 100).css("padding-right", "20px").css("padding-bottom", "20px").appendTo(placeToInsertImagePreview);
              }

              reader.readAsDataURL(input.files[i]);
          }
      }

  };

  $('#multipleImageInput').on('change', function() {
                  $('.imgPreviews').remove();
      imagesPreview(this, 'div.myGallery');
  });
});
  
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

 /*Script eindcontrole (footer3) preview and possibility to change image in placeholder*/
$(document).ready(function() { 
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $('#image_upload_preview1').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
  }
//  $("#inputFile1").change(function () {
//    readURL(this);
//  });
});
$(document).ready(function() { 
  function readURLs(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $('#image_upload_preview2').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
  }
//  $("#inputFile2").change(function () {
//    readURLs(this);
//  });
});
$(document).ready(function() { 
  function readURLs(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $('#image_upload_preview3').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
  }
  $("#inputFile3").change(function () {
    readURLs(this);
  });
});
$(document).ready(function() { 
  function readURLs(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $('#image_upload_preview4').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
  }
  $("#inputFile4").change(function () {
    readURLs(this);
  });
});
$(document).ready(function() {
        $(".input-file").on("change", function(){
            if($(this).val() == "") {
                $(this).parent().find(".image-upload-footer").css({"display": "none"});
            } else {
            $(this).parent().find(".image-upload-footer").css({"display": "block"});
            }
        });
        $(".reset").click(function(){
            $(this).closest(".image-upload").parent().find(".input-file").val("").trigger('change');
            
            var newImg=$(this).attr('custattr');
             
           $("#"+$(this).closest(".image-upload").parent().find(".img-responsive").attr('id')).attr("src",newImg);
        }); 
    });    
</script>
</body>
</html>