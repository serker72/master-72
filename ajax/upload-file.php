<?php

$uploaddir = getcwd().'/../static/uploads/';

//mkdir($uploaddir.date('d'));

$now = time();

if(isset($_FILES['uploadfile'])){
	$file_name = md5(basename($_FILES['uploadfile']['name'])).'_'.$now.basename($_FILES['uploadfile']['name']); 
	$file = $uploaddir . $file_name; 
	$file_tmp = $_FILES['uploadfile']['tmp_name'];
}elseif(isset($_FILES['uploadfile1'])){
	$file_name = md5(basename($_FILES['uploadfile1']['name'])).'_'.$now.basename($_FILES['uploadfile1']['name']);
	$file = $uploaddir . $file_name; 
	$file_tmp = $_FILES['uploadfile1']['tmp_name'];
}elseif(isset($_FILES['uploadfile2'])){
	$file_name = md5(basename($_FILES['uploadfile2']['name'])).'_'.$now.basename($_FILES['uploadfile2']['name']);
	$file = $uploaddir . $file_name; 
	$file_tmp = $_FILES['uploadfile2']['tmp_name'];
}elseif(isset($_FILES['uploadfile3'])){
	$file_name = md5(basename($_FILES['uploadfile3']['name'])).'_'.$now.basename($_FILES['uploadfile3']['name']); 
	$file = $uploaddir . $file_name; 
	$file_tmp = $_FILES['uploadfile3']['tmp_name'];
}elseif(isset($_FILES['uploadfile4'])){
	$file_name = md5(basename($_FILES['uploadfile4']['name'])).'_'.$now.basename($_FILES['uploadfile4']['name']); 
	$file = $uploaddir . $file_name; 
	$file_tmp = $_FILES['uploadfile4']['tmp_name'];
}elseif(isset($_FILES['uploadfile5'])){
	$file_name = md5(basename($_FILES['uploadfile5']['name'])).'_'.$now.basename($_FILES['uploadfile5']['name']); 
	$file = $uploaddir . $file_name; 
	$file_tmp = $_FILES['uploadfile5']['tmp_name'];
}elseif(isset($_FILES['uploadfile6'])){
	$file_name = md5(basename($_FILES['uploadfile6']['name'])).'_'.$now.basename($_FILES['uploadfile6']['name']); 
	$file = $uploaddir . $file_name; 
	$file_tmp = $_FILES['uploadfile6']['tmp_name'];
}elseif(isset($_FILES['uploadfile7'])){
	$file_name = md5(basename($_FILES['uploadfile7']['name'])).'_'.$now.basename($_FILES['uploadfile7']['name']); 
	$file = $uploaddir . $file_name; 
	$file_tmp = $_FILES['uploadfile7']['tmp_name'];
}

if (is_writable($uploaddir)) {
	if (move_uploaded_file($file_tmp, $file)) { 
	  $array['sta'] = 'success';
	  $array['name'] = $file_name;
	  echo json_encode($array); 
	
	  //echo 'success';
	} else {
	/*	$array['sta'] = 'error';
		echo json_encode($array);
	*/
	}
}



?>