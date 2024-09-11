--TEST--
Auto start disabled, builtins traced, trace report & several traced spans
--ENV--
return <<<END
SPX_ENABLED=1
SPX_AUTO_START=0
SPX_METRICS=zo
SPX_BUILTINS=1
SPX_REPORT=trace
SPX_TRACE_FILE=/dev/stdout
SPX_TRACE_SAFE=1
END;
--FILE--
<?php
echo "Normal output\n";

$objects = [];

function foo() {
    global $objects;

    $objects[] = new stdClass();
    bar();
    $objects[] = new stdClass();
}

function bar() {
    global $objects;

    $objects[] = new stdClass();
    time();

    spx_profiler_start();

    $objects[] = new stdClass();
    time();
}

for ($i = 0; $i < 3; $i++) {
    foo();
    $objects[] = new stdClass();
    spx_profiler_stop();
}

?>
--EXPECTF--
Normal output
 [38;5;244mZE object count               [0m [1;30m│[0m
 [38;5;244mCum.    [0m [1;30m│[0m [38;5;244mInc.    [0m [1;30m│[0m [38;5;244mExc.    [0m [1;30m│[0m [38;5;244mDepth   [0m [1;30m│[0m [38;5;244mFunction[0m
[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        1 [1;30m│[0m [38;5;244m+%s/spx_auto_start_002.php[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        2 [1;30m│[0m [38;5;244m +foo[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        3 [1;30m│[0m [38;5;244m  +bar[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        4 [1;30m│[0m [38;5;244m   +spx_profiler_start[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        4 [1;30m│[0m [38;5;244m   -spx_profiler_start[0m
        1 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        4 [1;30m│[0m [38;5;244m   +time[0m
        1 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        4 [1;30m│[0m [38;5;244m   -time[0m
        1 [1;30m│[0m        1 [1;30m│[0m        1 [1;30m│[0m        3 [1;30m│[0m [38;5;244m  -bar[0m
        2 [1;30m│[0m        2 [1;30m│[0m        1 [1;30m│[0m        2 [1;30m│[0m [38;5;244m -foo[0m
        3 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        2 [1;30m│[0m [38;5;244m +spx_profiler_stop[0m
        3 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        2 [1;30m│[0m [38;5;244m -spx_profiler_stop[0m
        3 [1;30m│[0m        3 [1;30m│[0m        1 [1;30m│[0m        1 [1;30m│[0m [38;5;244m-%s/spx_auto_start_002.php[0m

SPX trace file: /dev/stdout
 [38;5;244mZE object count               [0m [1;30m│[0m
 [38;5;244mCum.    [0m [1;30m│[0m [38;5;244mInc.    [0m [1;30m│[0m [38;5;244mExc.    [0m [1;30m│[0m [38;5;244mDepth   [0m [1;30m│[0m [38;5;244mFunction[0m
[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        1 [1;30m│[0m [38;5;244m+%s/spx_auto_start_002.php[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        2 [1;30m│[0m [38;5;244m +foo[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        3 [1;30m│[0m [38;5;244m  +bar[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        4 [1;30m│[0m [38;5;244m   +spx_profiler_start[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        4 [1;30m│[0m [38;5;244m   -spx_profiler_start[0m
        1 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        4 [1;30m│[0m [38;5;244m   +time[0m
        1 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        4 [1;30m│[0m [38;5;244m   -time[0m
        1 [1;30m│[0m        1 [1;30m│[0m        1 [1;30m│[0m        3 [1;30m│[0m [38;5;244m  -bar[0m
        2 [1;30m│[0m        2 [1;30m│[0m        1 [1;30m│[0m        2 [1;30m│[0m [38;5;244m -foo[0m
        3 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        2 [1;30m│[0m [38;5;244m +spx_profiler_stop[0m
        3 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        2 [1;30m│[0m [38;5;244m -spx_profiler_stop[0m
        3 [1;30m│[0m        3 [1;30m│[0m        1 [1;30m│[0m        1 [1;30m│[0m [38;5;244m-%s/spx_auto_start_002.php[0m

SPX trace file: /dev/stdout
 [38;5;244mZE object count               [0m [1;30m│[0m
 [38;5;244mCum.    [0m [1;30m│[0m [38;5;244mInc.    [0m [1;30m│[0m [38;5;244mExc.    [0m [1;30m│[0m [38;5;244mDepth   [0m [1;30m│[0m [38;5;244mFunction[0m
[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        1 [1;30m│[0m [38;5;244m+%s/spx_auto_start_002.php[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        2 [1;30m│[0m [38;5;244m +foo[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        3 [1;30m│[0m [38;5;244m  +bar[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        4 [1;30m│[0m [38;5;244m   +spx_profiler_start[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        4 [1;30m│[0m [38;5;244m   -spx_profiler_start[0m
        1 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        4 [1;30m│[0m [38;5;244m   +time[0m
        1 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        4 [1;30m│[0m [38;5;244m   -time[0m
        1 [1;30m│[0m        1 [1;30m│[0m        1 [1;30m│[0m        3 [1;30m│[0m [38;5;244m  -bar[0m
        2 [1;30m│[0m        2 [1;30m│[0m        1 [1;30m│[0m        2 [1;30m│[0m [38;5;244m -foo[0m
        3 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        2 [1;30m│[0m [38;5;244m +spx_profiler_stop[0m
        3 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        2 [1;30m│[0m [38;5;244m -spx_profiler_stop[0m
        3 [1;30m│[0m        3 [1;30m│[0m        1 [1;30m│[0m        1 [1;30m│[0m [38;5;244m-%s/spx_auto_start_002.php[0m

SPX trace file: /dev/stdout