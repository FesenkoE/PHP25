<?php
//function test() {
//    static $x = 0;
//    $x++;
//    echo $x . "\n";
//}
//
//test();
//test();
//
//function tes(&$x, $y, $z = null) {//& - ссылочная переменная
//echo $x ."". $y ."". $z;
//$a = func_num_args();//как правило такая функция пишется без параметров
//$b = func_get_args(); //возвращает переданные get - параметры
//    echo $a;
//    echo $b;
//}
//
//tes($x, 6, 6, 7);
//
//function test3(string  $x, int $y, bool $z) : int {//обязан вернуть int
//    echo $x;
//    echo $z;
//    return $y;
//}
//
//function test4(...$args) {//развертывание - возвращает в аргументы в массиа $args
//
//}
//
////анонимная функция
//$x = function() {
//
//};
//$x();
//
//$question->correct = 1;
//$question->check = function($answer) {
//    if ($answer == 1) {
//        return true;
//    }
//    return false;
//};
//
//foreach ($questions as $question) {
//    $answer = ask();
//    $question->check($answer);
//}
//
//ask("Что такое PHP", function($answer) {
//
//});
//
//function ask($ask, $callback) {
//    echo "bla-bla-bla";
//}
//
////Замыкание
//$z = 15;
//$x = function ($ask, $callback) use($z) {
//
//};
//
////реккурсия
//function test5($items) {
//    foreach ($items as $item) {
//        if ($item['children']) {
//            test5($item['children']);
//        }
//    }
//}
////деление массива пока не определим номер числа, может быть массив [1, 100] и найти позициию числа 25 например
//function search ($number, $items) {
//    $col = count($items)/2;
//    $chunks = array_chunk($items, $col);
//    if($chunks[1][0] == $number || end($chunks[0]) == $number) {
//        return true;
//    }
//    if($chunks[1][0] > $number) {
//        search ($number, $items);
//    }
//}

//function hello()
//{
//    echo "Hello\n";
//}

//hello();
//$name = "John";
//
//$x = function () use ($name) {
//    echo "hello, $name";
//};
//
//$x();

//function test($x, $y)
//{
//    return $x + $y;
//}
//
//echo test(5, 6);
//

//function hello($data)
//{
//    if (is_array($data)){
//        $name = implode(",", $data);
//    } else {
//        $name = $data;
//    }
//    echo "Hello, $name";
//}
//
//hello(["Pavel", "Oleg", "John"]);

//function test()
//{
//    print_r(func_get_args());
//}
//test(1, 1, 1, 1);

//unset($argv[0]);
//$argv = array_values($argv);
//
//
//call_user_func_array('hello', $argv);
//function hello($x, $z)
//{
//    echo $x;
//    echo "\n";
//    echo $z;
//    echo "\n";
//
//    print_r(func_get_args());
//}

//function fibonacci($num)
//{
//    if ($num < 2) {
//        return $num;
//    }
//    return fibonacci($num - 1) + fibonacci($num - 2);
//}
//
//for ($i = 0; $i <= 16; $i++) {
//    echo fibonacci(($i) . ", ");
//}

$x = range(1, 100000);

function _search($items, $number)
{
    $count = count($items) / 2;
    $chunks = array_chunk($items, $count);
    if (end($chunks[0]) == $number || $chunks[1][0] == $number) {
        return true;
    }
    if ($chunks[1][0] > $number) {
        return _search($chunks[0], $number);
    } else {
        return _search($chunks[1], $number);
    }
    return false;
}
$ts = microtime(true);
$result = _search($x, 47);
printf("_search: %.9f seconds\n", (microtime(true) - $ts));

$ts = microtime(true);
array_search(47, $x);
var_dump($result);
printf("array_search: %.9f seconds\n", (microtime(true) - $ts));
