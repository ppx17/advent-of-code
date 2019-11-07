<?php


namespace Ppx17\Aoc2019\Aoc\Runner;


use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DayLoader
{
    private const EXT = '.php';
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function load(string $folder): Collection
    {
        $content = scandir($folder);
        if (!$content) return new Collection();

        return collect($content)
            ->filter(function($file) {
                return Str::endsWith($file, self::EXT);
            })
            ->map(function($file) {
                return Str::substr($file, 0, -strlen(self::EXT));
            })
            ->map(function($file) use($folder) {
                return $this->resolveNamespace($folder . DIRECTORY_SEPARATOR . $file . self::EXT) . '\\' . $file;
            })
            ->filter(function($fqdn) {
                return class_exists($fqdn);
            })
            ->map(function($fqdn) {
                return $this->container->build($fqdn);
            })
            ->filter(function($instance) {
                return $instance instanceof DayInterface;
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