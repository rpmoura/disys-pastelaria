<?php

namespace Tests\Unit\Listener;

use App\Events\OrderCreated;
use App\Mail\OrderCreated as MailOrderCreated;
use App\Models\{Client, Order, Product};
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendCreatedOrderNotificationTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSendMail()
    {
        Mail::fake();

        $product = Product::factory()->create();
        $order   = Order::factory()->hasAttached($product)->create();
        event(new OrderCreated($order));

        Mail::assertSent(MailOrderCreated::class);
        Mail::assertSentCount(1);
    }

    /**
     * @test
     */
    public function shouldSendMailWith()
    {
        $client   = Client::factory()->create();
        $order    = Order::factory()->hasAttached(Product::factory()->create())->create();
        $mailable = new MailOrderCreated($order);

        $mailable->assertFrom('noreply@example.com');
        $mailable->assertTo($client->email);
        $mailable->assertHasSubject('Your order has been placed, ' . $client->name . '!');
    }
}
