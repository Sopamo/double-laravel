<?php

namespace Sopamo\Double;

use Illuminate\Support\Str;

class PHPLoader {

    /**
     * Returns the absolute frontend path for the given file path relative to the frontend root
     *
     * @param string $filePath
     * @return string
     */
    public function getFrontendPath(string $relativePath = ''): string {
        // Prevent some path trickery
        if(Str::contains($relativePath, '..')) {
            throw new \InvalidArgumentException('File path contains invalid characters');
        }
        if(Str::startsWith($relativePath, DIRECTORY_SEPARATOR)) {
            $relativePath = substr($relativePath, 1);
        }
        $absolutePath = config('double.frontend_root') . DIRECTORY_SEPARATOR . $relativePath;

        $extension = '.php';

        if(!file_exists($absolutePath . $extension)) {
            throw new \InvalidArgumentException('File path ' . $relativePath . ' is invalid. I was looking for ' . $absolutePath . '.php');
        }
        return $absolutePath . $extension;
    }

    /**
     * Returns the php source of the given `double` file.
     *
     * @param string $path
     */
    public function getSource(string $path) {
        $absolutePath = $this->getFrontendPath($path);
        $source = require($absolutePath);
        return $source;
    }
}