<?php

namespace Commands;

use Core\BaseCommand;

class Test extends BaseCommand
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
    protected static $name = 'test';

    /**
     * @var string
     */
    protected static $description = 'Test required arguments "supertest" and "superpupertest"';

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
        if (array_search('supertest', $this->arguments) === false) {
            $this->setError('Аргумент supertest обязателен');
        }

        if (array_search('superpupertest', $this->arguments) === false) {
            $this->setError('Аргумент superpupertest обязателен');
        }

        if ($this->hasErrors()) {
            return false;
        }

        return true;
    }

    public function output()
    {
        throw new \Exception("\e[32mTest success");
    }
}