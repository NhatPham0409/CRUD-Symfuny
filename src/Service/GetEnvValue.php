<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;

class GetEnvValue implements EnvVarProcessorInterface
{
    public function getEnv(string $prefix, string $name, \Closure $getEnv): string
    {
        $env = $getEnv($name);

        return strtolower($env);
    }

    public static function getProvidedTypes(): array
    {
        return [
            'lowercase' => 'string',
        ];
    }
}