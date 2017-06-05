<?php

class Custom_Model_File
{
    /**
     * 创建目录
     *
     * @param    string    $pathName
     * @param    integer   $mode
     * @return   bool
     */
    public static function makeDir($pathName, $mode = 0777)
    {
        if (is_dir($pathName) || empty($pathName)) {
            return true;
        }
        $pathName = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $pathName);
        if (is_file($pathName)) {
            return true;
        }
        $nextPathName = substr($pathName, 0, strrpos($pathName, DIRECTORY_SEPARATOR));
        if (self :: makeDir($nextPathName, $mode)) {
            if (!file_exists($pathName)) {
                return mkdir($pathName, $mode);
            }
        }
        return false;
    }
    
    /**
     * 删除文件及目录
     *
     * @param    string    $dirname
     * @return   bool
     */
    public static function removeDir($dirName)
    {
    	$dirName = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $dirName);
        if (!file_exists($dirName)) {
            return false;
        }
        if (is_file($dirName) || is_link($dirName)) {
            return unlink($dirName);
        }
        $dir = dir($dirName);
        while (false !== $entry = $dir -> read()) {
            if ($entry == '.' || $entry == '..') {
                continue;
            }
            self :: removeDir($dirName . DIRECTORY_SEPARATOR . $entry);
        }
        $dir -> close();
        return rmdir($dirName);
    }
    
    /**
     * 计算文件及目录大小
     *
     * @param    string   $path
     * @return   float
     */
    public static function dirSize($path)
    {
        $size = 0;
        
        if (substr($path, -1, 1) !== DIRECTORY_SEPARATOR) {
            $path .= DIRECTORY_SEPARATOR;
        }
        
        if (is_file($path)) {
            return filesize($path);
        } elseif (!is_dir($path)) {
            return false;
        }
        
        $queue = array($path);
        
        for ($i = 0, $j = count($queue); $i < $j; ++$i)
        {
            $parent = $i;
            if (is_dir($queue[$i]) && $dir = @dir($queue[$i])) {
                $subDirs = array();
                while (false !== ($entry = $dir -> read())) {
                    if ($entry == '.' || $entry == '..') {
                        continue;
                    }
                    
                    $path = $queue[$i] . $entry;
                    if (is_dir($path)) {
                        $path .= DIRECTORY_SEPARATOR;
                        $subDirs[] = $path;
                    } elseif (is_file($path)) {
                        $size += filesize($path);
                    }
                }
                
                unset($queue[0]);
                $queue = array_merge($subDirs, $queue);
                $i = -1;
                $j = count($queue);
                $dir -> close();
                unset($dir);
            }
        }
        return $size;
    }
    
    /**
     * 格式化大小单位
     *
     * @param    int    $size          大小
     * @param    int    $unit          最大显示单位
     * @param    int    $stringFormat  格式化字符串
     * @param    int    $si            是否应用国际单位制
     * @return   string
     */
    public static function sizeFormat($size, $unit = null, $stringFormat = null, $si = true)
    {
        if ($si === true) {
            $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
            $mod   = 1000;
        } else {
            $sizes = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
            $mod   = 1024;
        }
        
        $ii = count($sizes) - 1;
        $unit = array_search((string) $unit, $sizes);
        
        if ($unit === null || $unit === false) {
            $unit = $ii;
        }
        
        if ($stringFormat === null) {
            $stringFormat = '%01.2f %s';
        }
        
        $i = 0;
        
        while ($unit != $i && $size >= 1024 && $i < $ii) {
            $size /= $mod;
            $i++;
        }
 
        return sprintf($stringFormat, $size, $sizes[$i]);
    }


    /**
    * 转化 \ 为 /
    * 
    * @param	string	$path	路径
    * @return	string	路径
    */
     public static  function dir_path($path) {
        $path = str_replace('\\', '/', $path);
        if(substr($path, -1) != '/') $path = $path.'/';
        return $path;
    }
    /**
    * 创建目录
    * 
    * @param	string	$path	路径
    * @param	string	$mode	属性
    * @return	string	如果已经存在则返回true，否则为flase
    */
    public static   function dir_create($path, $mode = 0777) {
        if(is_dir($path)) return TRUE;
        $ftp_enable = 0;
        $path = self::dir_path($path);
        $temp = explode('/', $path);
        $cur_dir = '';
        $max = count($temp) - 1;
        for($i=0; $i<$max; $i++) {
            $cur_dir .= $temp[$i].'/';
            if (@is_dir($cur_dir)) continue;
            @mkdir($cur_dir, 0777,true);
            @chmod($cur_dir, 0777);
        }
        return is_dir($path);
    }
    /**
    * 拷贝目录及下面所有文件
    * 
    * @param	string	$fromdir	原路径
    * @param	string	$todir		目标路径
    * @return	string	如果目标路径不存在则返回false，否则为true
    */
     public static  function dir_copy($fromdir, $todir) {
        $fromdir = self::dir_path($fromdir);
        $todir = self::dir_path($todir);
        if (!is_dir($fromdir)) return FALSE;
        if (!is_dir($todir)) self::dir_create($todir);
        $list = glob($fromdir.'*');
        if (!empty($list)) {
            foreach($list as $v) {
                $path = $todir.basename($v);
                if(is_dir($v)) {
                    self::dir_copy($v, $path);
                } else {
                    copy($v, $path);
                    @chmod($path, 0777);
                }
            }
        }
        return TRUE;
    }
    /**
    * 转换目录下面的所有文件编码格式
    * 
    * @param	string	$in_charset		原字符集
    * @param	string	$out_charset	目标字符集
    * @param	string	$dir			目录地址
    * @param	string	$fileexts		转换的文件格式
    * @return	string	如果原字符集和目标字符集相同则返回false，否则为true
    */
      public static   function dir_iconv($in_charset, $out_charset, $dir, $fileexts = 'php|html|htm|shtml|shtm|js|txt|xml') {
        if($in_charset == $out_charset) return false;
        $list = self::dir_list($dir);
        foreach($list as $v) {
            if (pathinfo($v, PATHINFO_EXTENSION) == $fileexts && is_file($v)){
                file_put_contents($v, iconv($in_charset, $out_charset, file_get_contents($v)));
            }
        }
        return true;
    }


    /**
     * 取得文件扩展
     *
     * @param $filename 文件名
     * @return 扩展名
     */
   public static  function fileext($filename) {
        return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
    }


    /**
    * 列出目录下所有文件
    * 
    * @param	string	$path		路径
    * @param	string	$exts		扩展名
    * @param	array	$list		增加的文件列表
    * @return	array	所有满足条件的文件
    */
     public static  function dir_list($path, $exts = '', $list= array()) {
        $path = self::dir_path($path);
        $files = glob($path.'*');
        foreach($files as $v) {
            if (!$exts || pathinfo($v, PATHINFO_EXTENSION) == $exts) {
                $list[] = $v;
                if (is_dir($v)) {
                    $list = self::dir_list($v, $exts, $list);
                }
            }
        }
        return $list;
    }
    /**
    * 设置目录下面的所有文件的访问和修改时间
    * 
    * @param	string	$path		路径
    * @param	int		$mtime		修改时间
    * @param	int		$atime		访问时间
    * @return	array	不是目录时返回false，否则返回 true
    */
    public static   function dir_touch($path, $mtime = TIME, $atime = TIME) {
        if (!is_dir($path)) return false;
        $path = self::dir_path($path);
        if (!is_dir($path)) touch($path, $mtime, $atime);
        $files = glob($path.'*');
        foreach($files as $v) {
            is_dir($v) ? self::dir_touch($v, $mtime, $atime) : touch($v, $mtime, $atime);
        }
        return true;
    }
    /**
    * 目录列表
    * 
    * @param	string	$dir		路径
    * @param	int		$parentid	父id
    * @param	array	$dirs		传入的目录
    * @return	array	返回目录列表
    */
     public static  function dir_tree($dir, $parentid = 0, $dirs = array()) {
        global $id;
        if ($parentid == 0) $id = 0;
        $list = glob($dir.'*');
        foreach($list as $v) { 
            if (is_dir($v)) {
                $id++;
                $dirs[$id] = array('id'=>$id,'parentid'=>$parentid, 'name'=>basename($v), 'dir'=>$v.'/');
                $dirs = self::dir_tree($v.'/', $id, $dirs);
            }
        }
        return $dirs;
    }

    /**
    * 删除目录及目录下面的所有文件
    * 
    * @param	string	$dir		路径
    * @return	bool	如果成功则返回 TRUE，失败则返回 FALSE
    */
    public static function dir_delete($dir) {
        $dir = self::dir_path($dir);
        if (!is_dir($dir)) return FALSE;
        $list = glob($dir.'*');
        foreach($list as $v) {
            is_dir($v) ? self::dir_delete($v) : @unlink($v);
        }
        return @rmdir($dir);
    }

    /**
     * 文件下载
     * @param $filepath 文件路径
     * @param $filename 文件名称
     */

    public static  function file_down($filepath, $filename = '') {
        if(!$filename) $filename = basename($filepath);
        if(is_ie()) $filename = rawurlencode($filename);
        $filetype = self::fileext($filename);
        $filesize = sprintf("%u", filesize($filepath));
        if(ob_get_length() !== false) @ob_end_clean();
        header('Pragma: public');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: pre-check=0, post-check=0, max-age=0');
        header('Content-Transfer-Encoding: binary');
        header('Content-Encoding: none');
        header('Content-type: '.$filetype);
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Content-length: '.$filesize);
        readfile($filepath);
        exit;
    }
    
    // 函数名: getfilesource
    // 功能: 得到指定文件的内容
    // 参数: $file 目标文件
    // test passed
    function getfilesource ($file)
    {
        if ($fp = fopen($file, 'r')) {
            $filesource = fread($fp, filesize($file));
            fclose($fp);
            return $filesource;
        } else
            return false;
    }
    
    // 函数名: writefile
    // 功能: 创建新文件，并写入内容，如果指定文件名已存在，那将直接覆盖
    // 参数: $file -- 新文件名
    // $source 文件内容
    // test passed
    function writefile ($file, $source)
    {
        if ($fp = fopen($file, 'w')) {
            $filesource = fwrite($fp, $source);
            fclose($fp);
            return $filesource;
        } else
            return false;
    }
        
}