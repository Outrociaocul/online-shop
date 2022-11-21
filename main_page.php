<?php
	if (!class_exists("Connection")) {include "connection.php";}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Gleb's shop
        </title>
        <link href="main_page.css" rel="stylesheet" type="text/css" media="all">
        <link href="buttons.css" rel="stylesheet" type="text/css" media="all">
    	<script defer src="/main_page.js"></script>
        <script src="https://cdn.anychart.com/releases/8.7.1/js/anychart-base.min.js"></script>
<script>
        anychart.onDocumentReady(function() {
        var data = {
  header: ["Type of product", "Count in store"],
  rows: [
<?php
            $prods = Connection::getAllSKU();
            $book = 0; 
            $fur = 0; 
            $dvd = 0;
            foreach ($prods as $prod)
		{
			$sku = $prod["sku"];
			$type = Connection::getType($sku);
            switch ($type)
                    {
                        case "book":
                            $book = $book + 1; 
                            break; 
                        case "furniture":
                            $fur = $fur + 1; 
                            break; 
                        case "dvd":
                            $dvd = $dvd + 1; 
                            break; 
                    }
	    }
echo "
    [\"Book\", $book],
    [\"Furniture\", $fur],
    [\"DVD\", $dvd]";
    ?>
]};

var chart = anychart.column();
chart.data(data);
chart.title("Number of products in store");
chart.container("chart");
chart.draw();
});
            </script>
</head>
    <body>
        <div id="header">
            <div id="pl"><span>Product List</span></div>
            <div id="bts">
		        <a href="add_product"><button class="neo-button green-button" id="add">ADD</button></a>
                <button class="neo-button red-button" id="delete-product-btn" onclick="deleteItems()">MASS DELETE</button>
            </div>
            
        </div>
        
        <hr />
		<form id="deleteForm" method="POST" action="/">
        <div id="prodlist">
<?php
		$products = Connection::getAllSKU();
		
		foreach ($products as $p)
		{
			$sku = $p["sku"];
			$type = Connection::getType($sku); 
			$product = Connection::get($sku);

    			$desc = "";
                    switch ($type)
                    {
                        case "book":
                            $weight = $product["weight"];
                            $desc = "Weight: $weight KG";
                            break; 
                        case "dvd":
                            $size = $product["size"];
                            $desc = "Size: $size MB";
                            break; 
                        case "furniture":
                            $width = $product["width"];
                            $height = $product["height"];
                            $length = $product["length"];
                            $desc = "Dimensions: {$width}x{$height}x{$length} CM";
                            break; 
                    }
		
		
			

                    $name = $product["name"];
                    $price = $product["price"];

                    echo "<div class=\"product-div\"> 
                    <input type=\"checkbox\" name=\"checkboxes[]\" class=\"delete-checkbox\" value=\"$sku\"><br>
		    <span>$sku</span><br>
                    <span>$name</span><br>
                    <span>$price $</span><br>
		    <span>$desc</span><br>
                    </div>
                    ";
		}
            ?>
        </div>
    </form>
<div style="width:100%; display:flex; justify-content:space-around;">
    <div id="chart" style="width: 50%; height: 500px;"></div>
</div>
    </body>
</html>
