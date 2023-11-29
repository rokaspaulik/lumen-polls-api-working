<?php

namespace Tests\Unit\V2;

use Tests\TestCase;

class PresentationControllerTest extends TestCase
{
    public function testCreate()
    {
        // Mock the Guzzle client
        $guzzleMock = $this->getMockBuilder(\GuzzleHttp\Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Set up the expected Guzzle post method call
        $guzzleMock->expects($this->once())
            ->method('post')
            ->with(
                'https://infra.devskills.app/api/interactive-presentation/v4/presentations',
                $this->callback(function ($options) {
                    // Add any additional validation for the Guzzle request options here if needed
                    return isset($options['headers']['Content-Type'])
                        && $options['headers']['Content-Type'] === 'application/json'
                        && isset($options['json']['current_poll_index'])
                        && isset($options['json']['polls']);
                })
            )
            ->willReturn(new \GuzzleHttp\Psr7\Response(200, [], '{"presentation_id": "8627b957-216f-4176-a2f7-43e4f483bf72"}'));

        // Replace the actual Guzzle client with the mocked one
        $this->app->instance(\GuzzleHttp\Client::class, $guzzleMock);

        // Make a request to the endpoint
        $this->json('POST', '/api/v2/presentations', [
            'current_poll_index' => 0,
            'polls' => [
                [
                    'poll_id' => '123e4567-e89b-12d3-a456-426614174000',
                    'question' => 'Example Question',
                    'options' => [
                        ['key' => 'A', 'value' => 'Option A'],
                        ['key' => 'B', 'value' => 'Option B'],
                    ],
                ],
            ],
        ], ['Authorization' => 'Bearer your_api_v2_token']);

        $this->assertResponseStatus(200);
        $this->response->assertContent('{"presentation_id": "8627b957-216f-4176-a2f7-43e4f483bf72"}');
    }
}
