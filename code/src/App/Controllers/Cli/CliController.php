<?php

declare(strict_types=1);

namespace App\Controllers\Cli;

class CliController
{
    public static function execute(array $argv): int
    {
        var_dump($argv);
        if (!isset($argv[1])) {
            exit('Empty command');
        }

        array_shift($argv);
        $command_name = array_shift($argv);;
        var_dump($command_name);
        $command_class = self::getCommandClassByName($command_name);
        var_dump('$command_class>>',$command_class);
        if (!class_exists($command_class)) {
            exit("Unknown command: $command_name");
        }

        $cli_input = new CliInput($argv);
        var_dump('$cli_input>>',$cli_input);
        return $command_class::execute($cli_input);
    }

    private static function getCommandClassByName(string $command_name): string|CliCommand
    {
        $result_parts = [];
        $command_route = explode('/', $command_name);
        foreach ($command_route as $command_route_part) {
            $words = explode('-', $command_route_part);
            $result_parts[] = implode('', array_map(static fn($word) => ucfirst($word), $words));
        }

        return "App\\Controllers\\Cli\\Commands\\" . implode('\\', $result_parts);
    }
}