<?php
/**
 +---------------------------------<br/>
 * 功能:将多个文件压缩成zip文件的工具类<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package util.common
 * @author skygreen
 */
class UtilZipfile
{
    /**
     * Array to store compressed data
     *
     * @var  array    $datasec
     */
    private $datasec            = array();

    /**
     * Central directory
     *
     * @var  array    $ctrl_dir
     */
    private $ctrl_dir           = array();

    /**
     * End of central directory record
     *
     * @var  string   $eof_ctrl_dir
     */
    private $eof_ctrl_dir       = "\x50\x4b\x05\x06\x00\x00\x00\x00";

    /**
     * Last offset position
     *
     * @var  integer  $old_offset
     */
    private $old_offset         = 0;
    /**
     * 是否压缩包里包括文件夹信息
     */
    public $is_dir_info_include = false;

    /**
     * Converts an Unix timestamp to a four byte DOS date and time format (date
     * in high two bytes, time in low two bytes allowing magnitude comparison).
     *
     * @param  integer  the current Unix timestamp
     *
     * @return integer  the current date in a four byte DOS format
     *
     * @access private
     */
    private function unix2DosTime($unixtime = 0)
    {
        $timearray = ($unixtime == 0) ? getdate() : getdate($unixtime);

        if ($timearray['year'] < 1980)
        {
            $timearray['year']    = 1980;
            $timearray['mon']     = 1;
            $timearray['mday']    = 1;
            $timearray['hours']   = 0;
            $timearray['minutes'] = 0;
            $timearray['seconds'] = 0;
        } // end if

        return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) |
                ($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
    } // end of the 'unix2DosTime()' method


    /**
     * Adds "file" to archive
     *
     * @param  string   file contents
     * @param  string   name of the file in the archive (may contains the path)
     * @param  integer  the current timestamp
     *
     * @access public
     */
    private function addFile($data, $name, $showName, $time = 0)
    {
        //$name=Util_String::utf82gbk($name);
        if ($this->is_dir_info_include){
            if (is_int($showName)){
                $name    = str_replace(Gc::$nav_root_path, "", $name);
                $name    = str_replace('\\', '/', $name);
            }else{
                $name=$showName;
            }
        }else{
            $name=basename($name);
            if (!is_int($showName))$name=$showName;
        }

        if(contain(strtolower(client_os()),"windows")) if (UtilString::is_utf8($name)) $name=UtilString::utf82gbk($name);

        $dtime      = dechex($this->unix2DosTime($time));
        $hexdtime = '\x' . $dtime[6] . $dtime[7]
                  . '\x' . $dtime[4] . $dtime[5]
                  . '\x' . $dtime[2] . $dtime[3]
                  . '\x' . $dtime[0] . $dtime[1];
        eval('$hexdtime = "' . $hexdtime . '";');

        $fr    = "\x50\x4b\x03\x04";

        $fr   .= "\x14\x00";// ver needed to extract
        $fr   .= "\x00\x00";// gen purpose bit flag
        $fr   .= "\x08\x00";// compression method
        $fr   .= $hexdtime;// last mod time and date

        // "local file header" segment
        $unc_len = strlen($data);
        $crc     = crc32($data);
        $zdata   = gzcompress($data);
        $zdata   = substr(substr($zdata, 0, strlen($zdata) - 4), 2); // fix crc bug
        $c_len   = strlen($zdata);
        $fr      .= pack('V', $crc);             // crc32
        $fr      .= pack('V', $c_len);             // compressed filesize
        $fr      .= pack('V', $unc_len);         // uncompressed filesize
        $fr      .= pack('v', strlen($name));     // length of filename
        $fr      .= pack('v', 0);                 // extra field length
        $fr      .= $name;

        // "file data" segment
        $fr .= $zdata;

        // "data descriptor" segment (optional but necessary if archive is not
        // served as file)
        $fr .= pack('V', $crc);                 // crc32
        $fr .= pack('V', $c_len);             // compressed filesize
        $fr .= pack('V', $unc_len);             // uncompressed filesize

        // add this entry to array
        $this -> datasec[] = $fr;

        // now add to central directory record
        $cdrec  = "\x50\x4b\x01\x02";
        $cdrec .= "\x00\x00";                // version made by
        $cdrec .= "\x14\x00";                // version needed to extract
        $cdrec .= "\x00\x00";                // gen purpose bit flag
        $cdrec .= "\x08\x00";                // compression method
        $cdrec .= $hexdtime;                // last mod time & date
        $cdrec .= pack('V', $crc);            // crc32
        $cdrec .= pack('V', $c_len);        // compressed filesize
        $cdrec .= pack('V', $unc_len);        // uncompressed filesize
        $cdrec .= pack('v', strlen($name) );// length of filename
        $cdrec .= pack('v', 0 );            // extra field length
        $cdrec .= pack('v', 0 );            // file comment length
        $cdrec .= pack('v', 0 );            // disk number start
        $cdrec .= pack('v', 0 );            // internal file attributes
        $cdrec .= pack('V', 32 );            // external file attributes - 'archive' bit set

        $cdrec .= pack('V', $this -> old_offset ); // relative offset of local header
        $this  -> old_offset += strlen($fr);

        $cdrec .= $name;

        // optional extra field, file comment goes here
        // save to central directory
        $this  -> ctrl_dir[] = $cdrec;
    } // end of the 'addFile()' method

