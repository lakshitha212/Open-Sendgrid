<?php
/**
 * Created by PhpStorm.
 * User: chmaeera.lakshitha212@gmail.com
 * Date: 5/29/2018
 * Time: 4:11 PM
 */

/**
 * Class OpenSendGridMailer
 */
class OpenSendGridMailer
{
    /**
     * @var
     */
    protected $to;
    protected $from;
    protected $sender;
    protected $reply_to;
    protected $subject;
    protected $text;
    protected $html;
    protected $attachments = array();
    protected $send_grid_api_key;

    /**
     * OpenSendGridMailer constructor.
     * @param $send_grid_api_key
     */
    public function __construct($send_grid_api_key)
    {
        $this->send_grid_api_key = $send_grid_api_key;
    }

    /**
     * @param $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * @param $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @param $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @param $reply_to
     */
    public function setReplyTo($reply_to)
    {
        $this->reply_to = $reply_to;
    }

    /**
     * @param $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @param $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @param $html
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }

    /**
     * @param $filename
     */
    public function addAttachment($filename)
    {
        $this->attachments[] = $filename;
    }

    /**
     * @throws Exception
     * https://sendgrid.com/docs/API_Reference/api_v3.html
     */
    public function send()
    {
        if (!$this->to) {
            throw new \Exception('Error: E-Mail to required!');
        }

        if (!$this->from) {
            throw new \Exception('Error: E-Mail from required!');
        }

        if (!$this->sender) {
            throw new \Exception('Error: E-Mail sender required!');
        }

        if (!$this->subject) {
            throw new \Exception('Error: E-Mail subject required!');
        }

        if ((!$this->text) && (!$this->html)) {
            throw new \Exception('Error: E-Mail message required!');
        }

        if (is_array($this->to)) {
            $to = implode(',', $this->to);
        } else {
            $to = $this->to;
        }
        $textorder = array("\r\n", "\n", "\r", PHP_EOL);

        $mailtext = str_replace($textorder, "<br/>", $this->text);

        $message = isset($this->html) ? $this->html : $mailtext;
        $personalizations = Array(
            "personalizations" => Array(
                0 => Array(
                    "to" => Array(
                        0 => Array(
                            "email" => $to
                        )),
                    "subject" => $this->subject),
            ),
            "from" => Array(
                "email" => $this->from,
                "name" => $this->sender
            ),
            "reply_to" => Array(
                "email" => $this->from,
                "name" => $this->sender
            ),
            "subject" => $this->subject,
            "content" => Array(
                0 => Array(
                    "type" => "text/html",
                    "value" => $message
                ))
        );
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/mail/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($personalizations),
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer ". $this->send_grid_api_key,
                "content-type: application/json"
            ),
        ));
        curl_close($curl);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }
}