<?php

namespace App\SmsService;

class SmsService
{
    protected $url;

    protected $login;

    protected $password;

    public function __construct()
    {
        $this->url = 'http://91.204.239.44/broker-api/send';
        $this->login = env("PLAY_MOBILE_LOGIN");
        $this->password = env("PLAY_MOBILE_PASS");
    }

    /**
     * Максимальное кол-во сообщений в одном запросе.
     *
     * @var int
     */
    protected $maxMessages = 500;

    /**
     * Сообщения для массовой рассылки.
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Добавить сообщение для массовой рассылки.
     *
     * @param $phone
     * @param $message
     */
    public function add($phone, $message)
    {
        $this->messages[] = ['phone' => $phone, 'message' => $message];
    }

    /**
     * Отпраляет одно sms сообщение.
     *
     * @param $phone
     * @param $message
     * @return mixed
     */
    public function send($phone, $message)
    {
//        $request = $this->makeRequest([['phone' => $phone, 'message' => $message]]);
        return $this->request(['phone' => $phone, 'text' => $message]);
    }

    /**
     * Отправить все сообщения из массовой рассылки.
     */
    public function sendAll()
    {
        $chunks = array_chunk($this->messages, $this->maxMessages);

        foreach ($chunks as $messages) {
            $request = $this->makeRequest($messages);
            $this->request($request);
        }
    }

    /**
     * Отправить запрос.
     *
     * @param $data
     * @return mixed
     */
    protected function request($data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => "8083",
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERPWD => $this->login.":".$this->password,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\r\n \"messages\":\r\n [\r\n {\r\n  \"recipient\":\"".$data['phone']."\",\r\n  \"message-id\":\"".uniqid()."\",\r\n\r\n     \"sms\":{\r\n\r\n       \"originator\": \"3700\",\r\n     \"content\": {\r\n      \"text\": \"".$data['text']."\"\r\n      }\r\n      }\r\n         }\r\n     ]\r\n}\r\n\r\n",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        return $response;
    }

    /**
     * Сформировать запрос.
     *
     * @param array $messages
     * @return string
     */
    protected function makeRequest(array $messages)
    {
        $request = '<bulk-request
            login="' . $this->login . '"
            password="' . $this->password . '"
            ref-id="' . date('Y-m-d H:i:s') . '"
            delivery-notification-requested="true"
            version="1.0">';

        foreach ($messages as $index => $message) {
            $request .= '<message id="' . ($index + 1) . '"
                msisdn="' . $message['phone'] . '"
                validity-period="3"
                priority="1">
                <content type="text/plain">' . $message['message'] . '</content>
            </message>';
        }

        $request .= '</bulk-request>';

        return $request;
    }
}
