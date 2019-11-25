<?php
/**
 +---------------------------------<br/>
 * 功能:处理文件目录相关的事宜方法。<br/>
 +---------------------------------
 * @category betterlife
 * @package util.common
 * @author skygreen
 */
class UtilFileSystem extends Util
{
    /**
     * 移除数据中的BOM头，它一般是看不见的，但php或html文件有BOM头会影响显示，在头部总有删除不掉的空行。
     * @param string|array $data 内容数据|文件名称 (数组)
     * @return type
     */
    public static function removeBom($data)
    {
        if ( is_array($data) ) {
            foreach ($data as $k => $v) {
                if ( is_file($v) ) {
                    $v = file_get_contents($v);
                }
                $charset[1] = substr($v, 0, 1);
                $charset[2] = substr($v, 1, 1);
                $charset[3] = substr($v, 2, 1);
                if ( ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) {
                    $data[$k] = substr($v, 3);
                }
            }
        } else{
            if ( is_file($data) ) {
                $data = file_get_contents($data);
            }
            $charset[1] = substr($data, 0, 1);
            $charset[2] = substr($data, 1, 1);
            $charset[3] = substr($data, 2, 1);
            if ( ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191 ) {
                $data = substr($data, 3);
            }
        }
        return $data;
    }

    /**
    * 文件重命名
    * @param string $source 原文件名
    * @param string $dest 新文件名
    * @return bool 是否重命名成功。
    */
    public static function file_rename($source, $dest)
    {
        if ( PHP_OS == 'WINNT' ) {
            @copy($source, $dest);
            @unlink($source);
            if ( file_exists($dest) ) {
                return true;
            } else {
                return false;
            }
        } else {
            return rename($source, $dest);
        }
    }

    /**
     * 创建文件夹
     * @param string $dir
     * @param string $mod 文件访问权限
     * @return bool 是否创建成功。
     */
    public static function createDir($dir, $mod = 0755)
    {
        if ( UtilString::is_utf8( $dir ) ) {
            $dir = rawurldecode(self::charsetConvert( $dir ));
        }
        return is_dir($dir) or mkdir($dir, $mod, true);
        //return is_dir($dir) or (self::createDir(dirname($dir)) and mkdir($dir, 0777));
    }

    /**
     * 保存内容到指定文件。
     * 如果该文件不存在，则创建该文件。
     * @param string $filename 文件名
     * @param string $content  内容
     * @return bool 是否创建成功。
     */
    public static function save_file_content($filename, $content)
    {
        if ( UtilString::is_utf8( $filename ) ) {
            $filename = rawurldecode(self::charsetConvert( $filename ));
        }
        self::createDir( dirname($filename) );
        $cFile = fopen($filename, 'w');
        if ( $cFile ){
            file_put_contents($filename, $content);
        } else {
            LogMe::log( "创建文件:" . $filename . "失败！" );
        }
        if ( $cFile ) fclose($cFile);
    }

    /**
     * 移除文件夹<br/>
     * 参考rmdir，但是包括删除文件夹下所有包含的文件和子文件夹，慎用！
     * @param string $dir 目录
     */
    public static function deleteDir($dir)
    {
        $handle = @opendir($dir);
        if ( !$handle ) {
            return false;
            // die("目录不存在:" . $dir);
        }
        while (false !== ( $file = readdir($handle) )) {
            if ( $file != "." && $file != ".." ) {
                $file = $dir . DS . $file;
                if ( is_dir($file) ) {
                    self::deleteDir( $file );
                } else {
                    @unlink($file);
                }
            }
        }
        closedir($handle);
        @rmdir($dir);
    }

