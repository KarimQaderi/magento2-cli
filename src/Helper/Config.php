<?php

namespace M2\Cli\Helper;

use M2\Cli\Traits\Singleton;

/**
 * @property Path    path
 * @property InputIO inputIO
 */
class Config
{
    use Singleton;

    public function __construct()
    {
        $this->path = Path::getInstance();
        $this->inputIO = InputIO::getInstance();
    }

    protected function getConfig($name)
    {
        $config = require $this->path->cli('config/' . $name . '.php');

        return $config;
    }

    protected function m2Config($key = null)
    {
        $pathMagento = $this->path->cli('config/app.php');

        if (file_exists($this->path->dir('m2-config.php'))) {
            $pathMagento = $this->path->dir('m2-config.php');
        }

        $config = require $pathMagento;

        if (isset($config[$key])) {
            return $config[$key];
        }

        return [];
    }

    public function askDefault($key = null)
    {
        $config = $this->m2Config('askDefault');

        if (empty($key)) {
            return $config;
        }

        if (isset($config[$key])) {
            return $config[$key];
        }

        return '';
    }

    public function command()
    {
        return $this->m2Config('command');
    }

    public function commandDefault()
    {
        return $this->getConfig('command');
    }

    public function commandAll()
    {
        $items = [];

        $items = array_merge($items, $this->commandDefault(), $this->command());

        $this->addMenuDev($items);

        return $items;
    }

    protected function addMenuDev(&$items)
    {
        $items['+'] = [
            'title' => 'dev',
            'code' => '',
            'map' => '+',
            'deps' => [],
            'callback' => function () {
                $item = $this->inputIO->menu([
                    'add-config' => [
                        'title' => 'add config for current magento',
                        'code' => '',
                        'deps' => [],
                        'callback' => function () {
                            $srcConfig = $this->path->cli('config/app.php');

                            $content = file_get_contents($srcConfig);

                            file_put_contents($this->path->magento('m2-config.php'), $content);

                            Helper::getInstance()->alert('add file m2-config.php');
                        },
                    ],

                    'add-router-2-4' => [
                        'title' => 'router > 2.4',
                        'code' => '',
                        'deps' => [],
                        'callback' => function () {
                            $router = $this->path->stub('router-2.4', true);
                            file_put_contents($this->path->magento('router.php'), $router);
                            Helper::getInstance()->alert('add file router.php');
                        },
                    ],

                    'add-router-2-2' => [
                        'title' => 'router < 2.2',
                        'code' => '',
                        'deps' => [],
                        'callback' => function () {
                            $router = $this->path->stub('router-2.2', true);
                            file_put_contents($this->path->magento('router.php'), $router);
                            Helper::getInstance()->alert('add file router.php');
                        },
                    ],
                ]);


                if (isset($item['callback'])) {
                    $item['callback']();
                }
            },
        ];
    }
}
