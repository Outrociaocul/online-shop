<?php
	function checkSKU($sku)
	{
		return preg_match('/^[a-zA-Z]+[a-zA-Z01-9-]*[a-zA-Z01-9]+$/', $sku);
	}

	function checkName($name)
	{
		return preg_match('/^[a-zA-Z]+[a-zA-Z01-9\s]*[a-zA-Z01-9]+$/', $name);
	}

	function checkDecimal($price)
	{
		return preg_match('/^[1-9]+\d*(\.\d{1,2})?$/', $price);
	}

	function checkInt($price)
	{
		return preg_match('/^[1-9]+\d*$/', $price);
	}
?>