    /**
     * 复制源路径下所有的文件和子目录到目标路径下
     * [文件系统函数: copy](https://www.php.net/manual/zh/function.copy.php)
     * @param string $src 源路径目录
     * @param string $dst 目标路径目录
     */
    public static function copyDir($src, $dst) {
        if (file_exists($dst)) self::deleteDir($dst);
        if (is_dir($src)) {
            mkdir($dst);
            $files = scandir($src);
            foreach ($files as $file)
            if ($file != "." && $file != "..") self::copyDir("$src/$file", "$dst/$file");
        }
        else if (file_exists($src)) copy($src, $dst);
    }
    /**
     * 上传文件后，将目标文件的权限设置为0644，避免有些服务器丢失读取权限
     * @param string $filename 文件名
     * @param string $destination 目的地
     * @param string $mod 文件访问权限
     * @return bool 是否操作成功
     */
    public static function move_chmod_uploaded_file($filename, $destination, $mod = 0644)
    {
        if ( move_uploaded_file($filename, $destination) ) {
            chmod($destination, $mod);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 手机接口上传文件Base64数据
     * @param sting $base64_string 上传文件Base64数据
     * @param string $uploadPath 文件路径或者文件名
     * @return array 返回信息数组
     */
    public static function base64_to_image($base64_string, $uploadPath) {
        $ifp = fopen($uploadPath, "wb");

        $data = explode(',', $base64_string);

        fwrite($ifp, base64_decode($data[0]));
        fclose($ifp);

        // LogMe::log($data);
        // LogMe::log($uploadPath);
        return $uploadPath;
    }

    /**
     * 服务器上传文件
     * 需要调整php.ini的配置项:post_max_size|upload_max_filesize
     * @param mixed $files 上传的文件对象
     * @param string $uploadPath 文件路径或者文件名
     * @param sting $uploadFieldName 上传文件的input组件的名称
     * @param boolean $is_permit_same_filename 是否允许文件重名
     * @param sting $file_permit_upload_size 上传文件大小尺寸,单位是M
     * @return array 返回信息数组
     */
    public static function uploadFile($files, $uploadPath, $uploadFieldName = "upload_file", $is_permit_same_filename = false, $file_permit_upload_size = 2)
    {
        if ( $files[$uploadFieldName]["size"] < $file_permit_upload_size * 1024000 ) {
            if ( $files[$uploadFieldName]["error"] > 0 ) {
                switch( $files[$uploadFieldName]['error'] ) {
                    case 1:
                        $errorInfo = "超出了限制上传的文件大小";//The file is too large (server)
                        break;
                    case 2:
                        $errorInfo = "要上传的文件大小超出浏览器限制";//The file is too large (form)
                        break;
                    case 3:
                        $errorInfo = "文件仅部分被上传";//The file was only partially uploaded.
                        break;
                    case 4:
                        $errorInfo = "没有文件被上传";//No file was uploaded.
                        break;
                    case 5:
                        $errorInfo = "服务器临时文件夹丢失";//The servers temporary folder is missing.
                        break;
                    case 6:
                        $errorInfo = "文件写入到临时文件夹出错";//Failed to write to the temporary folder.
                        break;
                }
                $errorInfo .= "<br/>[错误号：" . $files[$uploadFieldName]["error"] . "],详情查看：<br/>http://php.net/manual/zh/features.file-upload.errors.php";
                return array('success' => false, 'msg' => $errorInfo);
            } else {
                //获得临时文件名
                $path_r    = explode('.', $files[$uploadFieldName]["name"]);
                $tmptail   = end($path_r);
                $temp_name = basename($uploadPath);
                if ( contain($temp_name, ".") ){
                    $temp_name="";
                    self::createDir( dirname($uploadPath) );
                } else {
                    $temp_name = date("YmdHis").'.'.$tmptail;
                    self::createDir( $uploadPath );
                }
                system_dir_info(dirname($uploadPath), GC::$upload_path);
                if ( !$is_permit_same_filename && file_exists($uploadPath . $temp_name) ) {
                    return array('success' => false, 'msg' => '文件重名!');
                } else {
                    LogMe::log( "[upload before]:" . $files[$uploadFieldName]["tmp_name"] . "\r\n[upload after]:" . $uploadPath . $temp_name);
                    $IsUploadSucc = move_uploaded_file($files[$uploadFieldName]["tmp_name"], $uploadPath . $temp_name);
                    if ( !$IsUploadSucc ) {
                        return array('success' => false, 'msg' => '文件上传失败，通知系统管理员!');
                    }
                    if ( empty($temp_name) ){
                        $temp_name = basename($uploadPath);
                    }
                    return array('success' => true,'file_showname'=>$files[$uploadFieldName]["name"],'file_name' => $temp_name);
                }
            }
        } else {
            return array('success' => false, 'msg' => '文件太大！文件大小不能超过' . $file_permit_upload_size . "M!");
        }
    }

    /**
     +----------------------------------------------------------<br/>
     * 查看指定目录下的子目录<br/>
     +----------------------------------------------------------
     * @static
     * @access public
     * @param string $dir 指定目录
     * @return array 指定目录下的子目录
     *    1:key-子目录名
     *    2:value-全路径名
     */
    public static function getSubDirsInDirectory($dir)
    {
        $dirdata = array();
        if ( strcmp(substr($dir, strlen($dir) - 1, strlen($dir)), DS) == 0 ) {
            $dir = substr($dir, 0, strlen($dir) - 1);//如果路径不以DIRECTORY_SEPARATOR结尾的话，应补上
        }
        if ( is_dir($dir) )
        {
            $iterator = new DirectoryIterator($dir);
            foreach ($iterator as $fileinfo) {
                if ( $fileinfo->isDir() ) {
                    $file = $fileinfo->getFilename();
                    if ( $file[0] != "." ) {
                        $dirdata[$fileinfo->getFilename()] = $fileinfo->getPathname();
                    }
                }
            }
        }
        return $dirdata;
    }

    /**
     * 获取指定文件夹下下符合要求的文件们
     *
     * @param mixed $dir 指定文件夹
     * @param mixed $agreesuffix 符合要求的文件名后缀名
     */
    public static function getFilesInDirectory($dir, $agreesuffix = array("php"))
    {
        $result = array();
        if ( is_dir($dir) ) {
            $dh = opendir($dir);
            if ( $dh ) {
                while (($file = readdir($dh)) !== false) {
                    if ( $file[0] != "." ) {
                        foreach ($agreesuffix as $suffix) {
                            $fileSuffix = explode('.', $file);
                            $fileSuffix = end($fileSuffix);
                            $fileSuffix = strcasecmp($fileSuffix, $suffix);
                            if ( $fileSuffix === 0 ) {
                                $result[] = $dir . $file;
                            }
                        }
                    }
                }
                closedir($dh);
            }
        }
        return $result;
    }

    /**
     +----------------------------------------------------------<br/>
     * 查看指定目录下的所有文件<br/>
     +----------------------------------------------------------
     * @static
     * @access public
     * @param string $dir 指定目录
     * @param string|array $agreesuffix 是否要求文件后缀名为指定
     *        1.当$agreesuffix='*'为查找所有后缀名的文件
     *        2.当$agreesuffix='php'为查找所有php后缀名的文件
     *        3.当$agreesuffix=array('php','xml')为查找所有php和xml后缀名的文件
     * @return array
     */
    public static function getAllFilesInDirectory($dir, $agreesuffix = array("php"))
    {
        $data = array();
        if ( strcmp(substr($dir, strlen($dir) - 1, strlen($dir)), DS) == 0 ) {
            $dir = substr($dir, 0, strlen($dir) - 1);//如果路径不以DIRECTORY_SEPARATOR结尾的话，应补上
        }
        $dir  = self::charsetConvert( $dir );
        $data = self::searchAllFilesInDirectory( $dir, $data, $agreesuffix );
        ksort($data);
        $result = array_values($data);
        // print_r($data);
        return $result;
    }

    /**
     +----------------------------------------------------------<br/>
     * 查看指定目录下所有的目录<br/>
     +----------------------------------------------------------
     * @static
     * @access public
     * @param string $dir 指定目录
     * @return array
     */
    public static function getAllDirsInDriectory($dir)
    {
        $dirdata = array();
        if ( strcmp(substr($dir, strlen($dir) - 1, strlen($dir)), DS) == 0 ) {
            $dir = substr($dir, 0, strlen($dir) - 1);//如果路径不以DIRECTORY_SEPARATOR结尾的话，应补上
        }
        $dir = self::charsetConvert( $dir );
        $dirdata = self::searchAllDirsInDirectory( $dir, $dirdata );
        return $dirdata;
    }

    /**
     * 递归执行查看指定目录下的所有目录
     * @param string $dir 指定目录
     * @return array
     */
    private static function searchAllDirsInDirectory($path, $dirdata)
    {
        if ( is_dir($path) ) {
            $dp = dir($path);
            $dirdata[]  = $path;
            while($file = $dp->read()) {
                if ( $file[0] != "." ) {
                // if ( $file != '.' && $file != '..' && $file != '.DS_Store' && $file != '.svn' && $file != '.git' ) {
                    $dirdata = self::searchAllDirsInDirectory( $path . DS . $file, $dirdata );
                }
            }
            $dp->close();
        }
        return $dirdata;
    }

    /**
     * 递归执行查看指定目录下的所有文件[完美解决中文文件名的问题]
     * @param string $dir 指定目录
     * @param string|array $agreesuffix 是否要求文件后缀名为指定
     *        1.当$agreesuffix='*'为查找所有后缀名的文件
     *        2.当$agreesuffix='php'为查找所有php后缀名的文件
     *        3.当$agreesuffix=array('php','xml')为查找所有php和xml后缀名的文件
     * @return array
     */
    private static function searchAllFilesInDirectory($path, $data, $agreesuffix = array("php"))
    {
        $handle = @opendir($path);
        if ( $handle ) {
            while (false !== ($file = @readdir($handle))) {
                if ( $file[0] == "." ) {
                    continue;
                }
                $nextpath = $path . DS . $file;

                if ( is_dir($nextpath) ) {
                    $data = self::searchAllFilesInDirectory( $nextpath, $data, $agreesuffix );
                } else {
                    if ( $file !== "Thumbs.db" ) {
                        if ( $agreesuffix == "*" ) {
                            $data[dirname($nextpath) . DS . 'a' . basename($nextpath)] = $nextpath;
                        } else if ( is_string($agreesuffix) ) {
                            $fileSuffix = explode('.', $file);
                            $fileSuffix = end($fileSuffix);
                            $fileSuffix = strcasecmp($fileSuffix, $agreesuffix);
                            if ( $fileSuffix === 0 ) {
                                $isChinese = UtilString::is_chinese( $nextpath );
                                if ( $isChinese ) {
                                    $is_utf8 = UtilString::is_utf8( $nextpath );
                                    if ( !$is_utf8 ) {
                                        $nextpath_tmp = UtilString::gbk2utf8( $nextpath );
                                    }
                                    $nextpath_basename = basename($nextpath_tmp);
                                    $nextpath_tmp      = substr($nextpath_tmp, 0, strrpos($nextpath_tmp, "\\"));
                                } else {
                                    $nextpath_tmp      = dirname($nextpath);
                                    $nextpath_basename = basename($nextpath);
                                }
                                $data[$nextpath_tmp . DS . 'a' . $nextpath_basename] = $nextpath;
                            }
                        } else if ( is_array($agreesuffix) ) {
                            foreach ($agreesuffix as $suffix) {
                                $fileSuffix = explode('.', $file);
                                $fileSuffix = end($fileSuffix);
                                $fileSuffix = strcasecmp($fileSuffix, $suffix);
                                if ( $fileSuffix === 0 ) {
                                    $data[dirname($nextpath) . DS . 'a' . basename($nextpath)] = $nextpath;
                                }
                            }
                        }
                    }

                }
            }
            @closedir($handle);
        }
        return $data;
    }

    /**
     * 解决直接传入中文文件夹无法正常获取子目录的问题
     * @param string $path
     * @return string
     * @example print_r(UtilFileSystem::getAllFilesInDirectory("D:\\测试文件夹\\"));
     */
    private static function charsetConvert($path)
    {
        return iconv("UTF-8", "GBK", $path);
    }

    /**
     * 获取文件扩展名
     * @param mixed $filename
     * @return mixed
     */
    public static function fileExtension($filename)
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }
}
//print_r(UtilFileSystem::getAllFilesInDirectory("D:\\wamp\\www"));
