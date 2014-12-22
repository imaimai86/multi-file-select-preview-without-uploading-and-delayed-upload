<?php
/**
 * Function to move uploaded file to target location
 *
 * @param string $fileName
 * @param array $values pass by reference
 *
 * @return string file name
 */
public function process_uploaded_file()
{   
	$bulk_upload = $_POST['bulk_upload'];
	$config['upload_path'] = getcwd().'/uploaded/';
	$config['allowed_types'] = array('gif','jpg', 'jpeg', 'jpe', 'png');
	$uploaded_file_info = false;
	$number_of_images = get_number_of_images_uploaded($_FILES['image_uploader_multiple']);
	if ($number_of_images > 0) {
		foreach ($_FILES['image_uploader_multiple']['name'] as $key => $uploaded_file_name) {
			$uploaded_path_parts = pathinfo($uploaded_file_name);
			$temp_name = $_FILES['image_uploader_multiple']['tmp_name'][$key];
			$fileName = uniqid('', true).".".date("YmdHis").".".sprintf("%06d",rand());
            $fileFullName = $fileName.".".$uploaded_path_parts['extension'];
			$target_path_parts = pathinfo($fileName);
			$target_file_name = $target_path_parts['filename'].'.'.$uploaded_path_parts['extension'];

			$i = 1;
			while (file_exists($config['upload_path'].$target_file_name)) {
				$target_file_name = $target_path_parts['filename'].'-'.($i++).'.'.$uploaded_path_parts['extension'];
			}
			
			$config['file_name'] = $target_file_name;
			$files_info = move_uploaded_file_to_location($temp_name, $target_file_name, $config);

			$uploaded_file_info[] = array(
				'height' => $files_info['image_height'],
				'width' => $files_info['image_width'],
				'path' => $files_info['full_path'],
				'target_name' => $target_file_name,
				'uploaded_file_name' => $uploaded_file_name
			);
		 }
	}

	return $uploaded_file_info;
}

/**
 * Function to get number of images uploaded
 *
 * @param array $image_uploader_multiple
 *
 * @return number
 */
function get_number_of_images_uploaded($image_uploader_multiple)
{
	$count = 0;
	if (isset($image_uploader_multiple['error']) && is_array($image_uploader_multiple['error'])) {
		foreach($image_uploader_multiple['error'] as $error) {
			if ($error != 4) {
				$count++;
			}
		}
	}

	return $count;
}

/**
 * function to move uploaded file to specific location
 *
 * @param string $temp_name
 * @param string $target_file_name
 * @param array $config
 *
 * @return multitype:string unknown multitype:
 */
function move_uploaded_file_to_location($temp_name, $target_file_name, $config)
{
	move_uploaded_file($temp_name, $config['upload_path'].$target_file_name);
	chmod_apply($config['upload_path'].$target_file_name);

	list($width, $height, $type, $attr) = getimagesize($config['upload_path'].$target_file_name);

	return array(
		'image_height' => $height,
		'image_width' => $width,
		'full_path' => $config['upload_path'].$target_file_name
	);
}

/**
 * Function to apply proper permission to the upload file
 *
 * @param $filename
 * @return bool
 */
function chmod_apply($filename = '') {
	$stat = @ stat(dirname($filename));
	$perms = $stat['mode'] & 0007777;
	$perms = $perms & 0000666;
	if ( @chmod($filename, $perms) )
		return true;
	return false;
}
