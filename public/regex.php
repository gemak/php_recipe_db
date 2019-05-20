<?php

$text = 'What is PHP?';

if (preg_match('/PHP/i', $text)) {
    echo '$text contains the string "PHP".';
}
else {
    echo '$text does not contain the string "PHP".';
}