    /**
     * Dumps out file
     *
     * @return  string  the zipped file
     *
     * @access public
     */
    private function file()
    {
        $data    = implode('', $this -> datasec);
        $ctrldir = implode('', $this -> ctrl_dir);

        return
            $data .
            $ctrldir .
            $this -> eof_ctrl_dir .
            pack('v', sizeof($this -> ctrl_dir)) .  // total # of entries "on this disk"
            pack('v', sizeof($this -> ctrl_dir)) .  // total # of entries overall
            pack('V', strlen($ctrldir)) .            // size of central dir
            pack('V', strlen($data)) .                // offset to start of central dir
            "\x00\x00";                                // .zip file comment length
    } // end of the 'file()' method


    /**
     * A Wrapper of original addFile Function
     *
     * Created By Hasin Hayder at 29th Jan, 1:29 AM
     *
     * @param array 需要压缩的文件名的数组 relative/absolute path to be added in Zip File
     *              key 键是压缩后的文件名称，如果没有指定，则默认为原来的文件名称
     * 示例:UtilZipFile::addFiles(array("abc.txt"=>Gc::$attachment_path."test.txt"),Gc::$attachment_path."test.zip");
     *      就是将原来文件名为test.txt压缩到test.zip文件，它的新名称就是test.txt。
     * @access public
     */
    private function addFiles($files)/*Only Pass Array*/
    {
        if ( is_array($files) ){
            foreach ($files as $showName => $file)
            {
                if ( contain(strtolower(php_uname()),"windows") ) if ( UtilString::is_utf8( $file ) ) $file = UtilString::utf82gbk( $file );
                if ( is_file($file) ) //directory check
                {
                    $data = implode("",file($file));
                    $this->addFile($data,$file,$showName);
                }
            }
        } else if ( is_string($files) ) {
            if ( contain(strtolower(php_uname()),"windows") ) if ( UtilString::is_utf8( $files ) ) $files = UtilString::utf82gbk( $files );
            if ( is_file($files) ){
                $data     = implode("",file($files));
                $showName = basename($files);
                $this->addFile($data, $files, $showName);
            }
        }
    }

    /**
     * A Wrapper of original file Function
     *
     * Created By Hasin Hayder at 29th Jan, 1:29 AM
     *
     * @param string Output file name
     *
     * @access public
     */
    private function output($file)
    {
        $fp = fopen($file,"w");
        fwrite($fp, $this->file());
        fclose($fp);
    }

