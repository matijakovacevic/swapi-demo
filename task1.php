#!/usr/bin/php

<?php

/**
 * Script can be ran from the command line (e.g. from the current directory) like so:
 *
 * ./task1.php "repeat([1,2,3])"
 * ./task1.php 'reformat("TyPEqaSt DeveLoper TeST")'
 * ./task1.php "next_binary_number([1,1])"
 *
 *
 * test cases:
 *
 *  repeat([1,2,3]) // [1,2,3,1,2,3,1,2,3]
 *  reformat("TyPEqaSt DeveLoper TeST"), // Typqst dvlpr tst
 *  next_binary_number([1,0]), // 11
 *  next_binary_number([1,1]), // 100
 *  next_binary_number([1,1,0]), // 111
 *  next_binary_number([1,0,0,0,0,0,0,0,0,1]), // 1000000010
 */


/**
 * Repeats the given array three times and flattens it into array
 * Hasn't been tested with multidimensional array, as it was not specified in the task
 *
 * For given input "[1,2,3]" the function returns "[1,2,3,1,2,3,1,2,3]"
 */
function repeat(array $values): array {
    $flattened = [];

    $array = array_fill(0, 3, $values);

    foreach ($array as $value) {
        $flattened = array_merge($flattened, $value);
    }

    return $flattened;
}

/**
 * Reformats input, removing vowels, making first letter uppercase, everything else lowercase
 *
 * For given input "TyPEqaSt DeveLoper TeST" the function returns "Typqst dvlpr tst"
 */
function reformat(string $string): string {
    $string = str_ireplace(array('a','e','i','o','u'), '', $string);

    return ucfirst(strtolower($string));
}

/**
 * Returns next binary number as an array
 */
function next_binary_number(array $nextBinary): array {
    $zeroFound = false;

    for ($i=count($nextBinary)-1; $i>=0; $i--) {
        $value = (int) $nextBinary[$i];

        $nextBinary[$i] = $value === 1 ? 0 : 1;

        if ($value === 0) {
            $zeroFound = true;
            break;
        }
    }

    if ($zeroFound === false) {
        // no zeros found, prepend 1 to the beginning
        array_unshift($nextBinary, 1);
    }

    return $nextBinary;
}

function consolePrint($value): string {
    if (is_array($value)) {
        return sprintf('[%s]', implode(',', $value));
    }

    return $value;
}

$functionName = $argv[1] ?? '';

if ($functionName === '') {
    echo sprintf("Please specify an argument (function call with parameters, as string)". PHP_EOL);
    exit;
}

eval("echo consolePrint($functionName) . PHP_EOL;");

echo PHP_EOL;
