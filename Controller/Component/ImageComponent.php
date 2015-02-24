<?php
class ImageComponent extends Component {
  var $name = 'Image';
  private $file;
  private $image;
  private $info;  // 图像信息 array('width','height','bits','mime')
	
  /*
   * 加载图像
   */
  public function prepare($file) {
  	if (file_exists($file)) {
  		$this->file = $file;
  		$info = getimagesize($file);
  		$this->info = array(
        	'width'  => $info[0],
        	'height' => $info[1],
        	'bits'   => $info['bits'],
        	'mime'   => $info['mime']
    	);
    	$this->image = $this->create($file);
  	} else {
    		exit('Error: Could not load image ' . $file . '!');
  	}
  }
	
  /*
   * 通过原图像创建新的图像
   */
  public function create($image) {
  	$mime = $this->info['mime'];
  	if ($mime == 'image/gif') {
  		return imagecreatefromgif($image);
  	} elseif ($mime == 'image/png') {
  		return imagecreatefrompng($image);
  	} elseif ($mime == 'image/jpeg') {
  		return imagecreatefromjpeg($image);
  	}
  }	

  /* 保存图像 */
  public function save($file, $quality = 100) {
      $info = pathinfo($file);
      $extension = strtolower($info['extension']);
 
      if ($extension == 'jpeg' || $extension == 'jpg') {
          imagejpeg($this->image, $file, $quality);
      } elseif($extension == 'png') {
          imagepng($this->image, $file, 0);
      } elseif($extension == 'gif') {
          imagegif($this->image, $file);
      }
	    imagedestroy($this->image);
  }

  /* 按比例缩放图像并且对图像进行剪裁 */
  public function crop($width, $heigth) {
    $source_aspect_ratio = $this->info['width'] / $this->info['height'];
    $desired_aspect_ratio = $width / $heigth;

    if ($source_aspect_ratio > $desired_aspect_ratio) {
        /*
         * 原图像比例较宽时比例缩放
         */
        $temp_height = $heigth;
        $temp_width = ( int ) ($heigth * $source_aspect_ratio);
    } else {
        /*
         * 原图像较小或者较高长时图像缩放比例
         */
        $temp_width = $width;
        $temp_height = ( int ) ($width / $source_aspect_ratio);
    }

    /*
     * 将原图像缩放到一定比例
     */
    $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
    imagecopyresampled(
        $temp_gdim,
        $this->image,
        0, 0,
        0, 0,
        $temp_width, $temp_height,
        $this->info['width'], $this->info['height']
    );

    $top_x = ($temp_width - $width) / 2;
    $top_y = ($temp_height - $heigth) / 2;

    $image_old = $temp_gdim;
    $this->image = imagecreatetruecolor($width, $heigth);
    
    imagecopy($this->image, $image_old, 0, 0, $top_x, $top_y, $this->info['width'], $this->info['height']);
    imagedestroy($image_old);

    $this->info['width'] = $width;
    $this->info['height'] = $heigth;
  }
  
}
