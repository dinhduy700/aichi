<?php
if (empty($_GET['file'])) http_response_code(400);
$script = 'absolute\path\to\vb-script' . DIRECTORY_SEPARATOR . 'xlstopdf.vbs';
$xlsPath = 'absolute\path\to\share-folder' . DIRECTORY_SEPARATOR . $_GET['file'];
if (!file_exists($xlsPath)) http_response_code(400);
$pdfPath = preg_replace('/(\.xlsx)$/', '.pdf', $xlsPath);
$command = 'wscript "'.$script.'" /ExcelFile:"' . $xlsPath . '" /PdfFile:"'.$pdfPath.'"';
exec($command, $output);