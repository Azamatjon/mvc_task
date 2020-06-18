<?php

namespace Core\Components;

class Alert
{
    public $type;
    public $message;
    public $dismissible;


    public function __construct($type, $message, $dismissible = false)
    {
        $this->type = $type;
        $this->message = $message;
        $this->dismissible = $dismissible;
    }
}

class AlertType
{
    const DANGER = 'danger';
    const SUCCESS = 'success';
    const WARNING = 'warning';
}