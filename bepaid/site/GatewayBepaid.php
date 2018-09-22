<?php

class GatewayBeGateway extends PaymentGateway {

	private $apiKey;
	private $lang;

	public function init() {
		global $store_mollie_api_key, $base_lang, $lang;
		if ($store_mollie_api_key) {
			$this->apiKey = htmlspecialchars($store_mollie_api_key, ENT_QUOTES);
		}
		$this->lang = $lang ? $lang : $base_lang;

    if ($this->lang == 'de2') {
      $this->lang = 'de'
    }
		return parent::init();
	}

	public function getTransactionId() {
		if (isset($_POST["id"])) {
			require_once(__DIR__.'/mollie/src/Mollie/API/Autoloader.php');
			$mollie = new Mollie_API_Client;
			$mollie->setApiKey($this->apiKey);
			$payment  = $mollie->payments->get($_POST["id"]);
			$order_id = $payment->metadata->order_id;
			if($order_id){
				return $order_id;
			} else {
				return null;
			}
		}
		return null;
	}

	public function createRedirectUrl($formVars) {
		$redirectPath = null;
		$error = null;
		$currency = StoreData::getCurrency();
		if (!$currency || !isset($currency->code) || $currency->code != 'EUR') {
			$error = 'Unsupported currency (EUR only)';
		} else if ($formVars['amount'] && $formVars['description'] && $formVars['orderId'] && $formVars['webhookUrl'] && $formVars['redirectUrl']) {
			$description = htmlspecialchars($formVars['description'], ENT_QUOTES);
			$amount = floatval(htmlspecialchars($formVars['amount'], ENT_QUOTES));
			$orderId = htmlspecialchars($formVars['orderId'], ENT_QUOTES);
			$webhookUrl = htmlspecialchars($formVars['webhookUrl'], ENT_QUOTES);
			$redirectUrl = htmlspecialchars($formVars['redirectUrl'], ENT_QUOTES);

			require_once(__DIR__.'/mollie/src/Mollie/API/Autoloader.php');
			try {
				$mollie = new Mollie_API_Client;
				$mollie->setApiKey($this->apiKey);
				$payment = $mollie->payments->create(array(
					"amount" => $amount,
					"description" => $description,
					"redirectUrl" => $redirectUrl,
					"webhookUrl" => $webhookUrl,
					"locale" => $this->lang,
					"metadata" => array(
						"order_id" => $orderId,
					),
				));
				$paymentUrl = $payment->getPaymentUrl();
				if ($paymentUrl) {
					$redirectPath = $paymentUrl;
				}
			} catch (Mollie_API_Exception $e) {
				$error = htmlspecialchars($e->getMessage());
			}
		}
		$this->setLastError($error);
		return $redirectPath;
	}

	public function callback(StoreModuleOrder $order = null) {
		if ($order) {
			$buyerData = $order->getBuyer()->getData();
			$fullname = ($buyerData['first_name']) ? $buyerData['first_name'].($buyerData['last_name'] ? ' '.$buyerData['last_name'] : '') : null;
			$email = $buyerData['email'] ? $buyerData['email'] : null;
			$address = $buyerData['address'] ? $buyerData['address'] : null;
			$phone = $buyerData['phone'] ? $buyerData['phone'] : null;
			$newBuyerData = array(
				'Name' => $fullname,
				'Email' => $email
			);
			if ($address) $newBuyerData['Address'] = $address;
			if ($phone) $newBuyerData['Phone'] = $phone;
			$order->getBuyer()->setData($newBuyerData);
			$order->save();
		}
	}

}
