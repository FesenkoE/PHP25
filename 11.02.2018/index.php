<?php
require_once('phar://ask.phar/src/ask.php');
/*
	Используя функцию ask(message); сгенерировать опросник-тест, по пройденым темам. Тест должен состоять из 10 вопросов, 
	тест пройден если ответы были точны по крайней мере на 8 из 10 вопросов (80%). Тест должен содержать вопросы где может быть
	несколько ответов правильными, где может быть правильным ответом только один, а так же ключевое слово (введенное с клавиатуры).

	Ответы могут быть правильными или нет, но они всегда должны быть корректны. Например недопустимо ответить строкой
	там где ожидается номер ответа.

	Если человек ввел некорректно - тест прекращает свою работу, его нужно запускать вручную повторно. Правила ввода для пользователя, 
	следует указать прямо в вопросе.  

	Вопрос #1

	Что такое PHP? (выберите несколько вариантов через запятую, например: 1,3)
	[1] Программа-интерпретатор
	[2] Язык программирования
	[3] Компилятор
	[4] Web-сервер


	Вопрос #2
	Что делает функция strlen? (выберите один вариант, указав цифру)
	[1] Считает количество символов в строке
	[2] Находит подстроку в строке
	[3] Дели строку на подстроки по указанному символу

	Вопрос #3
	Какое ключевое слово использутеся для вовода на экран обычной строки? (Введите с клавиатуры)

	Для тех кто любит заморочиться:

	Результат опроса можно раскрасить в зависимости от результата, красным или зеленым. Цвета можно посмотреть в методичке.
	echo "\033[31m Этот текст будет выведен темно-красным цветом.\033[0m\n";

	В каждом вопросе можно выводить информацию в таком виде: Вопрос #5 из 15. 

*/

/* @var int количество правильных ответов */
$rightAnswers = 0;
$yourAnswer = [];
//Пример вопроса
$answer = ask(<<<EOT
Вопрос #1 из 10
Что такое PHP? (выберите несколько вариантов через запятую, например: 1,3)
[1] Программа-интерпретатор
[2] Язык программирования
[3] Компилятор
[4] Web-сервер
Ваши варианты
EOT
);
if (empty($answer) && strlen(trim($answer)) != 3) {
    echo "\033[31mНе верно!\033[0m\n";
    array_push($yourAnswer, "null");
} else {
    $phpArr = explode(",", trim($answer));
    sort($phpArr);
    if ((int)$phpArr[0] === 1 && (int)$phpArr[1] === 2) {
        echo "\033[1;32mВерно!\033[0m\n";
        array_push($yourAnswer, implode(",", $phpArr));
        $rightAnswers++;
    } else {
        echo "\033[31mНе верно!\033[0m\n";
        array_push($yourAnswer, "null");
    }

}

printf("Правильных ответов: %s\n\n", $rightAnswers);

$answer = ask(<<<EOT
Вопрос #2 из 10
Что делает функция strlen? (выберите один вариант, указав цифру)
[1] Считает количество символов в строке
[2] Находит подстроку в строке
[3] Делит строку на подстроки по указанному символу
Ваш вариант
EOT
);
if ((int)$answer === 1) {
    echo "\033[1;32mВерно!\033[0m\n";
    array_push($yourAnswer, $answer);
    $rightAnswers++;
} else {
    echo "\033[31mНе верно!\033[0m\n";
    array_push($yourAnswer, "null");
}

printf("Правильных ответов: %s\n\n", $rightAnswers);

$answer = ask(<<<EOT
Вопрос #3 из 10
Какое ключевое слово использутеся для вовода на экран обычной строки? (Введите с клавиатуры)
Ваш вариант
EOT
);

if (strtolower(trim($answer)) === "echo") {
    echo "\033[1;32mВерно!\033[0m\n";
    array_push($yourAnswer, strtolower($answer));
    $rightAnswers++;
} else {
    echo "\033[31mНе верно!\033[0m\n";
    array_push($yourAnswer, "null");
}

printf("Правильных ответов: %s\n\n", $rightAnswers);

$answer = ask(<<<EOT
Вопрос #4 из 10
Сколько типов данных в PHP начиная с версии 7.2, выберите вариант ответа
[1] 4
[2] 6
[3] 8
[4] 9
[5] 10
Ваш вариант
EOT
);
if ((int)trim($answer) === 5) {
    echo "\033[1;32mВерно!\033[0m\n";
    array_push($yourAnswer, $answer);
    $rightAnswers++;
} else {
    echo "\033[31mНе верно!\033[0m\n";
    array_push($yourAnswer, "null");
}

printf("Правильных ответов: %s\n\n", $rightAnswers);

$answer = ask(<<<EOT
Вопрос #5 из 10
Что будет выведено в результате выполнения следующего кода?(Напечайте пробел если есть такой в ответе)
<?php
echo 'A';
if ('A' != 'B')
    echo "B"
# C ?> D
Ваш вариант
EOT
);

