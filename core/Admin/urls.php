<?php

return [
	'^' . core\Config::getSettings()['global']['admin_uri'] . '/?$' => [
		'controller' => 'Admin',
		'action' => 'dashboard'
	],
	'^' . core\Config::getSettings()['global']['admin_uri'] . '/(?P<models>[\w-]+)/?$' => [
		'controller' => 'Admin',
		'action' => 'listView'
	],
	'^' . core\Config::getSettings()['global']['admin_uri'] . '/(?P<models>[\w-]+)/(?P<id>\d+)/?$' => [
		'controller' => 'Admin',
		'action' => 'detailView'
	],
];