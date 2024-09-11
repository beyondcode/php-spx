--TEST--
Userland stats (PHP 7.0+)
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
SPX_METRICS=zuc,zuf,zuo
SPX_FP_FOCUS=zuo
END;
--FILE--
<?php

eval(<<<EOS
class foo {
  function __construct()
  {
    \$this->a = 0;
    \$this->b = 0;
  }
}
EOS
);

function bar()
{
    $a = 0;
    $b = 0;
}

function baz()
{
    $a = 0;
    $b = 0;
    $c = 0;
}

$a = 0;
$b = 0;
$c = 0;
$d = 0;

?>
--EXPECTF--
[38;5;244mZE class count     [0m [1;30m│[0m [38;5;244mZE func. count     [0m [1;30m│[0m [38;5;244mZE opcodes count   [0m [1;30m│[0m
 [38;5;244mInc.    [0m [1;30m│[0m [38;5;244mExc.    [0m [1;30m│[0m [38;5;244mInc.    [0m [1;30m│[0m [38;5;244mExc.    [0m [1;30m│[0m [38;5;244mInc.    [0m [1;30m│[0m [38;5;244m*Exc.   [0m [1;30m│[0m [38;5;244mCalled  [0m [1;30m│[0m [38;5;244mFunction[0m
[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────[0m
        0 [1;30m│[0m        0 [1;30m│[0m        2 [1;30m│[0m        2 [1;30m│[0m       12 [1;30m│[0m       12 [1;30m│[0m        1 [1;30m│[0m [38;5;244m::zend_compile_file[0m
        1 [1;30m│[0m        1 [1;30m│[0m        1 [1;30m│[0m        1 [1;30m│[0m        5 [1;30m│[0m        5 [1;30m│[0m        1 [1;30m│[0m [38;5;244m::zend_compile_string[0m
        1 [1;30m│[0m        0 [1;30m│[0m        1 [1;30m│[0m        0 [1;30m│[0m        5 [1;30m│[0m        0 [1;30m│[0m        1 [1;30m│[0m [38;5;244m%s/spx_%s.php[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        1 [1;30m│[0m [38;5;244m%s/spx_%s.php(%d) : eval()'d code[0m
        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        0 [1;30m│[0m        1 [1;30m│[0m [38;5;244m::php_request_shutdown[0m