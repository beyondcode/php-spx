--TEST--
GC is traced (PHP 8.0+)
--SKIPIF--
<?php
if (
    version_compare(PHP_VERSION, '8.0') < 0
) {
    die('skip this test is for PHP 8.0+ only');
}
?>
--ENV--
return <<<END
SPX_ENABLED=1
SPX_BUILTINS=1
SPX_METRICS=zgr,zgb,zgc
SPX_FP_FOCUS=zgb
END;
--FILE--
<?php

function f() {
    $a = new stdClass;
    $b = new stdClass;

    $a->b = $b;
    $b->a = $a;
}

for ($i = 0; $i < 50 * 1000; $i++) {
    f();
}

?>
--EXPECTF--
[38;5;244mZE GC runs         [0m [1;30m│[0m [38;5;244mZE GC root buffer  [0m [1;30m│[0m [38;5;244mZE GC collected    [0m [1;30m│[0m
 [38;5;244mInc.    [0m [1;30m│[0m [38;5;244mExc.    [0m [1;30m│[0m [38;5;244mInc.    [0m [1;30m│[0m [38;5;244m*Exc.   [0m [1;30m│[0m [38;5;244mInc.    [0m [1;30m│[0m [38;5;244mExc.    [0m [1;30m│[0m [38;5;244mCalled  [0m [1;30m│[0m [38;5;244mFunction[0m
[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────[0m
        9 [1;30m│[0m        0 [1;30m│[0m    10.0K [1;30m│[0m   100.0K [1;30m│[0m    90.0K [1;30m│[0m        0 [1;30m│[0m    50.0K [1;30m│[0m [38;5;244mf[0m
        9 [1;30m│[0m        0 [1;30m│[0m    10.0K [1;30m│[0m        0 [1;30m│[0m    90.0K [1;30m│[0m        0 [1;30m│[0m        1 [1;30m│[0m [38;5;244m%s/spx_%s.php[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        1 [1;30m│[0m [38;5;244m::zend_compile_file[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        1 [1;30m│[0m [38;5;244m::php_request_shutdown[0m
        9 [1;30m│[0m        9 [1;30m│[0m   -90.0K [1;30m│[0m   -90.0K [1;30m│[0m    90.0K [1;30m│[0m    90.0K [1;30m│[0m        9 [1;30m│[0m [38;5;244m::gc_collect_cycles[0m