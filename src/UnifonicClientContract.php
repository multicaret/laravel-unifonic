<?php

namespace Multicaret\Unifonic;


interface UnifonicClientContract
{
    public function send(int $recipient, string $message, string $senderID = null);

    public function getMessageIDStatus(int $messageID);

}
