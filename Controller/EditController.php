<?php

/* Add post with eyecatch image upload */
class EditController extends AppController {
	public $components = array('Image');

	public function index(){
		
	}

	/* Process the uploaded image request from jquery */
	public function save_image(){
		$this->autoRender = false;
		/* change this to your image path *Ex. /app/webroot/img/uploads => img/uploads */
		$path = 'img/uploads/';
		/* upload original image */
		$file_name = $this->save_file($path);

		/* resize the image to thumb and small version */
		$source_image = $path.$file_name;

		/* thumb version */
		$resize_image_thumb_path = $path.'thumb/'.$file_name;
		$this->Image->prepare($source_image);
		$this->Image->crop(150, 150);
		$this->Image->save($resize_image_thumb_path);

		/* small version */
		$resize_image_small_path = $path.'small/'.$file_name;
		$this->Image->prepare($source_image);
		$this->Image->crop(50, 50);
		$this->Image->save($resize_image_small_path);
	}

	/* Save file */
	private function save_file($path = 'img/'){
		$file_name = $this->random_string();
		$ext = explode(',', $_FILES['file']['name']);
		$file_name = $file_name.'.'.$ext[1];

		$destination = $path.$file_name;
		$location = $_FILES['file']['tmp_name'];

		move_uploaded_file($location, $destination);
		return $file_name;
	}

	/* Create random string for file name */
	private function random_string(){
		return md5(rand(100, 200));
	}

}
