<?php/*---------------------------------------------------------------+| eXtreme-Fusion - Content Management System - version 5         |+----------------------------------------------------------------+| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 || http://extreme-fusion.org/                               		 |+----------------------------------------------------------------+| This product is licensed under the BSD License.				 || http://extreme-fusion.org/ef5/license/						 |+---------------------------------------------------------------*/try{	require_once '../../../system/sitecore.php';	$rows = $_pdo->getMatchRowsCount('SELECT * FROM [chat_messages]');	$chat_settings = $_pdo->getRow('SELECT * FROM [chat_settings]');	$_sbb = $ec->sbb;		echo'<script>$(document).ready(function(){$(\'#chat_post\').html('.$rows.');});</script>';	if ($rows)	{		if($chat_settings['life_messages'] == 0) 		{			$result = $_pdo->getData('SELECT * FROM [chat_messages] ORDER BY `id` ASC'); 		}		else		{			$result = $_pdo->exec('DELETE FROM [chat_messages] WHERE date < '.(time() - ($chat_settings['life_messages']*60)));			$result = $_pdo->getData('SELECT * FROM [chat_messages] WHERE date > '.(time() - ($chat_settings['life_messages']*60)).' ORDER BY `id` ASC');    		}		$i =0;		foreach ($result as $row) 		{			echo '<div class="'.($i % 2 == 0 ? 'tbl1' : 'tbl2').'">'.$_user->getusername($row['user_id']).': '.$_sbb->parseAllTags($row['content']).' <span class="alt">'.date('d.m.Y H:i:s', $row['datestamp']).'</span></div>';			$i++;		}	}}catch(optException $exception){    optErrorHandler($exception);}catch(systemException $exception){    systemErrorHandler($exception);}catch(userException $exception){    userErrorHandler($exception);}catch(PDOException $exception){    PDOErrorHandler($exception);}