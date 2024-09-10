--TEST--
Flat profile: cycle depth
--ENV--
return <<<END
SPX_ENABLED=1
SPX_METRICS=ze
SPX_FP_FOCUS=ze
END;
--FILE--
<?php

function foo() {
    trigger_error('');

    if (count(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)) < 6) {
        bar();
    }
}

function bar() {
    trigger_error('');
    for ($i = 0; $i < 17; $i++) {
        foo();
    }
}

error_reporting(0);

trigger_error('');
for ($i = 0; $i < 13; $i++) {
    bar();
}

?>
--EXPECTF--
 [38;5;244mZE error count     [0m [1;30m│[0m
 [38;5;244mInc.    [0m [1;30m│[0m [38;5;244m*Exc.   [0m [1;30m│[0m [38;5;244mCalled  [0m [1;30m│[0m [38;5;244mFunction[0m
[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────[0m
    71.8K [1;30m│[0m    67.8K [1;30m│[0m    67.8K [1;30m│[0m [38;5;244m2@foo[0m
    71.8K [1;30m│[0m     4.0K [1;30m│[0m     4.0K [1;30m│[0m [38;5;244m2@bar[0m
    71.8K [1;30m│[0m        1 [1;30m│[0m        1 [1;30m│[0m [38;5;244m%s/spx_013.php[0m