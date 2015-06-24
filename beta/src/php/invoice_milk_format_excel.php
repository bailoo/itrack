<?php

// place this code inside a php file and call it f.e. "download.php"
//$path = $_SERVER['DOCUMENT_ROOT']."/vts/beta/src/php/upload_rawmilk_invoice/format/"; // change the path to fit your websites document structure
$path = "/var/www/html/vts/beta/src/php/upload_rawmilk_invoice/format/";
$fullPath = $path.$_GET['download_file'];
//echo $fullPath;
if ($fd = fopen ($fullPath, "r")) {
    $fsize = filesize($fullPath);
    $path_parts = pathinfo($fullPath);
    $ext = strtolower($path_parts["extension"]);
	//echo "<br>".$ext;
    switch ($ext) {
        case "xlsx":
        header("Content-type: application/vnd.ms-excel"); // add here more headers for diff. extensions
        header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
        break;
        default;
        header("Content-type: application/octet-stream");
        header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
    }
    header("Content-length: $fsize");
    header("Cache-control: private"); //use this to open files directly
    while(!feof($fd)) {
        $buffer = fread($fd, 2048);
        echo $buffer;
    }
}
fclose ($fd);
exit;
// example: place this kind of link into the document where the file download is offered:
// <a href="invoice_milk_format_excel.php?download_file=raw_milk_format.xlsx">Download here</a>
?>