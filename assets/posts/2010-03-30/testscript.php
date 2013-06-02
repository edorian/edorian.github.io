<?php

function typehint($level, $message) {
    if($level == E_RECOVERABLE_ERROR && preg_match('/^Argument (\d)+ passed to (?:(\w+)::)?(\w+)\(\) must be an instance of (\w+), (\w+) given/', $message, $match)) {
        if($match[4] == $match[5]) {
            return true;
        }
    }
    return false;
}

set_error_handler("typehint");


class withHints {

    public function a(string $a) {
    }

    public function b(string $a, integer $b, boolean $c) {
    }
}

class withoutHints {

    public function a($a) {
    }

    public function b($a, $b, $c) {
    }

}

echo "\n";

$time = microtime(true);
$x = new withoutHints();
for($i = 1000000; $i; --$i) {
    $x->a("string");
}
$time = microtime(true) - $time;
echo "Without Hints, one param: ".round($time, 3)."\n";

$time = microtime(true);
$x = new withoutHints();
for($i = 1000000; $i; --$i) {
    $x->b("string", 1, true);
}
$time = microtime(true) - $time;
echo "Without Hints, three params: ".round($time, 3)."\n";

$time = microtime(true);
$x = new withHints();
for($i = 1000000; $i; --$i) {
    $x->a("string");
}
$time = microtime(true) - $time;
echo "With Hints, one param: ".round($time, 3)."\n";

$time = microtime(true);
$x = new withHints();
for($i = 1000000; $i; --$i) {
    $x->b("string", 1, true);
}
$time = microtime(true) - $time;
echo "With Hints, three params: ".round($time, 3)."\n";

