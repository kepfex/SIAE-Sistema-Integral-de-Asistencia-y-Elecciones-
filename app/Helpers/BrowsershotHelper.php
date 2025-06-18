<?php

namespace App\Helpers;

use Spatie\Browsershot\Browsershot;

class BrowsershotHelper
{
    public static function fromHtml(string $html): Browsershot
    {
        
        $isWindows = PHP_OS_FAMILY === 'Windows';
        $chromePath = $isWindows
            ? env('BROWSERSHOT_CHROME_PATH_WINDOWS', 'C:\Program Files\Google\Chrome\Application\chrome.exe')
            : env('BROWSERSHOT_CHROME_PATH_LINUX', '/usr/bin/google-chrome'); // Ajusta si usas otro path

        $tempPath = $isWindows
            ? storage_path('app/browsershot-html')
            : '/home/www-data/browsershot-html';

        $userDataDir = $isWindows
            ? storage_path('app/chrome-user-data')
            : '/home/www-data/user-data';

        if (!is_dir($tempPath)) {
            mkdir($tempPath, 0755, true);
        }
        if (!is_dir($userDataDir)) {
            mkdir($userDataDir, 0755, true);
        }

        return Browsershot::html($html)
            ->setOption('args', ['--disable-web-security'])
            ->ignoreHttpsErrors()
            ->noSandbox()
            ->setCustomTempPath($tempPath)
            ->addChromiumArguments([
                'lang' => 'es-PE',
                'hide-scrollbars',
                'enable-font-antialiasing',
                'force-device-scale-factor' => 1,
                'font-render-hinting' => 'none',
                'user-data-dir' => $userDataDir,
                'disk-cache-dir' => $userDataDir . '/Default/Cache',
            ])
            ->setChromePath($chromePath)
            ->newHeadless()
            ->showBackground();
    }
}
