<?php
  if(!isset($_POST['method']) || !$method = $_POST['method']) exit;
  if(!isset($_POST['value']) || !$value = $_POST['value']) exit;
  if(!isset($_POST['target']) || !$target = $_POST['target']) exit;

  include_once "xmlresponse.php"; 
  include_once "check.php";

  if (!class_exists("Connection")) {include "connection.php";}

  $passed = FALSE;
  $retval = "";

  switch($method)
  {
    case 'checkSKU':
        if (!checkSKU($value))
        {
            $retval = 'SKU is invalid. It should contain only letters, digits and dash and look like MYSKU-1234';
        }
        else 
	{
	    if (Connection::SKUexists($value))
            	$retval = 'SKU already exists. Please try again';
	    else 
	    { 
            	$retval = "SKU is valid";
            	$passed = TRUE;
	    
	    }
	}
        break;

    case 'checkName':
	    if (!checkName($value))
        {
            $retval = 'Name is invalid. It should contain only letters and spaces and look like \'War and Peace\'';
        }
        else 
        {
            $retval = "Name is valid";
            $passed = TRUE;
        }
        break;

    case 'checkPrice':
        if (!checkDecimal($value))
        {
            $retval = 'Price is invalid. It should contain only digits and dot and look like 10 or 20.50 or 15.2';
        }
        else 
        {
            $retval = "Price is valid";
            $passed = TRUE;
        }
        break;

    case 'checkWidth':
        if (!checkDecimal($value))
        {
            $retval = 'Width is invalid. It should contain only digits and dot and look like 10 or 20.50 or 15.2';
        }
        else 
        {
            $retval = "Width is valid";
            $passed = TRUE;
        }
        break;
	
    case 'checkHeight':
        if (!checkDecimal($value))
        {
            $retval = 'Height is invalid. It should contain only digits and dot and look like 10 or 20.50 or 15.2';
        }
        else 
        {
            $retval = "Height is valid";
            $passed = TRUE;
        }
        break;

    case 'checkLength':
        if (!checkDecimal($value))
        {
            $retval = 'Length is invalid. It should contain only digits and dot and look like 10 or 20.50 or 15.2';
        }
        else 
        {
            $retval = "Length is valid";
            $passed = TRUE;
        }
        break;

    case 'checkWeight':
        if (!checkDecimal($value))
        {
            $retval = 'Weight is invalid. It should contain only digits and dot and look like 10 or 20.50 or 15.2';
        }
        else 
        {
            $retval = "Weight is valid";
            $passed = TRUE;
        }
        break;

    case 'checkSize':
        if (!checkInt($value))
        {
            $retval = 'Size is invalid. It should contain only digits and look like 700';
        }
        else 
        {
            $retval = "Size is valid";
            $passed = TRUE;
        }
        break;
    default:

      exit;

  }

  include "class.xmlresponse.php";
  $xml = new xmlResponse();
  $xml->start();
  
  $res = $passed ? "true" : "false";
  // set the response text

  $xml->command('setcontent', [
    'target' => "rsp_{$target}",
    'content' => $retval."<input type=\"checkbox\" class=\"secret\" style=\"display:none;\" value=\"$res\">"
    //'content' => htmlentities($retval)
  ]);

  if($passed) {
    // set the message colour to green and the checkbox to checked

    $xml->command('setstyle', [
      'target' => "rsp_{$target}",
      'property' => 'color',
      'value' => 'green'
    ]);

  } else {
    // set the message colour to red, the checkbox to unchecked and focus back on the field

    $xml->command('setstyle', [
      'target' => "rsp_{$target}",
      'property' => 'color',
      'value' => 'red'
    ]);
    $xml->command('focus', [
      'target' => $target
    ]);

  }

  $xml->end();

  exit;
?>
