<?php

if (!class_exists("Connection")) {include "connection.php";}

try
{
	$items = $_POST['checkboxes'];
	if (empty($items))
	{
		return; 
	}
	else 
	{
		foreach ($items as $item)
		{
			Connection::Delete($item);
		}
	}
}
catch(Exception $e)
{
	echo "Error deleting items: $e";
}
?>
