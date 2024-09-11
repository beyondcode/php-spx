--TEST--
Auto start disabled, fp report & span report keys printed (null expected)
--ENV--
return <<<END
SPX_ENABLED=1
SPX_AUTO_START=0
SPX_METRICS=zo
SPX_BUILTINS=0
SPX_REPORT=trace
SPX_TRACE_FILE=/dev/stdout
SPX_TRACE_SAFE=1
END;
--FILE--
<?php
function foo() {
    bar();
}

function bar() {
    time();
}

for ($i = 0; $i < 3; $i++) {
    spx_profiler_start();
    foo();
    $key = spx_profiler_stop();
    echo "Report key: ", var_export($key, true), "\n";
}

?>
--EXPECTF--
[38;5;244mZE object count               [0m [1;30m│[0m
 [38;5;244mCum.    [0m [1;30m│[0m [38;5;244mInc.    [0m [1;30m│[0m [38;5;244mExc.    [0m [1;30m│[0m [38;5;244mDepth   [0m [1;30m│[0m [38;5;244mFunction[0m
[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        1 [1;30m│[0m [38;5;244m+%s/spx_auto_start_006.php[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        2 [1;30m│[0m [38;5;244m +foo[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        3 [1;30m│[0m [38;5;244m  +bar[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        3 [1;30m│[0m [38;5;244m  -bar[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        2 [1;30m│[0m [38;5;244m -foo[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        1 [1;30m│[0m [38;5;244m-%s/spx_auto_start_006.php[0m

SPX trace file: /dev/stdout
Report key: NULL
 [38;5;244mZE object count               [0m [1;30m│[0m
 [38;5;244mCum.    [0m [1;30m│[0m [38;5;244mInc.    [0m [1;30m│[0m [38;5;244mExc.    [0m [1;30m│[0m [38;5;244mDepth   [0m [1;30m│[0m [38;5;244mFunction[0m
[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        1 [1;30m│[0m [38;5;244m+%s/spx_auto_start_006.php[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        2 [1;30m│[0m [38;5;244m +foo[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        3 [1;30m│[0m [38;5;244m  +bar[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        3 [1;30m│[0m [38;5;244m  -bar[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        2 [1;30m│[0m [38;5;244m -foo[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        1 [1;30m│[0m [38;5;244m-%s/spx_auto_start_006.php[0m

SPX trace file: /dev/stdout
Report key: NULL
 [38;5;244mZE object count               [0m [1;30m│[0m
 [38;5;244mCum.    [0m [1;30m│[0m [38;5;244mInc.    [0m [1;30m│[0m [38;5;244mExc.    [0m [1;30m│[0m [38;5;244mDepth   [0m [1;30m│[0m [38;5;244mFunction[0m
[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        1 [1;30m│[0m [38;5;244m+%s/spx_auto_start_006.php[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        2 [1;30m│[0m [38;5;244m +foo[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        3 [1;30m│[0m [38;5;244m  +bar[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        3 [1;30m│[0m [38;5;244m  -bar[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        2 [1;30m│[0m [38;5;244m -foo[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        1 [1;30m│[0m [38;5;244m-%s/spx_auto_start_006.php[0m

SPX trace file: /dev/stdout
Report key: NULL