<?php

namespace Genesis\Foundation;

class Mix
{
    /**
     * Get the path to a versioned Mix file.
     *
     * @param string $path
     *
     * @return string
     */
    public function __invoke(string $path)
    {
        $file = public_path('mix-manifest.json');

        if (!file_exists($file)) {
            return asset($path, true);
        }
        $json = json_decode(app('files')->get($file));

        $path = '/' . trim($path, '/');

        return asset(trim($json->$path ?? $path, '/'), true);
    }
}
