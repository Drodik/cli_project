<?php

namespace Core;

/**
 * Controller commands
 */
class Controller
{
    /**
     * @var string
     */
    private $command = '';

    /**
     * @var array
     */
    private $arguments = [];

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var array
     */
    private $allCommands = [];

    public function __construct(array $inputArguments)
    {
        $this->prepareArguments($inputArguments);

        $this->allCommands = $this->getCommands();

        $this->executeCommand();
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $inputArguments
     */
    public function prepareArguments(array $inputArguments): void
    {
        $this->command = $inputArguments[1];

        unset($inputArguments[0], $inputArguments[1]);

        $data = [];

        foreach ($inputArguments as $argument) {

            preg_match('/\[(.*)\]/m', $argument, $match);

            if (!empty($match)) {
                $option = explode('=', $match[1]);
                $this->options[$option[0]][] = $option[1];
            } else {

                $this->arguments[] = trim($argument, '{}');
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function executeCommand(): void
    {
        if (!empty($this->allCommands[$this->command])) {

            if (array_search('help', $this->arguments) !== false) {

                $this->outputCommandInfo();
            }

            $preparedClass = ucwords(mb_eregi_replace("[^a-z]", "|", $this->command), "|");
            $preparedClass = str_replace('|', '', $preparedClass);

            $class = '\\Commands\\' . $preparedClass;

            if (class_exists($class)) {
                $command = new $class;

                $command->start($this->arguments, $this->options);
            }
        } else {

            $this->outputOfCommandsList();
        }
    }

    /**
     * @throws \Exception
     */
    public function outputCommandInfo(): void
    {
        $commandInfo = $this->allCommands[$this->command];

        $result = '';
        $result .= "\n----------------------------------------------------------------------\n";
        $result .= "Команда: {$commandInfo['name']} \nОписание: {$commandInfo['description']} \n";
        $result .= "----------------------------------------------------------------------\n";

        throw new \Exception($result);
    }

    /**
     * @throws \Exception
     */
    public function outputOfCommandsList(): void
    {
        $allCommands = [];

        $allCommands = $this->allCommands;

        $result = '';

        foreach ($allCommands as $command) {
            $result .= "\n----------------------------------------------------------------------\n";
            $result .= "Команда: {$command['name']} \nОписание: {$command['description']} \n";
            $result .= "----------------------------------------------------------------------\n";
        }

        $result = (!empty($result)) ? $result : "отсутствует\n";

        throw new \Exception("\n\e[31mКоманда не найдена.\e[0m \n\nСписок доступных комманд: \n" . $result);
    }

    /**
     * @return array
     */
    private function getCommands(): array
    {
        $commands = [];

        $files = scandir('.//Commands/');

        unset($files[0], $files[1]);

        foreach ($files as $file) {

            $name = str_replace('.php', '', $file);
            $className = "\\Commands\\{$name}";

            $commands[$className::getName()] = [
                'name' => $className::getName(),
                'description' => $className::getDescription(),
            ];
        }

        return $commands;
    }
}