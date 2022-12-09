<?php

/*
|--------------------------------------------------------------------------
| Authentication Language Lines
|--------------------------------------------------------------------------
|
| The following language lines are used during authentication for various
| messages that we need to display to the user. You are free to modify
| these language lines according to your application's requirements.
|
*/

return [
    "confirm" => [
        "attempts" => "Количество попыток: :attempts.",
        "attempts_ended" => "Все попытки исчерпаны.",
        "code" => "Код подтверждения регистрации на сайте \"Родословная\".",
        "code_error" => "Неверный код!",
        "code_repeated_time" => "Повторный код можно выслать через:",
        "code_send_impossible" => "Не удалось направить Вам код подтверждения! Обратиться к администратору.",
        "confirm" => "Подтверждение",
        "email" => "На адрес электронной почты :address выслан код подтверждения.",
        "input_code" => "Введите код:",
        "phone" => "На номер телефона :address выслан код подтверждения.",
        "repeated_message" => "У Вас закончилось время или попытки!",
        "repeate_timestamp_not_reached" => "Не истекло время для совершения повторной попытки.",
        "sec" => "сек.",
        "send_repeated_code" => "Отправить повторный код",
        "subject" => "Подтверждение регистрации на сайте \"Родословная\".",
        "time" => "Оставшееся время:",
        "time_over" => "Время истекло.",
        "user_unconfirmed_exists" => "Введённый номер телефона или email уже находится в процессе подтверждения."
    ],
    "error_info" => "Упс! Что-то пошло не так.",
    'failed'   => 'Неверный логин или пароль.',
    "forbidden" => "У Вас нет прав на совершение данных действий!",
    "identifier" => "Телефон или email.",
    "identifier_incorrect" => "Некорректный телефон или email.",
    "identifier_info" => "Внимание! Номер телефона или email должны быть согласованы с администратором.",
    "identifier_no_exists" => "Номер телефона или email не согласован с администратором! Свяжитесь с ним.",
    "identifier_rule" => "Номер телефона без тире, плюса, 8, 7 или email.",
    "is_registered" => "Уже зарегистрирован?",
    "log_in" => "Войти",
    "log_out" => "Выйти",
    "password" => "Пароль",
    "password_rule" => "Строка длиной не менее 8 символов.",
    "password_confirm" => "Подтверждение пароля",
    "register" => "Зарегистрироваться",
    "remember_me" => "Запомнить меня.",
    'throttle' => 'Слишком много попыток входа. Пожалуйста, попробуйте еще раз через :seconds секунд.',
    "user_exist" => "Пользователь с аналогичным номером телефона или email уже зарегистрирован. Свяжитесь с администратором.",
];
