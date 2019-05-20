<?php
echo 'You are viewing ' . $_SERVER['REQUEST_URI'];
echo '<br>stripped: ' . ltrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');
