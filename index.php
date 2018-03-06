<?php
require_once('phar://ask.phar/src/ask.php');

/*
	API библиотеки обогатилось дополнительными функциями:
	save($user) - принмает ассоциативный массив вида ['username' => 'oleg', 'score' => 20];
				  username - имя пользователя в ЛАТИНИЦЕ (кирилицу не использовать)
				  score - очки пользователя
    find($username) - принимает строку (имя пользователя) и возвращает ассоциативный массив вида
                        ['username' => 'oleg', 'score' => 20]
    				  или null если пользователь с таким username не найден
    findAll()		- возвращает коллекцию (многомерный массив) ВСЕХ пользователей когда-либо участвоваших в викторине.
    clear()			- очищает хранилище данных.

    Реализовать викторину похожую на первую часть домашнего задания, но теперь с большей логикой.
    Что бы участвовать в викторине, пользователь должен представиться (ввести имя пользователя на латинице).
    Если пользователь с таким username
    найден, следует вывести приветственное сообщение, которое проинформирует его о текущем положении очков
    ($user['score']).

    За правильные ответы, пользователь получает очки, эти очки фиксируются в поле $user['score'].
    По завершении теста, информация о достижении
    должна быть зафиксирована при помощи функции save($user); Очки можно рассчитывать по-разному,
    это на ваше усмотрение. Каждый вопрос может
    иметь свою сложность и по-разному насчитывать балы (очки).

    Если пользователь ввел некорректные данные, которые прерывают работу программы - функция save($user)
    все равно должна быть вызвана
    что бы зафиксировать текущий результат.

    Сценарий имеет массив переданных ему аргументов (http://php.net/manual/ru/reserved.variables.argv.php),
    используйте это:
    	- Если пользователь запускает программу с аргументом stats (php index.php stats) - то необходимо имена
    пользователей и их очки.
    	- Если пользователь запускает программу с аргументом reset (php index.php reset) - то необходимо сбросить
    хранилище данных.


	Ничто не мешает расширять информацию о пользователе. Например, так:
    ['username' => 'oleg', 'score' => 20, 'question' => 5]
	Таким образом, вы можете сохранить информацию о том, на каком вопросе остановился пользователь.

	Добавьте метод load {username} что бы пользователь продолжил с того вопроса, на котором он остановился. Это может
    быть полезно, если	пользователь прервал выполнение программы нажатием Ctrl + C или ввел некорректно ответ на один
    из вопросов, что привело к завершению программы. Подумайте, как это можно реализовать. Пример вызова:
    php index.php load oleg - это приведет к тому, что пользователь oleg продолжит с того вопроса на котором
    остановился. Если же для этого пользователя викторина была окончена, он просто начнет с первого вопроса.
    Если же такого пользователя нет, то пользователь должен получить сообщение что такого пользователя нет, а так же
    получить информацию о том, как запустить викторину - правильно.

	Важно: код должен быть разделен на две части: область данных, проверка ввода. Если ответ не проходит валидацию,
    то вопрос повторяется
	и выводится содержимое ошибки на экран, что бы пользователь мог исправится и повторить ввод. Код не должен
    дублироваться.

	Подсказка: областью данных может быть массив вопросов, который определяется в самом начале.
*/
function redText($item)
{
    echo "\033[1;31m$item\033[0m\n";
}

function greenText($item)
{
    echo "\e[1;32m$item\e[0m\n";
}

define('TYPE_LIST', 1);
define('TYPE_INT', 2);
define('TYPE_STRING', 3);

$questions = [];

//1st question
$question = new stdClass();
$question->body = <<<EOL
Вопрос #1 из 5
Что такое PHP? 
[1] Программа-интерпретатор
[2] Язык программирования
[3] Компилятор
[4] Web-сервер
EOL;
$question->caption = "Укажите варианты через запятую";
$question->correct = [1, 2];
$question->type = TYPE_LIST;

$questions[] = $question;

//2d question
$question = new stdClass();
$question->body = <<<EOT
Вопрос #2 из 5
Какой функцией посчитать количество элементов массива?
EOT;
$question->caption = "Введите имя функции от руки. Например (sizeof)";
$question->correct = "count";
$question->type = TYPE_STRING;

