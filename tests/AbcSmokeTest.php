<?php

declare(strict_types=1);

/**
 * Contains the AbcSmokeTest class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-02-03
 *
 */

namespace Konekt\BearerAuth\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class AbcSmokeTest extends PHPUnitTestCase
{
    public const MIN_PHP_VERSION = '7.4.0';

    /**
     * Very Basic smoke test case for testing against parse errors, etc
     *
     * @test
     */
    public function smoke()
    {
        $this->assertTrue(true);
    }

    /**
     * Test for minimum PHP version
     *
     * @depends smoke
     * @test
     */
    public function php_version_satisfies_requirements()
    {
        $this->assertFalse(
            version_compare(PHP_VERSION, self::MIN_PHP_VERSION, '<'),
            'PHP version ' . self::MIN_PHP_VERSION . ' or greater is required but only '
            . PHP_VERSION . ' found.'
        );
    }
}
