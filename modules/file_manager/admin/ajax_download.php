<?php
/**
 * Ajax image editor platform
 * @author Logan Cai (cailongqun [at] yahoo [dot] com [dot] cn)
 * @link www.phpletter.com
 * @since 22/May/2007
 *
 */
require_once '../../../config.php';
require_once DIR_SYSTEM.'admincore.php';
try
{
	if ( ! $_user->hasPermission('module.ajax_menager.admin'))
	{
		throw new userException(__('Access denied'));
	}
	require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "config.php");
	if(!empty($_GET['path']) && file_exists($_GET['path']) && is_file($_GET['path']) && isUnderRoot($_GET['path']))
	{
			
			$path = $_GET['path'];
			//check if the file size
			$fileSize = @filesize($path);
			
			if($fileSize > getMemoryLimit())
			{//larger then the php memory limit, redirect to the file
				
				header('Location: ' . $path);
				exit;				 
			}else 
			{//open it up and send out with php 
				downloadFile($path);	
				 			
			}
	}else 
	{
		die(ERR_DOWNLOAD_FILE_NOT_FOUND);
	}
}
catch(userException $exception)
{
    userErrorHandler($exception);
}
?>