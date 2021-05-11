<?php

namespace Guave\InstallerBundle\Composer;

use Composer\Script\Event;

class ScriptHandler
{
    public static function replaceNameDir(Event $event): void
    {
        $project = $event->getArguments()[0];

        $dirs = [
            'files/template' => 'files/' . $project,
            'frontend/template' => 'frontend/' . $project,
            'templates/template' => 'templates/' . $project,
        ];

        foreach ($dirs as $old => $new) {
            if (!rename($old, $new)) {
                echo 'Could not rename ' . $old . ' to ' . $new;
            }
        }
    }

    public static function replaceNameFile(Event $event): void
    {
        $project = $event->getArguments()[0];

        $files = [
            '.env.local.guave' => [4],
            '.env.stage' => [4],
            '.env.prod' => [4],
            'Gruntfile.js' => [47],
            'jsconfig.json' => [14],
            'webpack.config.js' => [25],
            'build/dotenv.json' => [2, 3, 4, 6],
            'config/custom.yml' => [2],
            'contao/config/config.php' => [23],
            'system/config/localconfig.dev.php' => [6],
            'system/config/localconfig.live.php' => [6],
            'templates/' . $project . '/base.html5' => [23, 54],
        ];

        foreach ($files as $file => $lines) {
            if (!self::fileNameReplacer($file, $lines, $project)) {
                echo 'Could not rename ' . $file . ' on lines ' . implode(', ', $lines);
            }
        }
    }

    public static function fileNameReplacer($file, $lines, $newName): bool
    {
        $loadedFile = file($file);
        $result = $loadedFile;

        foreach ($lines as $line) {
            $result[$line - 1] = preg_replace('/\bproject\b|\btemplate\b/', $newName, $loadedFile[$line - 1]);
        }

        if (!file_put_contents($file, $result)) {
            return false;
        }

        return true;
    }
}
