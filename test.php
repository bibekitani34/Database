<?php

$val = "Province - 192";
$reversedParts = explode(' - ', strrev($val), 2);
$province = strrev($reversedParts[1]);
$zip = strrev($reversedParts[0]);

?>