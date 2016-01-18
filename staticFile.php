<?php
/**
 * 文件静态化类
 * 
 */
class FileStatic 
{	
	/**
	 * 产生静态文件 
	 */
	public $staticFilePath;//静态文件存放路径

	public function __construct($path = '/home/static'){
		$this->staticFilePath = $path;
	}

	public function saveStaticFile($type,$id,$html){
		$filename=$id.'.html';
		$path = $this->getFilePath($type,$id);
		if( !is_dir ($path)){
			$this->mkdirs($path,0775);
		}
		$fp=fopen($path.$filename, "w+"); //打开文件指针，创建文件
		if ( !is_writable($path.$filename) ){
		      //die("文件:" .$filename. "不可写，请检查！");
		}
		fwrite($fp, $html);
		fclose($fp);  //关闭指针
	}
	
	
	/**
	 * 获取静态文件
	 * $type：缓存文件夹名
	 */
	public function getStaticFile($type,$id){
		$requestURL = $this->getFilePath($type,$id);
		$requestURL .= $id.".html";
		if(file_exists($requestURL)){
			$data = file_get_contents($requestURL);
			return $data;
		}else{
			return false;
		}
	}

	/**
	 * 删除静态文件
	 */
	public function deleteStaticFile($type,$id,$all=0){
		if($all){
			$path = $this->staticFilePath;
			exec("cd {$path}\n rm $type -rf");
		}
		$requestURL = $this->getFilePath($type,$id);
		$requestURL .= $id.".html";
		if(file_exists($requestURL)){
			return unlink($requestURL);
		}else{
			return true;
		}
	}

	/**
	 * 创建目录
	 */
	private function mkdirs($dir, $mode = 0775){
		if (is_dir($dir) || @mkdir($dir, $mode)){
			return true;
		}
		if (!$this->mkdirs(dirname($dir), $mode)){
			return false;
		}
		return @mkdir($dir, $mode);
	}

	/**
	 * 获取文件路径
	 */
	public function getFilePath($type,$id){
		$path = $this->staticFilePath.$type.'/'.(($id >> 16) & 0xff)."/".(($id >> 8) & 0xff)."/";
		return $path;
	}
}