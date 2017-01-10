<?php

function pre($args)
{
	echo '<pre>' . print_r($args, true) . '</pre>';
}

function slugField($str)
{
	
	return $str;
}