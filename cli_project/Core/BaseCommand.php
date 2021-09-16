<?php

namespace Core;

abstract class BaseCommand
{
    /**
     * @var string
     */
    protected static $name = '';

    /**
     * @var string
     */
    protected static $description = '';

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::$name;
    }

    /**
     * @return string
     */
    public static function getDescription(): string
    {
        return static::$description;
    }

    /**
     * @param string $error
     */
    public function setError(string $error): void
    {
        $this->errors[] = "\e[31m{$error}\e[0m";
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * @throws \Exception
     */
    public function returnErrors()
    {
        throw new \Exception(implode("\n", $this->getErrors()));
    }

    /**
     * Start command
     * @param array $arguments
     * @param array $options
     */
    abstract public function start(array $arguments, array $options);

    /**
     * Check arguments and options
     * @return bool
     */
    abstract public function checkParams(): bool;

    /**
     * Output information in console
     */
    abstract public function output();
}