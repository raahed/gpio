<?php
namespace GPIO\Exception;

class CollectionException extends \Exception
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