$questions[] = $question;

//3d question
$question = new stdClass();
$question->body = <<<EOT
Вопрос #3 из 5
Какая функция возвращает величину строки?";
EOT;
$question->caption = "Введите имя функции от руки. Например (sizeof)";
$question->correct = "strlen";
$question->type = TYPE_STRING;

$questions[] = $question;

//4d question
$question = new stdClass();
$question->body = <<<EOT
Вопрос #4 из 5
Что делает функция strlen?
[1] Считает количество символов в строке
[2] Находит подстроку в строке
[3] Делит строку на подстроки по указанному символу
Ваш вариант
EOT;
$question->caption = "Выберите один из вариантов указав цифру";
$question->correct = 1;
$question->type = TYPE_INT;

$questions[] = $question;

//5th question
$question = new stdClass();
$question->body = <<<EOT
Вопрос #5 из 5
Что делает функция echo?
[1] Считает количество символов в строке
[2] Выводит информацию на экран
[3] Принимает первую строку масиива
Ваш вариант
EOT;
$question->caption = "Выберите один из вариантов указав цифру";
$question->correct = 2;
$question->type = TYPE_INT;

$questions[] = $question;

$answersUser = [];

$user = new stdClass();
$user->username;
$user->score = 0;
$user->question = 0;
$user->answers = $answersUser;

$participants = findAll();

echo <<<EOT
\nУчастники викторины
-------------------\n
EOT;
if ($participants) {
    foreach ($participants as $participant) {
        printf("%s - %s\n", $participant['username'], $participant['score']);
    }
} else {
    echo "Еще никто не участвовал\n";
}

$error = "Вы не ввели имя!\n";
do {
    $answer = ask("\nВедите ваше имя");
    if (!$answer) redText($error);
} while (!$answer);

$userName = $answer;

if (!find($answer)) {
    echo "Вы у нас впервые\n";
    $user->username = $answer;
    save(['username' => $user->username, 'score' => $user->score, 'question' => $user->question,
        'answers' => $user->answers]);
    foreach ($questions as $question) {
        $error = null;
        do {
            if ($error) {
                redText($error);
                $error = null;
            }
            echo "\n" . $question->body . "\n";
            $answer = ask($question->caption);
            switch ($question->type) {
                case TYPE_STRING:
                    if (strlen($answer) == 0) {
                        $error = "Нужно ввести текст";
                        continue;
                    }
                    if ((int)$answer > 0) {
                        $error = "Ваш ответ должен быть строкой";
                        continue;
                    }
                    if ($question->correct == $answer) {
                        $answersUser[] = $answer;
                        $user->answers = $answersUser;
                        $user->score += 2;
                        $user->question++;
                        save(['username' => $user->username, 'score' => $user->score, 'question' => $user->question,
                            'answers' => $user->answers]);
                        break;
                    } else {
                        $answersUser[] = $answer;
                        $user->answers = $answersUser;
                        $user->question++;
                        save(['username' => $user->username, 'score' => $user->score, 'question' => $user->question,
                            'answers' => $user->answers]);
                        break;
                    }
                case TYPE_INT:
                    if (strlen($answer) == 0) {
                        $error = "Нужно ввести текст";
                        continue;
                    }
                    if (!is_numeric($answer)) {
                        $error = 'Ваш ответ должен быть числом!';
                        continue;
                    }
                    if ($question->correct == $answer) {
                        $answersUser[] = $answer;
                        $user->answers = $answersUser;
                        $user->score++;
                        $user->question++;
                        save(['username' => $user->username, 'score' => $user->score, 'question' => $user->question,
                            'answers' => $user->answers]);
                        break;
                    } else {
                        $answersUser[] = $answer;
                        $user->answers = $answersUser;
                        $user->question++;
                        save(['username' => $user->username, 'score' => $user->score, 'question' => $user->question,
                            'answers' => $user->answers]);
                        break;
                    }
                case TYPE_LIST:
                    $answer = explode(',', $answer);
                    sort($answer);
                    if ($answer[0] == null) {
                        $error = "Нужно ввести данные";
                        continue;
                    }
                    if ($question->correct == $answer) {
                        $answersUser[] = $answer;
                        $user->answers = $answersUser;
                        $user->score += 3;
                        $user->question++;
                        save(['username' => $user->username, 'score' => $user->score, 'question' => $user->question,
                            'answers' => $user->answers]);
                        break;
                    } else {
                        $answersUser[] = $answer;
                        $user->answers = $answersUser;
                        $user->question++;
                        save(['username' => $user->username, 'score' => $user->score, 'question' => $user->question,
                            'answers' => $user->answers]);
                        break;
                    }
                default:
                    die('Неизвестный тип вопроса!');
                    break;
            }
        } while ($error);
    }
    $user = find($userName);
    printf("\nВикторина окончена, вот ваш результат: %s - %s\n", $user['username'], $user['score']);
}

