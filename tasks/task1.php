<?php
require_once('../library/ask.php');// <--- не трогать.

/*
	Опрос пользователя.

	Сформировать массив данных или объект stcClass (по вкусу), о пользователе. 
	Данные оторые необходимо запросить: email, возраст (целое число), список языков программирования, которые интересны пользователю.

	Для формирования вопроса пользователю использовать функцию: ask("Текст вопроса"); Всего должно быть задано три вопроса
	а ответы зафиксированы.

	Входные данные должны быть валидны: email должен содержать символ @, возраст должен быть целым числом от 16 до 70.
	Языки программирования передаются через запятую, а сохряняются в виде массива. 

	После завершения опроса должен быть сформулирован контрольный вопрос что бы убедиться правильно ли все было введено. В ответ принимается
	только либо "y" либо "n" в маленьком регистре.
*/
$arr = [];
$mail = ask("What is your email?");
printf("My email is %s\n", $mail);
if (!strpos($mail, "@") === false) {
    array_push($arr, $mail);
} else {
    echo "Error mail\n";
    die();
}

$age = (int)ask("How old are you?");
printf("I am %s\n", $age);
if ($age > 15 && $age < 71) {
    array_push($arr, $age);
} else {
    echo "Error age\n";
    die();
}

$language = ask("What languages do you like? Write with coma, please!(PHP, JAvaScript)");
printf("My favorite languages are: %s\n", $language);
if (!empty(trim($language))) {
    $languages = explode(", ", $language);
    array_push($arr, $languages);
} else {
    echo "Incorrect have wrote down\n";
    die();
}

$result = <<<EOL
    "Your email is $mail
     You are $age
     Your favorite languages are $language"
EOL;

echo $result . "\n";

$answer = ask("Is it correct?[y\\n]");
switch (strtolower($answer)) {
    case 'y':
        echo "Great! Put in Database\n";
        print_r($arr);
        break;
    case 'n':
        echo "Please, answer to questions again\n";
        break;
    default:
        echo "Error answer";
}

