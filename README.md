#ChillDebug

Library to do from debug something easy (hopefully)

#IMPORTANT

This library is WIP.

Not finished, MUST be improved.

Feel free to collaborate or give feedback.

#Usage

```
<?php
include_once(__DIR__ . '/vendor/autoload.php');

function a_test($str)
{
    echo "\nHi: $str";
}

function b_test($str)
{
    echo "\nHi: $str";
}

function c_test($str)
{
    echo "\nHi: $str";
}
$config = new \ChillDebug\Configuration();
$config->dumpDebugFiles = true;
$config->generateHtmlView = true;
//$config->reportsPath = '/ABSOLUTE/PATH/TO/DUMP/FILES';
$debugger = new \ChillDebug\Debugger();

$debugger->enable();

include 'test2.php';

$string = ' friend';
a_test($string);
a_test($a);
a_test(TEST);


$str = "Xdebug";
function ret_ord( $c )
{
    return ord( $c );
}

foreach ( str_split( $str ) as $char )
{
    echo $char, ": ", ret_ord( $char ), "\n";
}

$debugger->disable();
```

Working with html templates
---------------------------

To register the url handler for phpstorm copy the file *phpstorm-url-handler.sh* to:
*/usr/local/bin/phpstorm-url-handler* and give execution permissions

Register URL handler
The last bit is to tell your OS how to handle *phpstorm://* URLs.
Ideally you set this on the operating system level.
I found this [article](http://pla.nette.org/en/how-open-files-in-ide-from-debugger#toc-kde-4) for KDE4 and Gnome.
If you do not get things to work, you can also register a custom handler in your browser.

For firefox, go to about:config and then Right-click -> New -> Boolean -> Name: network.protocol-handler.expose.pstorm -> Value -> false. Then click on a link and select the pstorm-handler script. (If you got the last bit wrong, go to Preferences > Applications and look for pstorm in there).

For others: http://stackoverflow.com/questions/7087728/custom-protocol-handler-in-chrome
More info: https://askubuntu.com/questions/527166/how-to-set-subl-protocol-handler-with-unity