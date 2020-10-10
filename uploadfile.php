<?php


if(isset($_POST['submitImage']))
{
	for($i=0;$i<count($_FILES["uploadFile"]["name"]);$i++)
	{
		$uploadfile=$_FILES["uploadFile"]["tmp_name"][$i];
		$folder="uploaded_files/";


		move_uploaded_file($_FILES["uploadFile"]["tmp_name"][$i], "$folder".$_FILES["uploadFile"]["name"][$i]);
	}
	exit();
}


?>
<!-- TEST, NOT WORKING, I THINK OLD CODEs-->