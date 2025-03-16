<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\SessionHelper;

use Exception;

final class SessionException Extends Exception
{
    // Exception Message
    public function message():string
    {
        return "<b>[{$this->getCode()}]</b> - {$this->getMessage()} >> File: {$this->getFile()} in Line {$this->getLine()}<br>";
    }
}