<?php
namespace GPIO\Exception;

class SystemException extends \Exception
{

    protected $messages = [];

    public function __construct($message)
    {
        $this->messages[] = $message;
        
        parent::__construct($message);
    }

    public function getMessages()
    {
        return $this->messages;
    }
}