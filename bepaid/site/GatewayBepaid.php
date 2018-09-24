<?php
require_once(__DIR__.'/begateway/BeGateway.php');

class GatewayBepaid extends PaymentGateway {

	private $_shop_id;
  private $_shop_key;
	private $_lang;
  private $_demo;

	public function init() {
    if (isset($this->config->shop_id) && $this->config->shop_id
        && isset($this->config->shop_key) && $this->config->shop_key) {
      $this->_shop_id = $this->config->shop_id;
      $this->_shop_key = $this->config->shop_key;
    }

    $this->_demo = (isset($this->config->demo) && $this->config->demo) ? true : false;

    $this->_lang = $this->config->wbLang ? $this->config->wbLang : $this->config->wbBaseLang;

		return parent::init();
	}

	public function getTransactionId() {
    \BeGateway\Settings::$shopId = $this->_shop_id;
    \BeGateway\Settings::$shopKey = $this->_shop_key;
    $webhook = new \BeGateway\Webhook;

    if ($webhook->isAuthorized() && $webhook->getTrackingId()) {
      return $webhook->getTrackingId();
    }
    return null;
	}

	public function createRedirectUrl($formVars) {
		$redirectPath = null;
		$error = null;
		$currency = $formVars['currency'];
		if ($formVars['amount'] &&
        $formVars['description'] &&
        $formVars['orderId'] &&
        $formVars['webhookUrl'] &&
        $formVars['cancelUrl'] &&
        $formVars['redirectUrl']) {
			$description = htmlspecialchars($formVars['description'], ENT_QUOTES);
			$amount = floatval(htmlspecialchars($formVars['amount'], ENT_QUOTES));
			$orderId = htmlspecialchars($formVars['orderId'], ENT_QUOTES);
			$webhookUrl = htmlspecialchars($formVars['webhookUrl'], ENT_QUOTES);
			$redirectUrl = htmlspecialchars($formVars['redirectUrl'], ENT_QUOTES);
			$cancelUrl = htmlspecialchars($formVars['cancelUrl'], ENT_QUOTES);

			try {

        \BeGateway\Settings::$shopId = $this->_shop_id;
        \BeGateway\Settings::$shopKey = $this->_shop_key;
        \BeGateway\Settings::$checkoutBase = 'https://checkout.bepaid.by';
        \BeGateway\Settings::$gatewayBase = 'https://gateway.bepaid.by';

      	$transaction = new \BeGateway\GetPaymentToken;
        $transaction->money->setAmount($amount);
        $transaction->money->setCurrency($currency);
        $transaction->setDescription($description);
        $transaction->setTrackingId($orderId);
        $transaction->setLanguage($this->_lang);

        $transaction->setTestMode($this->_demo);

        $transaction->setSuccessUrl($redirectUrl);
        $transaction->setDeclineUrl($cancelUrl);
        $transaction->setFailUrl($cancelUrl);
        $transaction->setNotificationUrl($webhookUrl);

        $cartData = StoreData::getCartData();
        if ($cartData) {
          $transaction->customer->setFirstName($cartData->billingInfo->firstName);
          $transaction->customer->setLastName($cartData->billingInfo->lastName);

          $transaction->customer->setAddress($cartData->billingInfo->address1);
          $transaction->customer->setCity($cartData->billingInfo->city);
          $transaction->customer->setZip($cartData->billingInfo->postCode);
          $transaction->customer->setEmail($cartData->billingInfo->email);

    			if (isset($cartData->billingInfo->countryCode) && $cartData->billingInfo->countryCode) {
    				$country = StoreCountry::findByCode($cartData->billingInfo->countryCode);
    				if ($country && $country->isoCode2) {
              $transaction->customer->setCountry($country->isoCode2);
    				}
    			}
        }

        $response = $transaction->submit();

        if ($response->isSuccess() ) {
      		$paymentUrl = $response->getRedirectUrl();
        } else {
          throw new Exception($response->getMessage());
        }
			} catch (Exception $e) {
				$error = htmlspecialchars($e->getMessage());
			}
		}
		$this->setLastError($error);
		return $paymentUrl;
	}

	public function callback(StoreModuleOrder $order = null) {
    \BeGateway\Settings::$shopId = $this->_shop_id;
    \BeGateway\Settings::$shopKey = $this->_shop_key;
    $webhook = new \BeGateway\Webhook;
    $status = false;

    if ($order && $webhook->isAuthorized()) {
			if ($webhook->isFailed()) {
				$order->setState(StoreModuleOrder::STATE_FAILED);
      } elsif ($webhook->isPending() || $webhook->isIncomplete()) {
        $order->setState(StoreModuleOrder::STATE_PENDING);
			} elsif ($webhook->isSuccess()) {
        $order->setState(StoreModuleOrder::STATE_COMPLETE);
        $status = true;
			}
      $order->save();
		}
		header('HTTP/1.0 200 OK');
		echo "OK $status";
		return $status;
	}
}
