<?php
/**
 * Contains the ConcordModuleTest class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-02-03
 *
 */

namespace Konekt\BearerAuth\Tests;

class ConcordModuleTest extends TestCase
{
    /** @test */
    public function dependent_concord_modules_are_present()
    {
        $modules = $this->app->concord
            ->getModules()
            ->keyBy(function ($module) {
                return $module->getId();
            });

        $this->assertTrue($modules->has('konekt.bearer_auth'), 'Bearer Auth module should be registered');

        $this->assertTrue(
            $modules->get('konekt.bearer_auth')
                    ->getKind()
                    ->isModule(),
            'Concord Module Type Should be a module'
        );
    }
}
