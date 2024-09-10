--TEST--
Live flat profile is not working if stdout is not a tty
--ENV--
return <<<END
SPX_ENABLED=1
SPX_FP_LIVE=1
END;
--FILE--
<?php
// if live flat profile is active, this output will not be printed to stdout
echo 'Normal output';
?>
--EXPECTF--
Normal output


 [38;5;244mWall time          [0m [1;30m│[0m [38;5;244mMemory usage       [0m [1;30m│[0m
 [38;5;244mInc.    [0m [1;30m│[0m [38;5;244m*Exc.   [0m [1;30m│[0m [38;5;244mInc.    [0m [1;30m│[0m [38;5;244mExc.    [0m [1;30m│[0m [38;5;244mCalled  [0m [1;30m│[0m [38;5;244mFunction[0m
[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────┼[0m[1;30m──────────[0m
%s [1;30m│[0m   %s [1;30m│[0m      %s [1;30m│[0m      %s [1;30m│[0m        %s [1;30m│[0m [38;5;244m%s/spx_009.php[0m