<?php defined('EF5_SYSTEM') || exit;
/*********************************************************
| eXtreme-Fusion 5
| Content Management System
|
| Copyright (c) 2005-2013 eXtreme-Fusion Crew
| http://extreme-fusion.org/ 
**********************************************************
 	Some open-source code comes from
---------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Author: Wooya
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
$_locale->load('spo');

$bbcode_info = array(
	'name' => __('Spoiler'),
	'description' => __('Displays text as a spoiler'),
	'value' => 'spo'
);

if($bbcode_used)
{
	$_head->set("<script>
		$(document).ready(function(){
			$('#spoilercontent').hide();
			$('#spoilerhead').click(function () {
				if ($('#spoilercontent').is(':hidden')) {
					$('#spoilercontent').fadeIn('slow');
				} else {
					$('#spoilercontent').fadeOut('slow');
				}
				return false;
			});
		});
	</script>");
	
	if ($_user->iUSER()) 
	{
		$text = preg_replace('#\[spo\](.*?)\[/spo\]#si', '<button id="spoilerhead" class="thead">'.__('Pokaż tekst').'</button><div id="spoilercontent" class="alt1">\1</div>', $text);
	}
	else 
	{
		$text = preg_replace('#\[spo\](.*?)\[/spo\]#si', '<button id="spoilerhead" class="thead">'.__('Pokaż tekst').'</button><div id="spoilercontent" class="alt1">'.__('Zaloguj się, żeby zobaczyć zawartość').'</div>', $text);
	}
}
