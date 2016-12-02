<?php

return [
	'^shop/([\w-]+)/?$' => ['controller' => 'ShopPages', 'action' => 'categoryList'],
	'^shop/([\w-]+)/([\w+])/?$' => ['controller' => 'ShopPages', 'action' => 'add'],
	'^shop/?$' => ['controller' => 'Shop', 'action' => 'list'],
	'^shop/([\w-]+)/([\w-]+)/?$' => ['controller' => 'shop'],
	'^$' => ['controller' => 'main', 'action' => 'index'],
	'^blog/?$' => ['controller' => 'blog', 'action' => 'index'],
	'^blog/(?P<slug_cat>[\w-]+)/(?P<slug>[\w-]+)/(?P<id>\d+)/?$' =>
		['controller' => 'blog-new', 'action' => 'categoryList'],
];

