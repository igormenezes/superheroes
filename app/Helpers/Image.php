<?php
namespace App\Helpers;

class Image {
	public static function saveFileTemporary($tmp, $extension)
	{
		$file = base_path() . '/public/images'. $tmp . '.' . $extension;
		move_uploaded_file($tmp, $file);
	}

	public static function saveFilePermanent($files, $nickname, $newFolder = true)
	{
		$images = [];

		if($newFolder){
			mkdir(base_path() . '/public/images/' . $nickname);
		}

		foreach($files as $file){
			$pattern = '/^.*tmp\/(.*)$/';
			preg_match($pattern, $file, $matches);

			$path = base_path() . '/public' . $file;
			$destiny = base_path() . '/public/images/' . $nickname .'/' . $nickname . '-' . $matches[1];
			rename($path, $destiny);

			$images[] = '/images/' . $nickname .'/' . $nickname . '-' . $matches[1];
		}

		return $images;
	}

	public static function removeAllFilesPermanent($images, $nickname, $clear = true)
	{	
		foreach($images as $image){
			$path = base_path() . '/public' . $image = !empty($image->file) ? $image->file : $image;
			unlink($path);	
		}

		if($clear){
			$path = base_path() . '/public/images/' . $nickname;
			rmdir($path);
		}
	}

	public static function removeFile($file)
	{
		$path = base_path() . '/public' . $file;

		if(!file_exists($path)){
			return false;
		}

		unlink($path);

		return true;
	}

	public static function checkDeletedFiles($newImages, $oldImages)
	{
		$deletedFiles = [];

		foreach($oldImages as $image){
			$exist = false;

			foreach($newImages as $newImage){
				if($image->file === $newImage){
					$exist = true;
					break;
				}
			}

			if(!$exist){
				$deletedFiles['ids'][] = $image->id;
				$deletedFiles['files'][] = $image->file;
			}
		}

		return $deletedFiles;
	}

	public static function checkNewFiles($oldImages, $newImages)
	{
		$newFiles = [];

		foreach($newImages as $newImage){
			$exist = false;

			foreach($oldImages as $image){
				if($newImage === $image->file){
					$exist = true;
					break;
				}
			}

			if(!$exist){
				$newFiles[] = $newImage;
			}
		}

		return $newFiles;
	}

	public static function validate($file)
	{
		$pattern = '/^.*\/images\/.*\.(png|jpg|jpeg|bmp|gif|svg)$/';

		if(is_array($file)){
			foreach($file as $string){
				if(!preg_match($pattern, $string, $matches)){
					return false;
				}
			}

			return true;
		}

		if(!preg_match($pattern, $file, $matches)){
			return false;
		}

		return true;
	}
}