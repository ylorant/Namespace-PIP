<?php

function exception_handler($e)
{
    global $config;
    include(APP_DIR.'controllers/'.$config['error_controller_file']);                                                                                                                                 
    $error = new $config['error_controller']();
    $error->$config['exception_handler']($e);
}