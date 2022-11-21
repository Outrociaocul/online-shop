<?php

//if (!array_key_exists("productType", $data))
//	return; 

if (!class_exists("Product")) {include "Product.php";}
if (!class_exists("Connection")) {include "connection.php";}

$product = null;
$data = json_decode(file_get_contents('php://input'), true);

try
{
switch ($data["type"])
{
case "dvd":
    $product = new DVD($data["sku"], $data["name"], $data["price"], $data["size"]);
    break; 
  case "book":
    $product = new Book($data["sku"], $data["name"], $data["price"], $data["weight"]);
    break; 
  case "furniture":
    $product = new Furniture($data["sku"], $data["name"], $data["price"], $data["width"], $data["height"], $data["length"]);
    break;
  default:
    throw new Exception('Wrong product type');
   break; 
}
$product->saveToDB();
echo "OK";
}
catch(Exception $e)
{
	echo "Failed saving product: Please ensure that every field is filled and valid. $e"; 
}
?>
