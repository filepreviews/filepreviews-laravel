<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Request;

use FilePreviews\Laravel\WebhookController;

class WebhookControllerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        Facade::clearResolvedInstances();
    }

    public function testProperMethodsAreCalledBasedOnStatus()
    {
        $_SERVER['__received'] = false;

        Request::shouldReceive('getContent')->andReturn(json_encode([
            'status' => 'success',
            'id' => 'preview-id'
        ]));

        $controller = new WebhookControllerTestStub;
        $controller->handleWebhook();

        $this->assertTrue($_SERVER['__received']);
    }

    public function testNormalResponseIsReturnedIfMethodIsMissing()
    {
        Request::shouldReceive('getContent')->andReturn(json_encode([
            'status' => 'pending',
            'id' => 'preview-id'
        ]));

        $controller = new WebhookControllerTestStub;
        $response = $controller->handleWebhook();

        $this->assertEquals(200, $response->getStatusCode());
    }
}

class WebhookControllerTestStub extends WebhookController
{
    public function handleSuccess(array $payload)
    {
        $_SERVER['__received'] = true;
    }

    protected function existsOnFilePreviews($id)
    {
        return true;
    }
}
