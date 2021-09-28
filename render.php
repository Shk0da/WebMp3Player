<?php

$indexPage = "index.html";
$renderPage = "_index.php";

function renderedHTML($path) {
    ob_start();
    include($path);
    $content = ob_get_contents(); 
    ob_end_clean();
    return $content;
}

function createIndexPage() {
    global $indexPage, $renderPage;
    
    if (file_exists($indexPage )) {
        unlink($indexPage );
    }
    
    file_put_contents($indexPage, renderedHTML($renderPage), FILE_APPEND | LOCK_EX);
}
