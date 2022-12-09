<?php

namespace App\Channels\SmsRu;

use App\Exceptions\SenderException;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

final class Sender
{
    /**
     * @throws SenderException
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        $nexmoMessage = $notification->toNexmo($notifiable);

        $phone = $notifiable->routeNotificationForSms($notification);

        $ch = curl_init(config("services.sms_api.url"));
        if ($ch !== false) {
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt(
                $ch,
                CURLOPT_POSTFIELDS,
                http_build_query([
                    "api_id" => config("services.sms_api.token"),
                    "to" => $phone,
                    "msg" => $nexmoMessage->content,
                    "json" => 1
                ])
            );
            if (config("app.env") !== "testing") {
                $body = curl_exec($ch);
                curl_close($ch);

                if ($body === false) {
                    $messageError = "Функция curl_exec() вернула false. Входные данные phone=$phone, "
                        . "message={$nexmoMessage->content}. ";
                    throw new SenderException($messageError);
                }

                if ($body === true) {
                    $messageError = "Функция curl_exec() вернула неожиданно булевое значение true. Входные данные phone=$phone, "
                        . "message={$nexmoMessage->content}. ";
                    throw new SenderException($messageError);
                }

                $json = json_decode($body);
                if ($json) { // Получен ответ от сервера
                    //print_r($json); // Для дебага
                    if ($json->status == "OK") { // Запрос выполнился
                        $message = "Баланс после отправки: $json->balance руб. ";
                        foreach ($json->sms as $phone => $data) { // Перебираем массив СМС сообщений
                            if ($data->status == "OK") { // Сообщение отправлено
                                $message .= "Сообщение на номер $phone успешно отправлено. ";
                                $message .= "ID сообщения: $data->sms_id. ";
                                $message .= "";
                                Log::info(self::class . "; " . $message);
                            } else { // Ошибка в отправке
                                $message .= "Сообщение на номер $phone не отправлено. ";
                                $message .= "Код ошибки: $data->status_code. ";
                                $message .= "Текст ошибки: $data->status_text. ";
                                $message .= "";
                                throw new SenderException($message);
                            }
                        }
                    } else { // Запрос не выполнился (возможно ошибка авторизации, параметрах, итд...)
                        $messageError = "Запрос не выполнился. ";
                        $messageError .= "Код ошибки: $json->status_code. ";
                        $messageError .= "Текст ошибки: $json->status_text. ";
                        throw new SenderException($messageError);
                    }
                } else {
                    $messageError = "Запрос не выполнился. Не удалось установить связь с сервером. ";
                    throw new SenderException($messageError);
                }
            }
        }
    }
}
