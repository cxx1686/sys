<?php
set_time_limit(0);
ini_set('memory_limit', '1024M');
$zip_file = $_FILES["zipfile"];

$res = array('code' => 1, 'msg' => '请选择文件');
if(!empty($zip_file))
{	
	$zip_file_path = upload_zip($zip_file);
	$res = $zip_file_path;
}

exit(json_encode($res));

 function upload_zip($upload_file)
{
	$path = str_replace('\\','/',realpath(dirname(__FILE__).'/../../'))."/";
	$tname = $upload_file["tmp_name"];
	//$fname = iconv("UTF-8", "gbk", $upload_file["name"]); 
	$fname = $upload_file["name"];
	
	$res = array('code' => 0, 'msg' => '成功');
	$type_wj = pathinfo($fname, PATHINFO_EXTENSION); //获取文件类型

	$zip_path = $path.'file/zip/'.date('Y-m-d');
	is_dir($zip_path) OR mkdir($zip_path, 0777, true); 

	$pos = strrpos($fname, '.');
	$zip_file = $zip_path.'/'.$fname;
	if(file_exists($zip_file))
	{
		$ext = substr($fname, $pos);
		$fname = substr($fname,0,$pos).'_'.date('YmdHis').$ext;
		$pos = strrpos($fname, '.');
		$zip_file = $zip_path.'/'.$fname;
	}
	$zip_file_path = $path.'file/zip_file/'.date('Y-m-d').'/'.substr($fname,0,$pos);
	is_dir($zip_file_path) OR mkdir($zip_file_path, 0777, true); 
		
	 //判断文件类型
	
	
	if(strtolower($type_wj) == "zip" || strtolower($type_wj) == "rar")
	{
		move_uploaded_file($tname,$zip_file); 
		if (strtolower($type_wj) == "zip")
		{
			
			$zip = new ZipArchive; 
			$res_status = $zip->open($zip_file); 
			 if ($res_status === TRUE) { 
				 //解压缩到test文件夹 
				$zip->extractTo($zip_file_path); 
				$zip->close();
				//$res['data']['path'] = '/file/zip/'.date('Y-m-d').'/'.iconv("gbk", "UTF-8", $fname);
				
			 } else { 
				 $res['code'] = 1;
				 $res['msg'] = '请稍后再试！';
			 } 
			 
			 //exec("unzip -O GBK -o {$zip_file} -d {$zip_file_path}");
		}
		else
		{	
			//$obj=new com("wscript.shell");//使用PHP预定义的Com组件加载Shell,加载wscript.shell用来执行dos命令的组件
			//$obj->run("winrar x $zip_path\\".$fname." ".$zip_file_path,0,true);//所要执行的命
			$locale = 'zh_CN.UTF-8';
			setlocale(LC_ALL, $locale);
			putenv('LC_ALL='.$locale);
			exec("rar x {$zip_file} {$zip_file_path}/");
		}
		exec("chmod -R 777 {$zip_file_path}");
		exec("chmod -R 777 {$zip_file}");
		$res['data']['path'] = '/file/zip/'.date('Y-m-d').'/'.$fname;
		$res['data']['name'] = $fname;
	 }
	 else
	 {
		$res['code'] = 1;
		$res['msg'] = '上传压缩包格式不正确，只限zip或rar！';
		
	 }
	
	return $res;
}