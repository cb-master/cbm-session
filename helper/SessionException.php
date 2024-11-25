<?php
/**
 * APP Name:        Laika Session Model
 * APP Provider:    Showket Ahmed
 * APP Link:        https://cloudbillmaster.com
 * APP Contact:     riyadtayf@gmail.com
 * APP Version:     1.0.0
 * APP Company:     Cloud Bill Master Ltd.
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