<?php

$req = $_SERVER['REQUEST_URI'];
$req = $_SERVER['PHP_SELF'];

if (preg_match('/\.(?:css|html|js|php|jpeg|jpg|png)$/', $req))
{
	return false;
}

switch($req)
{
case '/':
	require __DIR__.'/delete_items.php'; 
        //require __DIR__.'/save_product.php';	
	require __DIR__.'/main_page.php';
	break;
case '/add_product':
	require __DIR__.'/add_product_page.html';
	break;	
default:
	http_response_code(404); 
	break; 
}
?>
