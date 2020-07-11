<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Stripe\Stripe;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class StripeController extends AppController
{

    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */

     public function serviceProvider(){
         $this->viewBuilder()->setLayout('stripe');
     }

     public function success($account_id){
         $this->viewBuilder()->setLayout('stripe');
         pr($account_id);die();
     }

     public function doPayment(){
         $this->viewBuilder()->setLayout('stripe');

         Stripe::setApiKey(env('STRIPE_API_KEY', ''));

         $payment_intent = \Stripe\PaymentIntent::create([
            'payment_method_types' => ['card'],
            'amount' => 100,
            'currency' => 'usd',
            'application_fee_amount' => 12,
            // 'on_behalf_of' => 'acct_1H3k7QATwFIpF6n4',
            'transfer_data' => [
            //   'destination' => 'acct_1H3k7QATwFIpF6n4',
              'destination' => 'acct_1H3l3aKhtIcazBar',
            ],
          ]);

          $this->set(['client_secret' => $payment_intent->client_secret]);
     }
}
