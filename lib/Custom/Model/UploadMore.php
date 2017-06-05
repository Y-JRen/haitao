<?php

class Custom_Model_UploadMore
{
	public $savepath;
	public $alowexts;
	public $maxsize;
	public $overwrite;
	public $files = array();
	public $uploads = 0;
	public $uploadeds = 0;
	public $imageexts = array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'apk', 'ipa', 'xls');
	public $uploadedfiles = array();
	public $error;

	function __construct($key,$inputname, $savepath = '', $savename = '', $alowexts = 'jpg|jpeg|gif|bmp|png|apk|ipa|xls', $maxsize = 2048000, $overwrite = 0)
	{
		if(!isset($_FILES[$inputname]) && !is_array($_FILES)) return false;
		$savepath = str_replace("\\", '/', $savepath);
		$this->set_savepath($savepath);
		$this->savename = $savename;
		$this->alowexts = $alowexts;
		$this->maxsize = $maxsize;
		$this->overwrite = $overwrite;

		$this->uploads = 1;
		$uploadfiles[$key] = array('tmp_name' => $_FILES[$inputname]['tmp_name'][$key], 'name' => $_FILES[$inputname]['name'][$key], 'type' => $_FILES[$inputname]['type'][$key], 'size' => $_FILES[$inputname]['size'][$key], 'error' => $_FILES[$inputname]['error'][$key]);

		if(!is_dir($this->savepath) && !$this->dir_create($this->savepath))
		{
			$this->error = 8;
			return false;
		}

		@chmod($this->savepath, 0777);
		if(!is_writeable($this->savepath) && ($this->savepath != '/'))
		{
			$this->error = 9;
			return false;
		}
		$this->files = $uploadfiles;
		return $this->files;
	}

	function up($auto_thumb = false, $thumbs = '80,80')
	{
		if($auto_thumb) {
			include_once 'Image.php';
			$image = new Custom_Model_Image();
		}
		if(empty($this->files)) return false;
		foreach($this->files as $k=>$file)
		{
			$fileext = $this->fileext($file['name']);

			if(!preg_match("/^(".$this->alowexts.")$/", $fileext))
			{
				$this->error = 10;
				return false;
			}
			if($this->maxsize && $file['size'] > $this->maxsize)
			{
				$this->error = 11;
				return false;
			}
			if(!$this->isuploadedfile($file['tmp_name']))
			{
				$this->error = 12;
				return false;
			}
			$dir = $this->savepath;
			if(!$this->dir_create($dir))
			{
				$this->error = 8;
				return false;
			}
			$filepath = ($this->savename ? $file['name'] : date('Ymd').'_'.substr(md5(microtime()), 20).$this->random(4).'.'.$fileext);
			$savefile = $this->savepath.$filepath;

			if(!$this->overwrite && file_exists($savefile)) continue;
			if(move_uploaded_file($file['tmp_name'], $savefile) || @copy($file['tmp_name'], $savefile))
			{
				$this->uploadeds++;
				@chmod($savefile, 0644);
				@unlink($file['tmp_name']);
				$this->uploadedfiles[] = array('saveto'=>$savefile, 'filename'=>$file['name'], 'filepath'=>$filepath, 'filetype'=>$file['type'], 'filesize'=>$file['size'], 'fileext'=>$fileext, 'description'=>$file['description']);
				if($auto_thumb)	{
				    $r = explode('|', $thumbs);
				    foreach($r as $v){
				        $t = explode(',', $v);
				        $image->thumb($savefile, $t[0], $t[1]);
				    }
				}
			}
		}
		return $this->uploadedfiles;
	}

    function set_savepath($savepath)
    {
		$savepath = str_replace("\\", "/", $savepath);
	    $savepath = substr($savepath,-1)=="/" ? $savepath : $savepath."/";
        $this->savepath = $savepath;
		return $this->savepath;
    }

	function isuploadedfile($file)
	{
		return is_uploaded_file($file) || is_uploaded_file(str_replace('\\\\', '\\', $file));
	}

	function random($length, $chars = '0123456789')
	{
		$hash = '';
		$max = strlen($chars) - 1;
		for($i = 0; $i < $length; $i++)
		{
			$hash .= $chars[mt_rand(0, $max)];
		}
		return $hash;
	}

	function size($filesize)
	{
		if($filesize >= 1073741824)
		{
			$filesize = round($filesize / 1073741824 * 100) / 100 . ' GB';
		}
		elseif($filesize >= 1048576)
		{
			$filesize = round($filesize / 1048576 * 100) / 100 . ' MB';
		}
		elseif($filesize >= 1024)
		{
			$filesize = round($filesize / 1024 * 100) / 100 . ' KB';
		}
		else
		{
			$filesize = $filesize . ' Bytes';
		}
		return $filesize;
	}

	function fileext($filename)
	{
		return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
	}

	function dir_path($path)
	{
		$path = str_replace('\\', '/', $path);
		if(substr($path, -1) != '/') $path = $path.'/';
		return $path;
	}

	function dir_create($path, $mode = 0777)
	{
		if(is_dir($path)) return TRUE;
		$path = $this->dir_path($path);
	    $temp = explode('/', $path);
	    $cur_dir = '';
		$max = count($temp) - 1;
	    for($i=0; $i<$max; $i++)
	    {
	        $cur_dir .= $temp[$i].'/';
	        if(is_dir($cur_dir)) continue;
			@mkdir($cur_dir, 0777);
			@chmod($cur_dir, 0777);
	    }
		return is_dir($path);
	}

	function error()
	{
		$UPLOAD_ERROR = array(0 => '文件上传成功',
							  1 => '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值',
							  2 => '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值',
							  3 => '文件只有部分被上传',
							  4 => '没有文件被上传',
							  5 => '',
							  6 => '找不到临时文件夹。',
							  7 => '文件写入临时文件夹失败',
							  8 => '附件目录创建不成功',
							  9 => '附件目录没有写入权限',
							  10 => '不允许上传该类型文件',
							  11 => '文件超过了管理员限定的大小',
							  12 => '非法上传文件',
							  13 => '发现同名文件',
							 );
		return $UPLOAD_ERROR[$this->error];
	}
}
?>
