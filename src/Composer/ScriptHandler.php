<?php

namespace Guave\InstallerBundle\Composer;

use Composer\Script\Event;

class ScriptHandler
{
    public static function replaceNameDir(Event $event)
    {
        $project = $event->getArguments()[0];
        
        $dirs = [
            'files/project' => 'files/' . $project,
            'frontend/project' => 'frontend/' . $project,
            'templates/project' => 'templates/' . $project,
        ];
        
        foreach ($dirs as $old => $new) {
            if (!rename($old, $new)) {
                echo 'Could not rename ' . $old . ' to ' . $new;
            }
        }
    }

    public static function replaceNameFile(Event $event)
    {
        $project = $event->getArguments()[0];
        
        $files = [
            '.env.example' => [24, 27, 28],
            'Gruntfile.js' => [44],
            'jsconfig.json' => [14],
            'webpack.config.js' => [25],
            'build/dotenv.json' => [2, 3, 4],
            'config/custom.yml' => [3],
            'config/parameters.dev.yml' => [5, 6, 7],
            'config/parameters.stage.yml' => [5, 6, 7],
            'config/parameters.live.yml' => [5, 6, 7],
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

    public function fileNameReplacer($file, $lines, $newName)
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
