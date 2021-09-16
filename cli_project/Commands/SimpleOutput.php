<?php

namespace Commands;

use Core\BaseCommand;

class SimpleOutput extends BaseCommand
{
    /**
     * @var array
     */
    private $arguments = [];

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var string
     */
    protected static $name = 'simple_output';

    /**
     * @var string
     */
    protected static $description = 'Команда, принимает на вход неограниченное количество аргументов и параметров и выводит их на экран в читаемом для пользователя виде';

    /**
     * Start command
     * @param array $arguments
     * @param array $options
     * @throws \Exception
     */
    public function start(array $arguments, array $options): void
    {
        $this->arguments = $arguments;
        $this->options = $options;

        if (!$this->checkParams()) {

            $this->returnErrors();
        }

        $this->output();
    }

    /**
     * Check arguments and options
     * @return bool
     */
    public function checkParams(): bool
    {
        return true;
    }

    /**
     * @throws \Exception
     */
    public function output(): void
    {
        $result = '';
        $result .= "\nCalled command: " . self::$name . "\n\n";

        if (!empty($this->arguments)) {

            $result .= "Arguments:\n";

            foreach ($this->arguments as $argument) {
                $result .= "\t- {$argument}\n";
            }
        }

        if (!empty($this->options)) {

            $result .= "Options:\n";

            foreach ($this->options as $key => $option) {

                $result .= "\t- {$key}\n";

                foreach ($option as $value) {

                    $result .= "\t\t- {$value}\n";
                }

            }
        }

        throw new \Exception($result);
    }
}