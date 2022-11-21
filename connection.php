<?php 

include_once "check.php";

class Connection
{
	private static $server = "localhost";
	private static $dbName = "products";
	private static $username = "root";
	private static $password = "";

	private static $connect;
	private static $set_book;
	private static $set_furniture; 
	private static $set_dvd; 
	private static $set_sku_type;

	private static $book;
	private static $furniture;
	private static $dvd;

	public static function Connect()
	{
		static::$book = "book";
		static::$furniture = "furniture";
		static::$dvd = "dvd";

		static::$connect = new mysqli(static::$server, static::$username, static::$password, static::$dbName);

		static::$set_book = static::$connect->prepare("INSERT INTO ".static::$book." (sku, name, price, weight) VALUES (?, ?, ?, ?)");
		static::$set_furniture = static::$connect->prepare("INSERT INTO ".static::$furniture." (sku, name, price, width, height, length) VALUES (?, ?, ?, ?, ?, ?)");
		static::$set_dvd = static::$connect->prepare("INSERT INTO ".static::$dvd." (sku, name, price, size) VALUES (?, ?, ?, ?)");
	
		static::$set_sku_type = static::$connect->prepare("INSERT INTO main_list (sku, type) VALUES (?, ?)");
	}

	public static function SKUexists($sku)
	{
		$result = static::$connect->query("SELECT * FROM main_list WHERE sku = '$sku'");
		return ($result->num_rows == 1); 
	}

	public static function SaveBook($sku, $name, $price, $weight)
	{
		if (static::SKUexists($sku))
			throw new Exception("SKU $sku already exists saving book $name");

		if (checkSKU($sku) && checkName($name) && checkDecimal($price) && checkDecimal($weight))
		{
			static::$set_book->bind_param("ssdd", $sku, $name, $price, $weight);
			static::$set_book->execute();
		}
		else 
			throw new Exception('Wrong params in SaveBook');

		static::$set_sku_type->bind_param("ss", $sku, static::$book);
		static::$set_sku_type->execute();
	}

	public static function SaveFurniture($sku, $name, $price, $width, $height, $length)
	{
		if (static::SKUexists($sku))
			throw new Exception("SKU $sku already exists saving furniture $name");
		if (checkSKU($sku) && checkName($name) && checkDecimal($price) && checkDecimal($width) && checkDecimal($height) && checkDecimal($length))
		{
			static::$set_furniture->bind_param("ssdddd", $sku, $name, $price, $width, $height, $length);
			static::$set_furniture->execute();
		}
		else 
			throw new Exception('Wrong params in SaveFurniture');

		static::$set_sku_type->bind_param("ss", $sku, static::$furniture);
		static::$set_sku_type->execute();
	}

	public static function SaveDVD($sku, $name, $price, $size)
	{
		if (static::SKUexists($sku))
			throw new Exception("SKU $sku already exists saving dvd $name");
		
		if (checkSKU($sku) && checkName($name) && checkDecimal($price) && checkInt($size))
		{
			static::$set_dvd->bind_param("ssdd", $sku, $name, $price, $size);
			static::$set_dvd->execute();
		}
		else 
			throw new Exception('Wrong params in SaveDVD');

		static::$set_sku_type->bind_param("ss", $sku, static::$dvd);
		static::$set_sku_type->execute();
	}

	public static function getType($sku)
	{
		if (!checkSKU($sku))
			throw new Exception('Wrong SKU in getType');
		
		$result = static::$connect->query("SELECT * FROM main_list WHERE sku = '$sku'");
		if ($result->num_rows == 0)
			throw new Exception('Type not found');
		else 
			return $result->fetch_assoc()["type"];
	}	

	public static function get($sku)
	{
		$type = static::getType($sku);
		$result = static::$connect->query("SELECT * FROM $type WHERE sku = '$sku'");
		return $result->fetch_assoc();
	}

	public static function getAllSKU()
	{
		$result = static::$connect->query("SELECT * FROM main_list ORDER BY sku");
		return $result->fetch_all(MYSQLI_ASSOC);
	}

	public static function Delete($sku)
	{
		if (!checkSKU($sku))
			throw new Exception("Wrong SKU in Delete");
		if (!static::SKUexists($sku))
			return false; 
		$type = static::getType($sku);
		static::$connect->query("DELETE FROM main_list WHERE sku = '$sku'");
		static::$connect->query("DELETE FROM $type WHERE sku = '$sku'");
	}

	public static function Close()
	{
		static::$connect->close();
	}
}

Connection::Connect();
?>
