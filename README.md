#ChillDebug

Library to do from debug something easy (hopefully)

#IMPORTANT

This library is WIP.

Not finished, MUST be improved.

Feel free to collaborate or give feedback.

#Usage

```
include_once(__DIR__ . '/../vendor/autoload.php');

function a_test($str)
{
    echo "\nHi: $str";
}

$debugger = new \ChillDebug\Debugger();

$debugger->enable();

a_test('friend');

$debugger->gerCodeCoverageInformation();
$debugger->disable();
```