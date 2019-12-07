<?php


namespace Ppx17\Aoc2015\Aoc\Runner;


use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;

class DayLoader
{
    private const EXT = '.php';
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function load(string $daysFolder, string $inputFolder): Collection
    {
        $content = scandir($daysFolder);
        if (!$content) return new Collection();

        return collect($content)
            ->filter(function($file) {
                return Str::endsWith($file, self::EXT);
            })
            ->map(function($file) {
                return Str::substr($file, 0, -strlen(self::EXT));
            })
            ->map(function($file) use($daysFolder) {
                return $this->resolveNamespace($daysFolder . DIRECTORY_SEPARATOR . $file . self::EXT) . '\\' . $file;
            })
            ->filter(function($fqdn) {
                return class_exists($fqdn);
            })
            ->filter(function($fqdn) {
                return (new ReflectionClass($fqdn))->isInstantiable();
            })
            ->map(function($fqdn) {
                return $this->container->build($fqdn);
            })
            ->filter(function($instance) {
                return $instance instanceof DayInterface;
            })
            ->each(function(DayInterface $instance) use($inputFolder) {
                $inputPath = $inputFolder . DIRECTORY_SEPARATOR . 'input-day' . $instance->dayNumber() . '.txt';
                if(file_exists($inputPath))
                {
                    $instance->setInput(file_get_contents($inputPath));
                }
            });

    }

    private function resolveNamespace(string $path): string
    {
        $handle = fopen($path, 'r');
        if (!$handle) {
            return null;
        }
        while (($line = fgets($handle)) !== false) {
            if (strpos($line, 'namespace') === 0) {
                fclose($handle);
                $parts = explode(' ', $line);
                return rtrim(trim($parts[1]), ';');
            }
        }
        fclose($handle);
        return '';
    }
}