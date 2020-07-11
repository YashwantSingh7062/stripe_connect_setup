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
class ApiController extends AppController
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

     public function getOauthLink(){
         $this->autoRender = false;
         $session = $this->getRequest()->getSession();
         $state = bin2hex(random_bytes('16')); 
         $session->write('state', $state); 
       
         $params = array(
           'state' => $state,
           'client_id' => env('STRIPE_CLIENT_ID', ''),
           'stripe_user[email]' => 'yashwantmasqar@mailinator.com',
           'redirect_uri' => 'http://localhost/demos/stripe-connect/Api/authorizeOauth'
         );
       
         $url = 'https://connect.stripe.com/express/oauth/authorize?' . http_build_query($params);
         echo json_encode(array('url' => $url));
     }

     public function authorizeOauth(){
        $this->autoRender = false;
        $session = $this->getRequest()->getSession();

        Stripe::setApiKey(env('STRIPE_API_KEY', ''));
        $data = $this->request->getQuery();
      
        // Assert the state matches the state you provided in the OAuth link (optional).
        if ($session->read('state') != $data['state']){
            echo json_encode(['error' => 'Incorrect state parameter: '.$data['state'].' - '.$session->read('state')]);
            exit();
        }

        // Send the authorization code to Stripe's API.
        try {
          $stripeResponse = \Stripe\OAuth::token([
            'grant_type' => 'authorization_code',
            'code' => $data['code'],
          ]);
        } catch (\Stripe\Error\OAuth\InvalidGrant $e) {
          echo json_encode(['error' => 'Incorrect authorization code: '.$data['code']]);
          exit();
        } catch (\Exception $e) {
            echo json_encode(['error' => 'An unknown error occurred. ']);
            exit();
        }
      
        $connectedAccountId = $stripeResponse->stripe_user_id;
      
        $this->redirect(['controller' => 'Stripe', 'action' => 'success', $connectedAccountId]);
     }
}
