<?php

require_once __DIR__ . '/vendor/autoload.php';



/**
 * emits a message and flusheds output buffers
 * helper method for streaming chunked content in the router
 * @param  String $message message to be chunked out
 * @return void
 */
function emitFlush($message = null){
    if($message) {
        echo $message."\r\n";
    }

    flush();
    ob_flush();
};

