<?php

namespace App;

use DB;

use Image;
use Intervention\Image\Exception\NotReadableException;

class ImageJob
{

	public static function Resize($path, $max_width, $max_height)
	{
		$_image = null;
		$image = null;

		try {
			$_image = Image::make(public_path() . $path);

			$_height = $_image->height();
			$_width = $_image->width();

			$ratio = $max_width / $_width;
			$new_w = $max_width;
			$new_h = $_height * $ratio;

			//if that didn't work
			if ($new_h > $_height) {
				$ratio = $max_height / $_height;
				$new_h = $max_height;
				$new_w = $_width * $ratio;
			}

			$_image = $_image->resize($new_w, $new_h);

			$_image->encode($_image->extension);
			$type = $_image->extension;
			$image = 'data:image/' . $type . ';base64,' . base64_encode($_image);

		} catch (NotReadableException $e) {

			try {
				$_image = Image::make($path);

				$_height = $_image->height();
				$_width = $_image->width();

				$ratio = $max_width / $_width;
				$new_w = $max_width;
				$new_h = $_height * $ratio;

				//if that didn't work
				if ($new_h > $_height) {
					$ratio = $max_height / $_height;
					$new_h = $max_height;
					$new_w = $_width * $ratio;
				}

				$_image = $_image->resize($new_w, $new_h);

				$_image->encode($_image->extension);
				$type = $_image->extension;
				$image = 'data:image/' . $type . ';base64,' . base64_encode($_image);

			} catch (NotReadableException $e) {
				$image = null;
			}

		}

		return $image;
	}

}