<?php

/**
 * Test: DI\VersionExtension
 */

use Contributte\Latte\DI\VersionExtension;
use Nette\Bridges\ApplicationDI\LatteExtension;
use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Tester\Assert;
use Tester\FileMock;

require_once __DIR__ . '/../../bootstrap.php';

test(function () {
    $loader = new ContainerLoader(TEMP_DIR, TRUE);
    $class = $loader->load(function (Compiler $compiler) {
        $compiler->addExtension('latte', new LatteExtension(TEMP_DIR));
        $compiler->addExtension('version', new VersionExtension());
        $compiler->loadConfig(FileMock::create('
        version:
            rev: 1
            build: 2
            v: 3
        ', 'neon'));
    }, 1);

    /** @var Container $container */
    $container = new $class;

    /** @var ILatteFactory $latteFactory */
    $latteFactory = $container->getByType(ILatteFactory::class);

    Assert::equal('123', $latteFactory->create()->renderToString(FileMock::create('{rev}{build}{v}', 'latte')));
});