if (strtoupper($answer) === "AB D") {
    echo "\033[1;32mВерно!\033[0m\n";
    array_push($yourAnswer, strtolower($answer));
    $rightAnswers++;
} else {
    echo "\033[31mНе верно!\033[0m\n";
    array_push($yourAnswer, "null");
}

printf("Правильных ответов: %s\n\n", $rightAnswers);

$answer = ask(<<<EOT
Вопрос #6 из 10
Напишите через запятую не менее 3-x чисел, сумма которых будет кратна 3(пример: 4,10,4)
Ваш вариант
EOT
);

if (empty($answer)) {
    echo "\033[31mНе верно!\033[0m\n";
    array_push($yourAnswer, "null");
} else {
    $sum = explode(",", trim($answer));
    if (count($sum) > 2 && array_sum($sum) % 3 === 0) {
        echo "\033[1;32mВерно!\033[0m\n";
        array_push($yourAnswer, "true");
        $rightAnswers++;
    } else {
        echo "\033[31mНе верно!\033[0m\n";
        array_push($yourAnswer, "null");
    }
}

printf("Правильных ответов: %s\n\n", $rightAnswers);

$answer = ask(<<<EOT
Вопрос #7 из 10
В чем различия между одинарными кавычками('') и двойными(""), если несколько вариантов, напишите через запятую 
[1] В двойных кавычках скрипт работает быстрее
[2] В одинарных кавычках скрипт работает быстрее
[3] В одинарных кавычках подставляются значения переменных
[4] В двойных кавычках подставляются значения переменных
Ваши варианты
EOT
);
if (empty($answer) && strlen(trim($answer)) != 3) {
    echo "\033[31mНе верно!\033[0m\n";
    array_push($yourAnswer, "null");
} else {
    $brackets = explode(",", trim($answer));
    sort($brackets);

    if ((int)$brackets[0] === 2 && (int)$brackets[1] === 4) {
        echo "\033[1;32mВерно!\033[0m\n";
        array_push($yourAnswer, implode(",", $brackets));
        $rightAnswers++;
    } else {
        echo "\033[31mНе верно!\033[0m\n";
        array_push($yourAnswer, "null");
    }
}
printf("Правильных ответов: %s\n\n", $rightAnswers);

$answer = ask(<<<EOT
Вопрос #8 из 10
Какой из нижеперечисленных вариантов корректно объявляет константу 
[1] define "NAME", "John";
[2] define ("NAME", 'John');
[3] define ("NAME"), ('John');
[4] define ("NAME": 'John');
Ваши варианты
EOT
);

if (empty($answer)) {
    echo "\033[31mНе верно!\033[0m\n";
    array_push($yourAnswer, "null");
} else {
    if ((int)$answer === 2) {
        echo "\033[1;32mВерно!\033[0m\n";
        array_push($yourAnswer, $answer);
        $rightAnswers++;
    } else {
        echo "\033[31mНе верно!\033[0m\n";
        array_push($yourAnswer, "null");
    }
}

printf("Правильных ответов: %s\n\n", $rightAnswers);

$answer = ask(<<<EOT
Вопрос #9 из 10
Какая функция выводит отформатированную строку(напишите Ваш ответ)
Ваш вариант
EOT
);

if (strtolower($answer) === "printf") {
    echo "\033[1;32mВерно!\033[0m\n";
    array_push($yourAnswer, strtolower($answer));
    $rightAnswers++;
} else {
    echo "\033[31mНе верно!\033[0m\n";
    array_push($yourAnswer, "null");
}

printf("Правильных ответов: %s\n\n", $rightAnswers);

$answer = ask(<<<EOT
Вопрос #10 из 10
Какая функция удаляет теги HTML и PHP(напишите Ваш ответ)
Ваш вариант
EOT
);

if (strtolower($answer) === "strip_tags") {
    echo "\033[1;32mВерно!\033[0m\n";
    array_push($yourAnswer, strtolower($answer));
    $rightAnswers++;
} else {
    echo "\033[31mНе верно!\033[0m\n";
    array_push($yourAnswer, "null");
}

printf("Правильных ответов: %s\n\n", $rightAnswers);


echo "Тест успешно пройден! Вы ответили на " . $rightAnswers . " ответов правильно из 10, это составляет "
    . $rightAnswers * 10 . "%!\n\n";

$arrRightAnswers = [
     "1,2",
     "1",
     "echo",
     "5",
     "ab d",
     "true",
     "2,4",
     "2",
    "printf",
    "strip_tags"
];

$result = ask("Хотите увидеть свои ответы и сравнить? [y\\n]");

switch (strtolower($result)) {
    case 'y':
        echo "Сейчас покажем\n";
        for ($i = 0; $i < 10; $i++) {
            if ($yourAnswer[$i] == $arrRightAnswers[$i]) {
                echo "\e[1;32m$yourAnswer[$i]\e[0m - $arrRightAnswers[$i]\n";
            } else {
                echo "\e[31m$yourAnswer[$i]\e[0m - $arrRightAnswers[$i]\n";
            }
        }
        break;
    case 'n':
        echo "Ну как хотите!\n";
        break;
    default:
        die("Хорошего дня! =)");
}