    /**
     * 将若干个文件压缩成一个文件下载
     * 注释: 压缩的文件似乎只能在Windows操作系统下可以访问
     * 调用示例:
     *    1.UtilZipfile::zip(array(Gc::$attachment_path."attachment".DIRECTORY_SEPARATOR."20111221034439.xlsx","attachment".DIRECTORY_SEPARATOR."20111221034612.xlsx"),Gc::$attachment_path."goodjob.zip",true);
     *    2.UtilZipFile::zip(array("a/b/c/abc.txt"=>Gc::$attachment_path."test.txt"),Gc::$attachment_path."test.zip");
     *                      就是将原来文件名为test.txt压缩到test.zip文件，它的新名称就是test.txt。
     * @param mixed $arr_filename 需要压缩的文件名称列表
     * @param mixed $outputfile 压缩后输出的压缩文件
     * @param bool $is_dir_info_include 是否包含文件夹在内,默认为false,即所有来自不同位置的文件都在压缩文件根目录下;$arr_filename如果没有指定key时有效，若指定可以则以key为基准，文件夹以/隔开
     */
    public static function zip_window($arr_filename, $outputfile, $is_dir_info_include = false)
    {
        $ziper = new UtilZipfile();
        $ziper->is_dir_info_include = $is_dir_info_include;
        $ziper->addFiles($arr_filename);
        $zip_dir = dirname($outputfile);
        UtilFileSystem::createDir($zip_dir);
        //array of files
        $ziper->output($outputfile);
        return true;
    }

    /**
     * 将指定文件和文件提及的附件如图片或者参考文档压缩成一个文件下载
     * @link https://github.com/Ne-Lexa/php-zip#Documentation-ZipFile-addFile
     * 前提条件:
     *    - PHP 5.5
     *    - 安装PhpZip: composer require nelexa/zip
     * @param string $main_filename 主文件名称
     * @param string $out_zip_filename 输出zip文件名
     * @param string $attachment_source_dir 附件如图片或者参考文档所在目录
     * @param string $attachment_dest_dir 指定在压缩文件里附件如图片或者参考文档所在目录
     * @param string $password 默认没有密码，没有加密
     * 示例
     *    $texFName   = Gc::$upload_path . "tex" . DS . $paperID . ".tex";
     *    $img_dir    = Gc::$upload_path . "tex" . DS ."img";
     *    $outputFile = Gc::$upload_path . "tex" . DS . "zip" . DS . "bb.zip";
     *    $password   = "1234";
     *    UtilZipfile::zip($texFName, $outputFile, $img_dir, "images");
     */
    public static function zip($main_filename, $out_zip_filename, $attachment_source_dir = null, $attachment_dest_dir = "images", $password=null)
    {
        if ( !empty($out_zip_filename) && !empty($main_filename) ) {
            ini_set('memory_limit', '-1'); //取消内存限制
            $zipFile    = new \PhpZip\ZipFile();
            $zip_method = \PhpZip\ZipFile::METHOD_DEFLATED;
            $filename   = basename($main_filename);
            $zipFile->addFile($main_filename, $filename, $zip_method);

            if ( !empty($password) ) {
                $encryptionMethod = \PhpZip\ZipFile::ENCRYPTION_METHOD_TRADITIONAL;
                $zipFile->setReadPassword($password);
                $zipFile->setPassword($password, $encryptionMethod);
            }
            $zip_dir = dirname($out_zip_filename);
            UtilFileSystem::createDir($zip_dir);
            if ( !empty($attachment_source_dir) ) $zipFile->addDir($attachment_source_dir, $attachment_dest_dir, $zip_method);
            $zipFile
                ->saveAsFile($out_zip_filename)
                ->close();
        }
    }

    /**
     * 解压zip文件到指定路径下
     * @param string $zip_filename zip文件名
     * @param string $outputDir 解压zip文件输出文件路径, 默认为和zip文件同一个路径下
     */
    public static function unzip($zip_filename, $outputDir = null)
    {
        if ( empty($outputDir) ) {
            $fileName = pathinfo($zip_filename, PATHINFO_FILENAME);
            $outputDir = dirname($zip_filename) . DS . $fileName;
        }
        $zipFile    = new \PhpZip\ZipFile();
        $zipFile->openFile($zip_filename);
        UtilFileSystem::createDir($outputDir);
        $zipFile->extractTo($outputDir);
    }
}

?>
