<?php

namespace App\Http\Controllers;

use App\Models\PaypalPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;

class PaymentController extends Controller
{
    private $apiContext;

    public function __construct()
    {
        $paypalConfig = Config::get('paypal');
        
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                $paypalConfig['cliente_id'],
                $paypalConfig['secret']
            )    
        );
        
        $this->apiContext->setConfig($paypalConfig['settings']);
    }

    public function payWithPayPal(){

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new Amount();
        $amount->setTotal('3.20');
        $amount->setCurrency('USD');

        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription('Plan Chat UCaldas');
        
        $callbackUrl = url('/paypal/status');

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($callbackUrl) // no tiene dinero
            ->setCancelUrl($callbackUrl);   // decidio cancelar
            
        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions(array($transaction))
            ->setRedirectUrls($redirectUrls);

        try {
            $payment->create($this->apiContext);
            return redirect()->away($payment->getApprovalLink());
        } catch (PayPalConnectionException $ex) {
            echo $ex->getData();
        }
    }

    public function payPalStatus(Request $request)
    {
        $paymentId = $request->input('paymentId');
        $payerId = $request->input('PayerID');
        $token = $request->input('token');
        
        // No existe alguna variable
        if (!$paymentId || !$payerId || !$token) {
            $status = 'Lo sentimos! El pago a través de PayPal no se pudo realizar.';
            return redirect('/dashboard')->with(compact('status'));
        }

        $payment = Payment::get($paymentId, $this->apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        /** Execute the payment **/
        $result = $payment->execute($execution, $this->apiContext);
        
        if ($result->getState() === 'approved') {
            $status = 'Gracias! El pago a través de PayPal se ha realizado correctamente.';
        
            // Obtener información relevante del pago
            $paymentId = $result->getId();
            $payerId = $result->getPayer()->getPayerInfo()->getPayerId();
            $amount = $result->getTransactions()[0]->getAmount()->getTotal();
        
           // Obtener al usuario que realizó el pago
            $user = auth()->user();
            $user->rol = 2;
            $user->save();

             // Obtener la fecha de vencimiento (por ejemplo, 10 días después de hoy)
            $expirationDate = now()->addDays(5);

            // Crear un registro en la tabla de pagos
            $paymentRecord = new PaypalPayment([
                'user_id' => auth()->user()->id, // o la lógica que uses para obtener el ID del usuario
                'payment_id' => $paymentId,
                'amount' => $amount,
                'expiration_date' => $expirationDate,
            ]);
            $paymentRecord->save();
            return redirect('/dashboard')->with(compact('status'));
        }

        $status = 'Lo sentimos! El pago a través de PayPal no se pudo realizar...';
        return redirect('/dashboard')->with(compact('status'));
    }


    

    // Validar Plan de cada usuario
    public function pagos(Request $request) {
        if (Auth::check()) {
            $user = auth()->user();
            $pagos = $user->paypalPayments()->get();
            return response()->json([
                "success" => true,
                "pagos" => $pagos,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "User is not authenticated.",
            ], 401); // Código de estado 401 indica no autorizado
        }
    }
}
