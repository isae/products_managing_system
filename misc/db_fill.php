<?php
//xml files with catalogues are from Ozon.ru: http://www.ozon.ru/context/partner_xml/
require_once '../db/db_credentials.php';
$files = ["div_bs.xml", "div_fashion.xml", "div_home.xml", "div_soft.xml", "div_tech.xml"];
$file = "div_appliance.xml";
$depth = array();

$GLOBALS['curName'] = '';
$GLOBALS['curDescription'] = '';
$GLOBALS['curPrice'] = 0;
$GLOBALS['curImgUrl'] = '';
$GLOBALS['imageFounded'] = false;
$GLOBALS['curTag'] = '';

$db = mysqli_connect(HOST, USER, PASSWORD, DATABASE, PORT) or die('Could not connect: ' . mysql_error());
mysqli_real_query($db, "SET NAMES 'utf8'");
$stmt = $db->prepare("INSERT INTO products(name, description, price, imageUrl) VALUES (?,?,?,?)");


function startElement($parser, $name, $attrs)
{
    //if($name==)
    global $depth;
    if ($name == 'PRICE') {
        $GLOBALS['curTag'] = 'curPrice';
    }
    if ($name == 'PICTURE') {
        if (!$GLOBALS['imageFounded']) {
            $GLOBALS['curTag'] = 'curImgUrl';
        } else {
            $GLOBALS['curTag'] = '';
        }
        $GLOBALS['imageFounded'] = true;
    }
    if ($name == 'NAME') {
        $GLOBALS['curTag'] = 'curName';

    }
    if ($name == 'DESCRIPTION') {
        $GLOBALS['curTag'] = 'curDescription';
    }

    if (!isset($depth[$parser])) {
        $depth[$parser] = 0;
    }
}

function endElement($parser, $name)
{
    global $stmt;
    if ($name == 'OFFER') {
        if (!$stmt) {
            $ok = false;
        } else {
            if (!$stmt->bind_param("ssis", $GLOBALS['curName'], $GLOBALS['curDescription'], $GLOBALS['curPrice'], $GLOBALS['curImgUrl'])) {
                $ok = false;
            }
            if (!$stmt->execute()) {
                $ok = false;
            }
        }
        $GLOBALS['imageFounded'] = false;
    }
}

function processText($parser, $data)
{
    $GLOBALS[$GLOBALS['curTag']] = $data;
    $GLOBALS['curTag'] = '';
}

foreach ($files as $file) {
    $xml_parser = xml_parser_create();
    xml_set_element_handler($xml_parser, "startElement", "endElement");
    xml_set_character_data_handler($xml_parser, "processText");
    if (!($fp = fopen($file, "r"))) {
        continue;

        //die("could not open XML input");
    }
    $counter = 0;
    while ($data = fread($fp, 4096)) {
        if (!xml_parse($xml_parser, $data, feof($fp))) {
            if ($counter == 3) {
                die(sprintf("XML error: %s at line %d",
                    xml_error_string(xml_get_error_code($xml_parser)),
                    xml_get_current_line_number($xml_parser)));
            } else ++$counter;
        }
    }
    xml_parser_free($xml_parser);
}

?>