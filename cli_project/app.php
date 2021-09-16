<?php

if (PHP_OS !== 'Linux') {
    echo "\e[31mThe library only works on Linux\n";

    die();
}

spl_autoload_register(function($sClassName)
{
    $sClassFile = __DIR__.'/';

    if ( file_exists($sClassFile.'/'.str_replace('\\', '/', $sClassName).'.php') )
    {
        require_once($sClassFile.'/'.str_replace('\\', '/', $sClassName).'.php');
        return;
    }

    $arClass = explode('\\', strtolower($sClassName));
    foreach($arClass as $sPath )
    {
        $sClassFile .= '/'.ucfirst($sPath);
    }
    $sClassFile .= '.php';
    if (file_exists($sClassFile))
    {
        require_once($sClassFile);
    }
});

try {

    $controller = new \Core\Controller($argv);

} catch (\Exception $e) {

    echo $e->getMessage().PHP_EOL;
}