if (find($answer)) {
    $user = find($answer);
    printf("Рады вас снова видеть, %s!\n\n", $answer);

    if ($user['question'] == 5) {
        $error = "Введите ответ!\n";
        do {
            $answer = ask("Вы ранее уже участвовали в викторине! Хотите повторить? [y\\n]");
            if (!$answer) {
                redText($error);
            }
            switch (strtolower($answer)) {
                case 'n':
                    printf("Ваши результат викторины: %s - %s\n", $user['username'], $user['score']);
                    clear($user);
                    break;
                case 'y':
                    clear($user);
                    $error = "Вы не ввели имя!\n";
                    do {
                        $answer = ask("\nВедите ваше имя");
                        if (!$answer) redText($error);
                    } while (!$answer);

                    $userName = $answer;

                    if (!find($answer)) {
                        echo "Вы у нас впервые\n";
                        $user = new stdClass();
                        $user->username = $answer;
                        save(['username' => $user->username, 'score' => $user->score, 'question' => $user->question,
                            'answers' => $user->answers]);
                        foreach ($questions as $question) {
                            $error = null;
                            do {
                                if ($error) {
                                    redText($error);
                                    $error = null;
                                }
                                echo "\n" . $question->body . "\n";
                                $answer = ask($question->caption);
                                switch ($question->type) {
                                    case TYPE_STRING:
                                        if (strlen($answer) == 0) {
                                            $error = "Нужно ввести текст";
                                            continue;
                                        }
                                        if ((int)$answer > 0) {
                                            $error = "Ваш ответ должен быть строкой";
                                            continue;
                                        }
                                        if ($question->correct == $answer) {
                                            $answersUser[] = $answer;
                                            $user->answers = $answersUser;
                                            $user->score += 2;
                                            $user->question++;
                                            save(['username' => $user->username, 'score' => $user->score,
                                                'question' => $user->question,
                                                'answers' => $user->answers]);
                                            break;
                                        } else {
                                            $answersUser[] = $answer;
                                            $user->answers = $answersUser;
                                            $user->question++;
                                            save(['username' => $user->username, 'score' => $user->score,
                                                'question' => $user->question,
                                                'answers' => $user->answers]);
                                            break;
                                        }
                                    case TYPE_INT:
                                        if (strlen($answer) == 0) {
                                            $error = "Нужно ввести текст";
                                            continue;
                                        }
                                        if (!is_numeric($answer)) {
                                            $error = 'Ваш ответ должен быть числом!';
                                            continue;
                                        }
                                        if ($question->correct == $answer) {
                                            $answersUser[] = $answer;
                                            $user->answers = $answersUser;
                                            $user->score++;
                                            $user->question++;
                                            save(['username' => $user->username, 'score' => $user->score,
                                                'question' => $user->question,
                                                'answers' => $user->answers]);
                                            break;
                                        } else {
                                            $answersUser[] = $answer;
                                            $user->answers = $answersUser;
                                            $user->question++;
                                            save(['username' => $user->username, 'score' => $user->score,
                                                'question' => $user->question,
                                                'answers' => $user->answers]);
                                            break;
                                        }
                                    case TYPE_LIST:
                                        $answer = explode(',', $answer);
                                        sort($answer);
                                        if (empty($answer)) {
                                            $error = "Нужно ввести данные";
                                            continue;
                                        }
                                        if ($question->correct == $answer) {
                                            $answersUser[] = $answer;
                                            $user->answers = $answersUser;
                                            $user->score += 3;
                                            $user->question++;
                                            save(['username' => $user->username, 'score' => $user->score,
                                                'question' => $user->question,
                                                'answers' => $user->answers]);
                                            break;
                                        } else {
                                            $answersUser[] = $answer;
                                            $user->answers = $answersUser;
                                            $user->question++;
                                            save(['username' => $user->username, 'score' => $user->score,
                                                'question' => $user->question,
                                                'answers' => $user->answers]);
                                            break;
                                        }
                                    default:
                                        die('Неизвестный тип вопроса!');
                                        break;
                                }
                            } while ($error);
                        }
                        $user = find($userName);
                        printf("\nВикторина окончена, вот ваш результат: %s - %s\n", $user['username'], $user['score']);
                    }
            }
        } while (!$answer);
    }
    if ($user['question'] < 5) {
        $error = null;
        $answer = ask("Вы ранее уже участвовали в викторине! Хотите продолжить? [y\\n]");
        do {
            if (!$answer) {
                redText($error);
                $answer = ask("Введите ответ! [y\\n]");
            }
        } while (!$answer);

        switch (strtolower($answer)) {
            case 'n':
                printf("Ваш текущий результат викторины: %s - %s\n", $user['username'], $user['score']);
                break;
            case 'y':
                for ($i = $user['question']; $i < count($questions); $i++) {
                    do {
                        if ($error) {
                            redText($error);
                            $error = null;
                        }
                        echo "\n" . $questions[$i]->body . "\n";
                        $answer = ask($questions[$i]->caption);
                        switch ($questions[$i]->type) {
                            case TYPE_STRING:
                                if (strlen($answer) == 0) {
                                    $error = "Нужно ввести текст";
                                    echo $error;
                                    continue;
                                }
                                if ((int)$answer > 0) {
                                    $error = "Ваш ответ должен быть строкой";
                                    continue;
                                }
                                if ($questions[$i]->correct == $answer) {
                                    $user['answers'][] = $answer;
                                    $user['score'] += 2;
                                    $user['question']++;
                                    save(['username' => $user['username'], 'score' => $user['score'],
                                        'question' => $user['question'],
                                        'answers' => $user['answers']]);
                                } else {
                                    $user['question']++;
                                    save(['username' => $user['username'], 'score' => $user['score'],
                                        'question' => $user['question'],
                                        'answers' => $user['answers']]);
                                }
                                break;
                            case TYPE_INT:
                                if (strlen($answer) == 0) {
                                    $error = "Нужно ввести текст\n";
                                    continue;
                                }
                                if (!is_numeric($answer)) {
                                    $error = 'Ваш ответ должен быть числом!';
                                    continue;
                                }
                                if ($questions[$i]->correct == $answer) {
                                    $user['answers'][] = $answer;
                                    $user['score']++;
                                    $user['question']++;
                                    save(['username' => $user['username'], 'score' => $user['score'],
                                        'question' => $user['question'],
                                        'answers' => $user['answers']]);
                                } else {
                                    $user['question']++;
                                    save(['username' => $user['username'], 'score' => $user['score'],
                                        'question' => $user['question'],
                                        'answers' => $user['answers']]);
                                }
                                break;
                            case TYPE_LIST:
                                $answer = explode(',', $answer);
                                sort($answer);
                                if (empty($answer)) {
                                    $error = "Нужно ввести данные";
                                    continue;
                                }
                                if ($questions[$i]->correct == $answer) {
                                    $user['answers'][] = $answer;
                                    $user['score'] += 3;
                                    $user['question']++;
                                    save(['username' => $user['username'], 'score' => $user['score'],
                                        'question' => $user['question'],
                                        'answers' => $user['answers']]);
                                } else {
                                    $user['question']++;
                                    save(['username' => $user['username'], 'score' => $user['score'],
                                        'question' => $user['question'],
                                        'answers' => $user['answers']]);
                                }
                                break;
                            default:
                                die('Неизвестный тип вопроса!');
                                break;
                        }
                    } while ($error);
                }
        }
        $user = find($userName);
        printf("\nВикторина окончена, вот ваш результат: %s - %s\n", $user['username'], $user['score']);
    }
}


