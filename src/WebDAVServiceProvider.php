<?php

namespace Pbmedia\FilesystemProviders;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use League\Flysystem\WebDAV\WebDAVAdapter;
use Sabre\DAV\Client as WebDAVClient;

class WebDAVServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Storage::extend('webdav', function ($app, $config) {
            $client = new WebDAVClient($config);

            if (array_key_exists('httpVersion', $config)) {
                $client->addCurlSetting(CURLOPT_HTTP_VERSION, $config['httpVersion']);
            }

            $adapter = new WebDAVAdapter(
                $client,
                array_key_exists('pathPrefix', $config) ? $config['baseUri'].'/'.$config['pathPrefix'] : null,
                true,
                $config['internalHttpPort'] ?? null
            );

            return new Filesystem($adapter);
        });
    }

    public function register()
    {

    }
}
