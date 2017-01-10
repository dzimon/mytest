<?php

return [
	'^$' => ['controller' => 'main', 'action' => 'index'],
	// Shop URLs
	'^shop/([\w-]+)/?$' => ['controller' => 'Shop', 'action' => 'CatListView'],
	'^shop/([\w-]+)/([\w+])/?$' => ['controller' => 'Shop', 'action' => 'add'],
	'^shop/?$' => ['controller' => 'Shop', 'action' => 'listView'],
	'^shop/([\w-]+)/([\w-]+)/?$' => ['controller' => 'shop'],
	// Shop URLs
	'^blog/?$' => ['controller' => 'blog', 'action' => 'index'],
	'^blog/(?P<slug_cat>[\w-]+)/(?P<slug>[\w-]+)/(?P<id>\d+)/?$' =>
		['controller' => 'blog-new', 'action' => 'categoryList'],
];

