<?php use Illuminate\Support\Number; ?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<body style="background-color:#e2e1e0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
<table style="max-width:670px;margin:50px auto 10px;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px green;">
    <thead>
    <tr>
        <th style="text-align:left;">{{ config('app.name') }}</th>
        <th style="text-align:right;font-weight:400;">{{ $order->created_at->format('Y-m-d') }}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="height:35px;"></td>
    </tr>
    <tr>
        <td colspan="2" style="border: solid 1px #ddd; padding:10px 20px;">
            <p style="font-size:14px;margin:0 0 6px 0;">
                <span style="font-weight:bold;display:inline-block;min-width:146px">
                    Transaction ID
                </span> {{ $order->uuid }}

            </p>
            <p style="font-size:14px;margin:0 0 0 0;">
                <span style="font-weight:bold;display:inline-block;min-width:146px">
                    Order amount
                </span> {{ Number::currency($order->total) }}
            </p>
        </td>
    </tr>
    <tr>
        <td style="height:35px;">
        </td>
    </tr>
    <tr>
        <td style="width:50%;padding:20px;vertical-align:top">
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;">
                <span style="display:block;font-weight:bold;font-size:13px">Name</span> {{ $order->client->name }}

            </p>
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;">
                <span style="display:block;font-weight:bold;font-size:13px;">Email</span> {{ $order->client->email }}

            </p>
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;">
                <span style="display:block;font-weight:bold;font-size:13px;">Phone</span> {{ $order->client->phone }}

            </p>
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;">
                <span style="display:block;font-weight:bold;font-size:13px;">ID No.</span> {{ $order->client->uuid }}

            </p>
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;">
                <span style="display:block;font-weight:bold;font-size:13px;">Birth Date</span> {{ $order->client->birth_date }}

            </p>
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;">
                <span style="display:block;font-weight:bold;font-size:13px;">Address</span> {{ $order->client->address }}

            </p>
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;">
                <span style="display:block;font-weight:bold;font-size:13px;">Neighborhood</span> {{ $order->client->neighborhood }}

            </p>
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;">
                <span style="display:block;font-weight:bold;font-size:13px;">Add On</span> {{ $order->client->add_on ?? '-' }}

            </p>
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;">
                <span style="display:block;font-weight:bold;font-size:13px;">Postcode</span> {{ $order->client->postcode }}

            </p>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="font-size:15px;font-weight:bold;padding:30px 15px 0 0;">Items</td>
        <td colspan="1" style="font-size:15px;font-weight:bold;padding:30px 15px 0 0;">Value</td>
    </tr>
    @foreach($order->products as $product)
    <tr>
        <td colspan="2" style="border:solid 1px #ddd;padding:10px 0;">
            <p style="font-size:14px;margin:0;padding:10px;font-weight:bold;">
                <span style="display:block;font-size:13px;font-weight:normal;">{{ $product->name }}</span>
            </p>
        </td>
        <td colspan="1" style="border:solid 1px #ddd;padding:10px 0;">
            <p style="font-size:14px;margin:0;padding:10px;font-weight:bold;">
                    {{ Number::currency($product->price) }}
            </p>
        </td>
    </tr>
    @endforeach;
    </tbody>
</table>
</body>

</html>