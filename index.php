<?php
//$x = "Hello";
//$y = "world";
//$z = "$x $y";
//printf("%s %s!", $x, $y);
/*$letters = array('яблоко');
$fruit   = array('я');

$output  = str_replace($letters, $fruit);
echo $output;
/*
 $email = "@mail.com";
if(!strpos('@', $mail) === false) {

}
*/
$hello = "Hello world";
echo strtolower($hello) . "\n";

echo strtoupper($hello) . "\n";
/*php index.php  запуск в консоли*/

$name = "John";
$output = <<<EOL
    Hello, my name is $name.
    I live in Kyi.
EOL
;

echo $output;
