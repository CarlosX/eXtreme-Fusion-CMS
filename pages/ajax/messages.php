<?php/***********************************************************| eXtreme-Fusion 5.0 Beta 5| Content Management System       || Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 | http://extreme-fusion.org/                               		 || This product is licensed under the BSD License.				 | http://extreme-fusion.org/ef5/license/						 ***********************************************************/require_once '../../system/sitecore.php';try{	if ($_request->post('action')->show() === 'send')	{		if ($_request->post('message_subject')->show())		{			$item_id = $_pdo->getField('SELECT max(`item_id`) FROM [messages]') + 1;			$subject = $_request->post('message_subject')->strip();		}		else		{			$item_id = $_request->post('item_id')->isNum(TRUE);			$subject = '';		}    		if ($_request->post('send')->show() && iUSER && $_request->post('message')->show() != '' && isNum($_request->post('to')->show()))		{			$result = $_pdo->exec('INSERT INTO [messages] (`to`, `from`, `item_id`, `message`, `subject`, `datestamp`) VALUES (:to, :from, :item_id, :message, :subject, :datestamp)',				array(					array(':to', $_request->post('to')->show(), PDO::PARAM_INT),					array(':from', $_user->get('id'), PDO::PARAM_INT),					array(':item_id', $item_id, PDO::PARAM_INT),					array(':subject', $subject, PDO::PARAM_STR),					array(':message', $_request->post('message')->strip(), PDO::PARAM_STR),					array(':datestamp', time(), PDO::PARAM_INT)				)			);		}				if ($_request->post('message_subject')->show())		{			_e($item_id);		}	}	else//if()	{		if (($_request->get('item_id')->show() && $_request->get('item_id')->isNum(TRUE)) || ($_request->post('item_id')->show() && $_request->post('item_id')->isNum(TRUE)))		{			$result = $_pdo->getData('SELECT * FROM [messages] WHERE `item_id` = :item_id AND (`to` = :user OR `from` = :user) ORDER BY id ASC',				array(					array(':item_id',$_request->get('item_id')->show(), PDO::PARAM_INT),					array(':user', $_user->get('id'), PDO::PARAM_INT)				)			);			$i = 0;			$_sbb = $ec->getService('Sbb');			foreach ($result as $row)			{				echo '				<article class="'.($row['from']==$_user->get('id') ? 'my_post' : 'sb_post').' clearfix">					<span class="arrow"></span>					'.($_user->getByID($row['from'],'avatar') ? '<img src="'.ADDR_IMAGES.'avatars/'.$_user->getByID($row['from'],'avatar').'" alt="Avatar" class="avatar">'																: '<img src="'.ADDR_IMAGES.'avatars/none.gif" alt="No Avatar" class="avatar">').'					<div class="pw_cont">						<header>							<time datetime="'.date('Y-m-d H:i', $row['datestamp']).'">'.HELP::showDate('longdate', $row['datestamp']).'</time>							<strong>Napisał(a): '.HELP::profileLink(NULL, $row['from']).'</strong>						</header>						<div>'.$_sbb->parseBBCode($row['message']).'</div>					</div>				</article>';				$i++;			}		}	}}catch(optException $exception){  optErrorHandler($exception);}catch(systemException $exception){  systemErrorHandler($exception);}catch(userException $exception){  userErrorHandler($exception);}catch(PDOException $exception){  PDOErrorHandler($exception);}