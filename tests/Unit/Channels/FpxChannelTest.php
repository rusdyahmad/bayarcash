<?php

namespace Tests\Unit\Channels;

use Bayarcash\Channels\FpxChannel;
use Bayarcash\Http\ApiClient;
use Bayarcash\Support\Configuration;
use Bayarcash\Resources\PaymentIntent;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery;

class FpxChannelTest extends MockeryTestCase
{
    protected $apiClient;
    protected $config;
    protected $fpxChannel;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->apiClient = Mockery::mock(ApiClient::class);
        $this->config = new Configuration(
            'test-pat',
            'test-api-secret-key',
            'test-portal-key',
            [
                'sandbox' => true,
                'api_version' => 'v3',
                'debug' => false,
                'default_channel' => 'FPX',
                'return_url' => 'https://example.com/return',
                'callback_url' => 'https://example.com/callback'
            ]
        );
        
        $this->fpxChannel = new FpxChannel($this->apiClient, $this->config);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testGetStatusLabels()
    {
        $labels = FpxChannel::getStatusLabels();
        
        $this->assertIsArray($labels);
        $this->assertArrayHasKey(FpxChannel::STATUS_NEW, $labels);
        $this->assertArrayHasKey(FpxChannel::STATUS_PENDING, $labels);
        $this->assertArrayHasKey(FpxChannel::STATUS_FAILED, $labels);
        $this->assertArrayHasKey(FpxChannel::STATUS_SUCCESS, $labels);
        $this->assertArrayHasKey(FpxChannel::STATUS_CANCELLED, $labels);
        
        $this->assertEquals('New', $labels[FpxChannel::STATUS_NEW]);
        $this->assertEquals('Pending', $labels[FpxChannel::STATUS_PENDING]);
        $this->assertEquals('Failed', $labels[FpxChannel::STATUS_FAILED]);
        $this->assertEquals('Successful', $labels[FpxChannel::STATUS_SUCCESS]);
        $this->assertEquals('Cancelled', $labels[FpxChannel::STATUS_CANCELLED]);
    }

    public function testGetStatusText()
    {
        $this->assertEquals('New', FpxChannel::getStatusText(FpxChannel::STATUS_NEW));
        $this->assertEquals('Pending', FpxChannel::getStatusText(FpxChannel::STATUS_PENDING));
        $this->assertEquals('Failed', FpxChannel::getStatusText(FpxChannel::STATUS_FAILED));
        $this->assertEquals('Successful', FpxChannel::getStatusText(FpxChannel::STATUS_SUCCESS));
        $this->assertEquals('Cancelled', FpxChannel::getStatusText(FpxChannel::STATUS_CANCELLED));
        $this->assertEquals('UNKNOWN STATUS', FpxChannel::getStatusText(999));
    }

    public function testIsSuccessful()
    {
        $this->assertTrue(FpxChannel::isSuccessful(FpxChannel::STATUS_SUCCESS));
        $this->assertFalse(FpxChannel::isSuccessful(FpxChannel::STATUS_NEW));
        $this->assertFalse(FpxChannel::isSuccessful(FpxChannel::STATUS_PENDING));
        $this->assertFalse(FpxChannel::isSuccessful(FpxChannel::STATUS_FAILED));
        $this->assertFalse(FpxChannel::isSuccessful(FpxChannel::STATUS_CANCELLED));
    }

    public function testIsFailed()
    {
        $this->assertTrue(FpxChannel::isFailed(FpxChannel::STATUS_FAILED));
        $this->assertFalse(FpxChannel::isFailed(FpxChannel::STATUS_NEW));
        $this->assertFalse(FpxChannel::isFailed(FpxChannel::STATUS_PENDING));
        $this->assertFalse(FpxChannel::isFailed(FpxChannel::STATUS_SUCCESS));
        $this->assertFalse(FpxChannel::isFailed(FpxChannel::STATUS_CANCELLED));
    }

    public function testIsPending()
    {
        $this->assertTrue(FpxChannel::isPending(FpxChannel::STATUS_PENDING));
        $this->assertFalse(FpxChannel::isPending(FpxChannel::STATUS_NEW));
        $this->assertFalse(FpxChannel::isPending(FpxChannel::STATUS_FAILED));
        $this->assertFalse(FpxChannel::isPending(FpxChannel::STATUS_SUCCESS));
        $this->assertFalse(FpxChannel::isPending(FpxChannel::STATUS_CANCELLED));
    }

    public function testCreatePaymentIntent()
    {
        $paymentData = [
            'amount' => 100.00,
            'currency' => 'MYR',
            'order_number' => 'TEST-ORDER-123',
            'payer_name' => 'Mohd Ramzy',
            'payer_email' => 'mohdramzy@example.com',
            'payer_phone' => '60123456789',
            'description' => 'Test payment',
        ];
        
        $responseData = [
            'id' => 'pi_123456',
            'amount' => 100.00,
            'currency' => 'MYR',
            'order_number' => 'TEST-ORDER-123',
            'status' => FpxChannel::STATUS_NEW,
            'status_description' => 'New',
            'url' => 'https://example.com/payment/pi_123456',
        ];
        
        $this->apiClient->shouldReceive('request')
            ->once()
            ->with('POST', 'payment-intents', Mockery::on(function ($options) use ($paymentData) {
                $json = $options['json'] ?? [];
                
                // Check that required fields are present
                return isset($json['amount']) 
                    && isset($json['currency']) 
                    && isset($json['order_number'])
                    && isset($json['payer_name'])
                    && isset($json['channel_id'])
                    && $json['channel_id'] === 1; // FPX channel ID
            }))
            ->andReturn($responseData);
        
        $paymentIntent = $this->fpxChannel->createPaymentIntent($paymentData);
        
        $this->assertInstanceOf(PaymentIntent::class, $paymentIntent);
        $this->assertEquals('pi_123456', $paymentIntent->getId());
        $this->assertEquals(100.00, $paymentIntent->getAmount());
        $this->assertEquals('MYR', $paymentIntent->getCurrency());
        $this->assertEquals('TEST-ORDER-123', $paymentIntent->getOrderNumber());
        $this->assertEquals(FpxChannel::STATUS_NEW, $paymentIntent->getStatus());
        $this->assertEquals('New', $paymentIntent->getStatusDescription());
        $this->assertEquals('https://example.com/payment/pi_123456', $paymentIntent->getUrl());
    }
}
