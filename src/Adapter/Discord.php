<?php

namespace Tartan\IranianSms\Adapter;

class Discord extends AdapterAbstract implements AdapterInterface
{
    public $url;

    public function __construct($account = null)
    {
        if (is_null($account)) {
            $this->url = config('iranian_sms.discord.url');
        } else {
            $this->url = config("iranian_sms.discord.{$account}.url");
        }
    }

    public function send(string $number, string $message)
    {
        $number = $this->filterNumber($number);

        $data = ['content' => "To: $number - Message: $message"];

        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        return $data;
    }
}
