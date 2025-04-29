# Bayarcash PHP SDK

A PHP SDK for integrating with the Bayarcash payment gateway API.

[![GitHub Repository](https://img.shields.io/badge/GitHub-Repository-blue.svg)](https://github.com/rusdyahmad/bayarcash)

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
  - [Laravel Integration](#laravel-integration)
  - [Standalone PHP](#standalone-php)
- [Usage](#usage)
  - [Creating a Client](#creating-a-client)
  - [Payment Channels](#payment-channels)
  - [Creating Payment Intents](#creating-payment-intents)
  - [Retrieving Transactions](#retrieving-transactions)
  - [Bank Listing](#bank-listing)
  - [Portal Management](#portal-management)
  - [FPX Direct Debit](#fpx-direct-debit)
  - [Handling Callbacks](#handling-callbacks)
- [Available Payment Channels](#available-payment-channels)
- [Testing](#testing)
- [Security](#security)
- [License](#license)

## Installation

You can install the package via composer:

```bash
composer require rusdyahmad/bayarcash
```

Or add it directly to your composer.json file:

```json
"require": {
    "rusdyahmad/bayarcash": "^1.0"
}
```

## Configuration

### Laravel Integration

If you're using Laravel, the package includes a service provider and facade for easy integration.

1. Publish the configuration file:

```bash
php artisan vendor:publish --provider="Bayarcash\Laravel\BayarcashServiceProvider"
```

2. Add your Bayarcash credentials to your `.env` file:

```env
BAYARCASH_PAT=your-personal-access-token
BAYARCASH_API_SECRET_KEY=your-api-secret-key
BAYARCASH_PORTAL_KEY=your-portal-key
BAYARCASH_SANDBOX=true
BAYARCASH_API_VERSION=v3
BAYARCASH_DEBUG=false
BAYARCASH_DEFAULT_CHANNEL=FPX
BAYARCASH_RETURN_URL=https://your-site.com/payment/return
BAYARCASH_CALLBACK_URL=https://your-site.com/payment/callback
```

3. You can now use the `Bayarcash` facade in your application:

```php
use Bayarcash\Laravel\Facades\Bayarcash;

$transaction = Bayarcash::getTransaction('trx_123456');
```

### Standalone PHP

For standalone PHP applications, you'll need to create a configuration object:

```php
use Bayarcash\Support\Configuration;

$config = new Configuration(
    'your-personal-access-token',
    'your-api-secret-key',
    'your-portal-key',
    true, // sandbox mode
    'v3', // API version
    false, // debug mode
    'FPX', // default channel
    'https://your-site.com/payment/return', // return URL
    'https://your-site.com/payment/callback' // callback URL
);
```

## Usage

### Creating a Client

#### Laravel

```php
use Bayarcash\Laravel\Facades\Bayarcash;

// The client is automatically created using your configuration
$paymentIntent = Bayarcash::createPaymentIntent([
    'amount' => 100.00,
    'order_number' => 'ORDER-123',
    // ...
]);
```

#### Standalone PHP

```php
use Bayarcash\Bayarcash;
use Bayarcash\Support\Configuration;

// Create a configuration object
$config = new Configuration(
    'your-personal-access-token',
    'your-api-secret-key',
    'your-portal-key'
);

// Create a client
$bayarcash = new Bayarcash($config);

// Create a payment intent
$paymentIntent = $bayarcash->createPaymentIntent([
    'amount' => 100.00,
    'order_number' => 'ORDER-123',
    // ...
]);
```

### Payment Channels

The SDK supports multiple payment channels. You can specify the channel when creating a payment intent:

```php
// Using the FPX channel
$paymentIntent = $bayarcash->channel('FPX')->createPaymentIntent([
    'amount' => 100.00,
    'order_number' => 'ORDER-123',
    // ...
]);

// Using the DuitNow DOBW channel
$paymentIntent = $bayarcash->channel('DuitnowDobw')->createPaymentIntent([
    'amount' => 100.00,
    'order_number' => 'ORDER-123',
    // ...
]);
```

### Creating Payment Intents

#### FPX Payment

```php
// Create a payment intent using FPX
$paymentIntent = $bayarcash->createPaymentIntent([
    'payment_channel' => 1, // FPX channel ID (integer)
    'portal_key' => 'your-portal-key', // Portal key from your Bayarcash account
    'order_number' => 'ORDER-' . time(),
    'amount' => 1000, // Amount in cents (RM 10.00)
    'payer_name' => 'John Doe',
    'payer_email' => 'john@example.com',
    'payer_telephone_number' => '60123456789', // Malaysia number format
    'payer_bank_code' => 'ABB0234', // Optional, bank code from getBanks()
    'return_url' => 'https://your-site.com/payment/return', // Server to browser redirect
    'callback_url' => 'https://your-site.com/payment/callback', // Server to server callback
]);

// Get the payment URL
$paymentUrl = $paymentIntent->getPaymentUrl();

// Redirect the user to the payment page
header("Location: {$paymentUrl}");
exit;
```

### Retrieving Transactions

```php
// Get a transaction by ID
$transaction = $bayarcash->getTransaction('trx_123456');

// Check the transaction status
if ($transaction->isSuccessful()) {
    // Process successful payment
    echo "Payment successful!";
    echo "Amount: " . $transaction->getAmount() . " " . $transaction->getCurrency();
    echo "Transaction ID: " . $transaction->getId();
} else {
    // Handle failed payment
    echo "Payment failed: " . $transaction->getStatusDescription();
}
```

### Bank Listing

#### FPX Banks

```php
// Get the list of FPX banks
$banks = $bayarcash->getBanks();

// Display bank information
foreach ($banks as $bank) {
    echo $bank->getDisplayName() . ' (' . $bank->getCode() . ')';
    echo $bank->isAvailable() ? ' - Available' : ' - Not Available';
    echo PHP_EOL;
}
```

#### DuitNow DOBW Banks

```php
// Get the list of DuitNow DOBW banks
$banks = $bayarcash->getDuitNowDobwBanks();

// Display bank information
foreach ($banks as $bank) {
    echo $bank->getName() . ' (' . $bank->getCode() . ')';
    echo $bank->isAvailable() ? ' - Available' : ' - Not Available';
    echo PHP_EOL;
}
```

### Portal Management

#### Listing Portals

```php
// Get the list of portals (first page)
$portalCollection = $bayarcash->getPortals();

// Access portal information
foreach ($portalCollection->getPortals() as $portal) {
    echo "Portal: " . $portal->getPortalName() . " (" . $portal->getPortalKey() . ")\n";
    echo "URL: " . $portal->getUrl() . "\n";

    // Access payment channels for each portal
    echo "Supported payment channels:\n";
    foreach ($portal->getPaymentChannels() as $channel) {
        echo "- " . $channel->getName() . " (" . $channel->getCode() . ")\n";
    }
    echo "\n";
}

// Access pagination information
echo "Page " . $portalCollection->getCurrentPage() . " of " . $portalCollection->getLastPage() . "\n";
echo "Total portals: " . $portalCollection->getTotal() . "\n";

// Get a specific page
$portalCollection = $bayarcash->getPortals(2);
```

#### Creating a Portal

```php
// Create a basic portal
$portal = $bayarcash->createPortal('My New Portal');

// Create a portal with all options
$portal = $bayarcash->createPortal(
    'My Custom Portal',
    'https://mycustomportal.com',
    'notifications@example.com',
    'Pay Now with Bayarcash',
    [1, 3, 4] // Enable FPX, Direct Debit, and FPX Line of Credit
);

// Access the created portal information
echo "Portal created: " . $portal->getPortalName() . "\n";
echo "Portal Key: " . $portal->getPortalKey() . "\n";
echo "Portal URL: " . $portal->getUrl() . "\n";
```

### FPX Direct Debit

#### Creating a Direct Debit Mandate

```php
// Create a direct debit mandate using FPX
$mandate = $bayarcash->createDirectDebitMandate([
    'payment_channel' => 5, // FPX Direct Debit channel ID (integer)
    'portal_key' => 'your-portal-key', // Portal key from your Bayarcash account
    'payer_name' => 'John Doe',
    'payer_id_type' => 1, // 1 for NRIC, 2 for Passport, 3 for Army ID, 4 for Police ID
    'payer_id' => '910109021234', // ID number
    'payer_email' => 'john@example.com',
    'payer_telephone_number' => '60123456789', // Malaysia number format
    'payer_bank_code' => 'ABB0234', // Bank code from getBanks()
    'order_number' => 'DD-' . time(),
    'amount' => 3000, // Amount in cents (RM 30.00)
    'application_type' => 'Subscription',
    'application_reason' => 'Monthly subscription',
    'frequency_mode' => 'MT', // MT for Monthly, YR for Yearly, etc.
    'effective_date' => '2025-05-01',
    'expiry_date' => '2026-05-01',
    'return_url' => 'https://your-site.com/mandate/return', // Server to browser redirect
    'callback_url' => 'https://your-site.com/mandate/callback', // Server to server callback
]);

// Get the authorization URL
$authorizationUrl = $mandate->getAuthorizationUrl();

// Redirect the user to the authorization page
header("Location: {$authorizationUrl}");
exit;
```

#### Getting Mandate Details

```php
// Get a mandate by ID
$mandate = $bayarcash->getMandate('md_MGWpzp');

// Access mandate information
echo "Mandate ID: " . $mandate->getId() . "\n";
echo "Reference Number: " . $mandate->getMandateReferenceNumber() . "\n";
echo "Status: " . $mandate->getStatusDescription() . " (" . $mandate->getStatus() . ")\n";
echo "Payer: " . $mandate->getPayerName() . "\n";
echo "ID Type: " . $mandate->getPayerIdTypeText() . "\n";
echo "Amount: " . $mandate->getAmount() . " " . $mandate->getCurrency() . "\n";
echo "Frequency: " . $mandate->getFrequencyModeLabel() . "\n";
```

#### Updating a Mandate

```php
// Update a mandate
$updatedMandate = $bayarcash->updateMandate('md_MGWpzp', [
    'payer_name' => 'Mohd Ramzy',
    'payer_id_type' => 1,
    'payer_id' => '910109021234',
    'payer_email' => 'mohdramzy@example.com',
    'payer_telephone_number' => '60198109001',
    'order_number' => 'DD001',
    'amount' => 30,
    'application_type' => 'Maintenance',
    'application_reason' => 'Maintenance of DD001',
    'frequency_mode' => 'YR',
    'effective_date' => '2024-06-15',
    'expiry_date' => '2024-08-15'
]);

// Access the updated mandate information
echo "Updated Mandate: " . $updatedMandate->getId() . "\n";
echo "Amount: " . $updatedMandate->getAmount() . " " . $updatedMandate->getCurrency() . "\n";
echo "Frequency: " . $updatedMandate->getFrequencyModeLabel() . "\n";
```

#### Terminating a Mandate

```php
// Terminate a mandate
$terminatedMandate = $bayarcash->terminateMandate('md_MGWpzp', [
    'application_reason' => 'Termination of DD001'
]);

// Access the termination URL
echo "Termination URL: " . $terminatedMandate->getReturnUrl() . "\n";
```

#### Getting Mandate Transaction Details

```php
// Get a mandate transaction by ID
$transaction = $bayarcash->getMandateTransaction('trx_GPk868');

// Access transaction information
echo "Transaction ID: " . $transaction->getId() . "\n";
echo "Date: " . $transaction->getDatetime() . "\n";
echo "Amount: " . $transaction->getAmount() . " " . $transaction->getCurrency() . "\n";
echo "Status: " . $transaction->getStatusDescription() . " (" . $transaction->getStatus() . ")\n";
echo "Payment Gateway: " . $transaction->getPaymentGatewayName() . "\n";

// Access the associated mandate information
if ($transaction->getMandate()) {
    $mandate = $transaction->getMandate();
    echo "Mandate ID: " . $mandate->getId() . "\n";
    echo "Mandate Status: " . $mandate->getStatusDescription() . "\n";
    echo "Frequency: " . $mandate->getFrequencyModeLabel() . "\n";
}
```

### Handling Callbacks

#### Transaction Callbacks

```php
use Bayarcash\Callbacks\TransactionCallback;
use Bayarcash\Exceptions\InvalidCallbackException;

// In a controller method
public function handleCallback(Request $request)
{
    try {
        // Create a transaction callback instance from the request data
        $callback = TransactionCallback::fromRequest($request->all());

        // Verify the checksum using your API secret key
        $apiSecretKey = config('bayarcash.api_secret_key');
        if (!$callback->verifyChecksum($apiSecretKey)) {
            Log::warning('Invalid Bayarcash callback checksum', $callback->toArray());
            return response()->json(['status' => 'error', 'message' => 'Invalid checksum'], 400);
        }

        // Process the transaction based on its status
        if ($callback->isSuccessful()) {
            // Handle successful payment
            $order = Order::where('order_number', $callback->getOrderNumber())->first();
            if ($order) {
                $order->markAsPaid();
                $order->transaction_id = $callback->getTransactionId();
                $order->save();
            }
        } elseif ($callback->isFailed()) {
            // Handle failed payment
            // ...
        }

        // Return 200 OK response as required by Bayarcash
        return response()->json(['status' => 'success']);
    } catch (InvalidCallbackException $e) {
        Log::error('Invalid Bayarcash callback: ' . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
    }
}
```

#### FPX Direct Debit Callbacks

```php
use Bayarcash\Callbacks\FpxDirectDebitAuthorizationCallback;
use Bayarcash\Callbacks\FpxDirectDebitBankApprovalCallback;
use Bayarcash\Callbacks\FpxDirectDebitTransactionCallback;
use Bayarcash\Exceptions\InvalidCallbackException;

// In a controller method
public function handleDirectDebitCallback(Request $request)
{
    $data = $request->all();
    $apiSecretKey = config('bayarcash.api_secret_key');

    try {
        // Determine callback type based on record_type
        if ($data['record_type'] === 'authorization') {
            $callback = FpxDirectDebitAuthorizationCallback::fromRequest($data);
        } elseif ($data['record_type'] === 'bank_approval') {
            $callback = FpxDirectDebitBankApprovalCallback::fromRequest($data);
        } elseif ($data['record_type'] === 'transaction') {
            $callback = FpxDirectDebitTransactionCallback::fromRequest($data);
        } else {
            throw new InvalidCallbackException("Unknown record type");
        }

        // Verify the checksum
        if (!$callback->verifyChecksum($apiSecretKey)) {
            Log::warning('Invalid FPX Direct Debit callback checksum', $callback->toArray());
            return response()->json(['status' => 'error', 'message' => 'Invalid checksum'], 400);
        }

        // Process the callback based on its type
        // ...

        // Return 200 OK response as required by Bayarcash
        return response()->json(['status' => 'success']);
    } catch (InvalidCallbackException $e) {
        Log::error('Invalid FPX Direct Debit callback: ' . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
    }
}
```

## Available Payment Channels

The SDK supports the following payment channels:

- `FPX` - FPX (Financial Process Exchange)
- `DuitnowDobw` - DuitNow Online Banking/Wallets
- `DuitnowQr` - DuitNow QR
- `FpxDirectDebit` - FPX Direct Debit
- `SPayLater` - SPay Later
- `BoostPayflex` - Boost PayFlex
- `Qris` - QRIS
- `Nets` - NETS

Each channel has specific features and requirements. Refer to the Bayarcash API documentation for details.

## Testing

```bash
composer test
```

## Security

If you discover any security related issues, please email <support@bayarcash.com> instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
