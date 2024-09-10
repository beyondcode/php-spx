--TEST--
Flat profile: inc. sort
--ENV--
return <<<END
SPX_ENABLED=1
SPX_METRICS=zo,ze
SPX_FP_FOCUS=ze
SPX_FP_INC=1
END;
--FILE--
<?php

function foo() {
    global $objects;
    $o = new stdClass;
    $objects[] = $o;
    trigger_error('');
}

function bar() {
    global $objects;
    $o = new stdClass;
    $objects[] = $o;
    trigger_error('');
    for ($i = 0; $i < 30; $i++) {
        foo();
    }
}

error_reporting(0);
$objects = [];
trigger_error('');
for ($i = 0; $i < 10; $i++) {
    bar();
}

$objects = [];

?>
--EXPECTF--
 [38;5;244mZE object count    [0m [1;30m│[0m [38;5;244mZE error count     [0m [1;30m│[0m
 [38;5;244mInc.    [0m [1;30m│[0m [38;5;244mExc.    [0m [1;30m│[0m [38;5;244m*Inc.   [0m [1;30m│[0m [38;5;244mExc.    [0m [1;30m│[0m [38;5;244mCalled  [0m [1;30m│[0m [38;5;244mFunction[0m
[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────[0m
%s     0 [1;30m│[0m     -310 [1;30m│[0m      311 [1;30m│[0m        1 [1;30m│[0m        1 [1;30m│[0m [38;5;244m%s/spx_012.php[0m
%s    310 [1;30m│[0m       10 [1;30m│[0m      310 [1;30m│[0m       10 [1;30m│[0m       10 [1;30m│[0m [38;5;244mbar[0m
%s    300 [1;30m│[0m      300 [1;30m│[0m      300 [1;30m│[0m      300 [1;30m│[0m      300 [1;30m│[0m [38;5;244mfoo[0m