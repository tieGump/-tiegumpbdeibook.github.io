<?php

/**文件操作
 * Class File
 * @author tie.Gump
 */
class File
{
    /**上传
     * @param $file
     * @return array|string
     */
    function uploadFile($file)
    {
        $file=$file['files'];
//        print_r($file);
        $return='';
        if(is_array($file['name'])){
            $return=array();
            foreach($file['name'] as $key=>$value){
                if($file['error'][$key]>0){
                    $return[]='';
                }else{

                    $file_name=$file["name"][$key];
                    $file_name = iconv('UTF-8','GBK',$file_name);
                    $tmp=explode('.',$file_name);
                    $ext_name=isset($tmp[1])?$tmp[1]:'';
                    $file_name=string2ascii($file_name,'_').'.'.$ext_name;
                    move_uploaded_file($file["tmp_name"][$key], UPLOAD_DIR."/UserFiles/".$file_name);
                    $return[]=$file_name;
                }
            }
        }
        return $return;
    }

    /**删除文件
     * @param $file_path
     */
    function dropFile($file_path)
    {
        unlink($file_path);
    }

    /**显示文件
     * @param $file_info
     */
    function showFile($file_info)
    {

//        $file_name_base = explode('.', basename($file_path));
//        $filename = tep_db_output(ascii2string($file_name_base[0], '_'));
//        $realpath = preg_replace('/(' . preg_quote('/', '/') . ')+/', '/',  $file_path);
//        $extension_name = strtolower(preg_replace('/.+\.+/', '', basename($realpath)));
        if(!$file_info)
            return;

        $extension_name=$file_info['file_type'];
        $filename=$file_info['file_name'];
        $realpath=$file_info['file_path'];
        switch ($extension_name) {
            case 'xls': //直接浏览xls文件

                include_once(PUBLIC_DIR . 'php-excel-reader-2.21/load_reader.php');
                $data = new load_cxcel_reader($realpath, 'gb2312');
                $data->output();
                echo '<noscript><iframe src="*.html"></iframe></noscript>';
                exit;
                break;
            case 'swf': //直接打开swf文件
                $filename = $realpath;
                $filesize = filesize($filename);
                $handle = fopen($filename, "r");
                $contents = fread($handle, $filesize);
                fclose($handle);
                header('Content-Type: application/x-shockwave-flash');
                header("Content-Length: " . $filesize);
                //header("Content-Disposition: inline; filename=".$filename);
                echo $contents;
                unset($contents);
                exit;
                break;
            default: //下载

                $this->ouput($realpath, $filename);
                break;
        }
        exit;
    }

    /**输出，下载
     * @param $realpath
     * @param string $output_name
     */
    public static function ouput($realpath, $output_name=''){
        if(!file_exists($realpath)){
            die('No find '.$realpath);
        }
        if($output_name==''){
            $output_name = $realpath;	//输出的文件名
        }
        //$output_name = basename($output_name);
        if(strpos($output_name,'/')!==false){
            $output_name = preg_replace('/.*\//','',$output_name);
        }
        if(strpos($output_name,'\\')!==false){
            $output_name = preg_replace('/.*'.preg_quote('\\').'/','',$output_name);
        }

        $mtime = ($mtime = filemtime($realpath)) ? $mtime : gmtime();
        $size = intval(sprintf("%u", filesize($realpath)));

        if (intval($size + 1) > self::return_bytes(ini_get('memory_limit')) && intval($size * 1.5) <= 1073741824) { //Not higher than 1GB
            ini_set('memory_limit', intval($size * 1.5));
        }

        @apache_setenv('no-gzip', 1);
        @ini_set('zlib.output_compression', 0);
        $fileext  = substr(strrchr($realpath,'.'),1);
        header('Content-Type: '.$fileext);
        header("Content-Type: application/force-download");
        header('Content-Type: application/octet-stream');
        header("Content-Type: application/download");
        header("Content-Transfer-Encoding:binary");
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        header("Content-Disposition: attachment; filename=\"" . $output_name . '"; modification-date="' . date('r', $mtime) . '";');
        header("Content-Length: " . $size);

        set_time_limit(300);

        $chunksize = 1 * (1024 * 1024); // how many bytes per chunk
        if ($size > $chunksize) {
            $handle = fopen($realpath, 'rb');
            $buffer = '';
            while (!feof($handle)) {
                $buffer = fread($handle, $chunksize);
                echo $buffer;
                ob_flush();
                flush();
            }
            fclose($handle);
        } else {
            readfile($realpath);
        }
        exit;
    }

    /**
     * 返回bytes格式的大小数值
     *
     * @param string $val
     * @return int
     */
    private static function return_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        switch($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }
}