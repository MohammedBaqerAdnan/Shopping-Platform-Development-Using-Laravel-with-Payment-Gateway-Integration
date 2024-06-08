<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class CheckoutController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }
    public function process(Request $request)
    {
        // Pack data for payment initiation
        $data = [
            'unique_id' => '560885',
            'amount' => $request->price,
            'api_type' => '2',
            'action' => 'background',
            'tax_rate' => '10',
            'currency_code' => 'USD',
            'currency_rate' => '0.38',
            'callback_url' => 'http://127.0.0.1:8000/shop/' . $request->item_id,
            'show_callback' => '1',


        ];

        // Create GuzzleHttp client instance
        $client = new \GuzzleHttp\Client();

        // Send a GET request to Gate-E's API
        $response = $client->request('GET', 'https://test.gate-e.com/api/v01/process.php', [
            'query' => $data,
            'verify' => false,
        ]);

        // Get json response body
        $responseBody = json_decode($response->getBody(), true);

        // Check the status in the response
        if ($responseBody['status'] === 'success') {
            // Payment initiated successfully

            // Extract payment_id and payment_url
            $payment_id = $responseBody['payment_id'];
            $payment_url = $responseBody['payment_url'];

            // Store payment_id in the database (or session, etc) write code 

            // Check if a payment record for the current item id already exists
            $payment = Payment::where('item_id', $request->item_id)->first();


            // If user has selected to save card
            if ($request->save_card) {
                $this->getPayment($responseBody['payment_id']);
            }

            // if it does, update the existing payment record
            if ($payment) {
                $payment->payment_id = $payment_id;
                $payment->save();
            }
            // otherwise, create a new payment record
            else {
                $payment = new Payment();
                $payment->payment_id = $payment_id;
                $payment->item_id = $request->item_id;
                $payment->save();
            }


            // Redirect the user to the payment page
            return redirect()->away($payment_url);
        }

        // Payment initiation failed
        // Log the error, and return a failure message to the user
        return 'Payment initiation failed';

    }
    public function getPayment($payment_id)
    {
        // Send a GET request to Gate-E's API
        $response = $this->client->request('GET', 'https://www.test.gate-e.com/api/getpayment.php', [
            'query' => ['payment_id' => $payment_id],
            'verify' => false,
        ]);

        // Get JSON response body
        $responseBody = json_decode($response->getBody(), true);

        // Check if the card code exists in the response
        if (isset($responseBody['card']) && isset($responseBody['card']['code'])) {
            // Save the card code in your database
            $payment = Payment::where('payment_id', $payment_id)->first();

            if ($payment) {
                $payment->card_code = $responseBody['card']['code'];
                $payment->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'Card details saved successfully',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Failed to save card details',
        ]);
    }
    public function verifyPayment(Request $request)
    {
        $payment_id = $request->payment_id; // Get the payment_id from the request

        $client = new \GuzzleHttp\Client();

        // Send GET request to retrieve payment transaction
        $response = $client->request('GET', 'https://www.test.gate-e.com/api/getpayment.php', [
            'query' => [
                'unique_id' => '560885',
                'hash' => '4CF0PQUGN6S5EQZVURZRPHKE2CID1AS9',
                'payment_id' => $payment_id,
            ],
            'verify' => false,
        ]);

        $responseBody = json_decode($response->getBody(), true);

        // Check if 'assigned' is equal to 'YES' and 'status' is equal to 'completed'
        if ($responseBody['assigned'] === 'YES' && $responseBody['status'] === 'completed') {
            return 'Payment is successful';
        }

        return 'Payment is not successful';
    }
    public function getPaymentStatus($item_id)
    {
        // Retrieve the payment record for the provided item ID
        $payment = Payment::where('item_id', $item_id)->first();

        if (!$payment) {
            return 'Not Paid.';
        }

        $client = new \GuzzleHttp\Client();

        // Send GET request to retrieve payment transaction
        $response = $client->request('GET', 'https://www.test.gate-e.com/api/getpayment.php', [
            'query' => [
                'unique_id' => '560885',
                'hash' => '4CF0PQUGN6S5EQZVURZRPHKE2CID1AS9',
                'payment_id' => $payment->payment_id,
            ],
            'verify' => false,
        ]);

        $responseBody = json_decode($response->getBody(), true);

        // Check if 'assigned' is equal to 'YES' and 'status' is equal to 'completed'
        if ($responseBody['assigned'] === 'YES' && $responseBody['status'] === 'completed') {
            return 'Item is Paid.';
        }

        return 'Not Paid.';
    }
    public function updatePaymentStatus(Request $request)
    {
        $item_id = $request->item_id; // get the item_id from the request

        // Get the corresponding payment record for this item
        $payment = Payment::where('item_id', $item_id)->first();

        // Check that a matching payment record was found
        if (!$payment) {
            echo "No payment found for Item ID: " . $item_id;
            return;
        }

        $processed = $request->processed; // get the processed status from the request

        $queryParams = [
            'unique_id' => '560885',
            'hash' => '4CF0PQUGN6S5EQZVURZRPHKE2CID1AS9',
            'payment_id' => $payment->payment_id,
            'processed' => $processed,
        ];

        $fullURL = 'https://www.test.gate-e.com/api/updatepayment.php?' . http_build_query($queryParams);

        echo "Full URL: " . $fullURL; // print full URL

        $response = $this->client->request('GET', 'https://www.test.gate-e.com/api/updatepayment.php', [
            'query' => $queryParams,
            'verify' => false,
        ]);

        $responseBody = json_decode($response->getBody(), true);

        if ($responseBody['status'] === 'success') {
            echo "Payment Updated Successfully";
        } else {
            echo "Failed to update payment";
        }
    }
    public function autoDeduct($payment_id, $card_code)
    {
        $payment = Payment::where('payment_id', $payment_id)->first();
        if (!$payment || !$payment->card_code) {
            echo "Failed to find payment or card";
            return;
        }

        $response = $this->client->post('https://www.test.gate-e.com/api/auto_deduct.php', [
            'form_params' => [
                'payment_id' => $payment_id,
                'token_code' => $payment->card_code,
                'security_code' => '369',
            ],
        ]);
        $responseBody = json_decode($response->getBody(), true);

        if ($responseBody['status']) {
            echo "Successful payment";
        } else {
            echo "Failed to deduct payment";
        }
    }

}