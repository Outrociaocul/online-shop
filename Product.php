<?php

if (!class_exists("Connection")){ include_once "connection.php";}
include_once "check.php"; 

abstract class Product
{
    protected $SKU;
    protected $name;
    protected $price;
    
    public function __construct($SKU, $name, $price)
    {
	$this->setSKU($SKU);
	$this->setName($name);
	$this->setPrice($price);
    }

    public function getSKU( ) {return $this->SKU;}

    public function setSKU($sku) {
    	if (!checkSKU($sku))
		throw new Exception("Wrong SKU");
	else 
        	$this->SKU = $sku;
    }

    public function getName( )
    {
        return $this->name;
    }

    public function setName($name) {
    	if (!checkName($name))
		throw new Exception("Wrong Name");
	else 
        	$this->name = $name;
    }
    
    public function getPrice( )
    {
        return $this->price;
    }    
    
    public function setPrice($price) {
    	if (!checkDecimal($price))
		throw new Exception("Wrong Price");
	else 
        	$this->price = $price;
    }

    public function getFromDB($sku)
    {
	return Connection::get($sku);
    }

    abstract public function saveToDB();
}

class Book extends Product
{
    protected $weight; 

    public function __construct($SKU, $name, $price, $weight)
    {
        parent::__construct($SKU, $name, $price);
	$this->setWeight($weight);
    }

    public function getWeight()
    {
	    return $this->weight;
    }

    public function setWeight($weight)
    {
	    if (!checkDecimal($weight))
		    throw new Exception("Wrong Weight");
	    else 
		    $this->weight = $weight;
    }

    public function saveToDB()
    {
	    Connection::SaveBook($this->SKU, $this->name, $this->price, $this->weight);
    }
}

class Furniture extends Product
{
    protected $width;
    protected $height;
    protected $length;

    public function __construct($SKU, $name, $price, $width, $height, $length)
    {
        parent::__construct($SKU, $name, $price);
	$this->setWidth($width);
	$this->setHeight($height);
	$this->setLength($length);
    }

    public function getWidth()
    {
	    return $this->width;
    }

    public function setWidth($width) {
    	if (!checkDecimal($width))
		throw new Exception("Wrong Width");
	else 
        	$this->width = $width;
    }

    public function getHeight()
    {
	    return $this->height;
    }

    public function setHeight($height) {
    	if (!checkDecimal($height))
		throw new Exception("Wrong Height");
	else 
        	$this->height = $height;
    }

    public function getLength()
    {
	    return $this->length;
    }

    public function setLength($length) {
    	if (!checkDecimal($length))
		throw new Exception("Wrong Length");
	else 
        	$this->length = $length;
    }

    public function saveToDB()
    {
	    Connection::SaveFurniture($this->SKU, $this->name, $this->price, $this->width, $this->height, $this->length);
    }
}

class DVD extends Product
{
	protected $size; 

    public function __construct($SKU, $name, $price, $size)
    {
        parent::__construct($SKU, $name, $price);
	$this->setSize($size);
    }

    public function getSize()
    {
	    return $this->size;
    }

    public function setSize($size) {
    	if (!checkInt($size))
		throw new Exception("Wrong Size");
	else 
        	$this->size = $size;
    }

    public function saveToDB()
    {
	    Connection::SaveDVD($this->SKU, $this->name, $this->price, $this->size);
    }
}
?>
