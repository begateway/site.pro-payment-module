<?php
$id = $pluginData->elemId;
if (isset($_POST[$id.'_submit']) && $_POST[$id.'_submit']) {
	require_once(__DIR__.'/begateway/BeGateway.php');

	$useLang = $pluginData->currLangLocale;
  $useLang = substr($useLang, 0, 2);

	$shop_id  = isset($pluginData->shop_id) ? $pluginData->shop_id : null;
	$shop_key = isset($pluginData->shop_key) ? $pluginData->shop_key : null;

	$description = isset($pluginData->orderdescription) ? $pluginData->orderdescription : null;
	$amount = isset($pluginData->amount) ? $pluginData->amount : null;
  $amount = floatval($amount);
	$currency = isset($pluginData->currency) ? $pluginData->currency : null;
  $test_mode = isset($pluginData->demo) ? $pluginData->demo : false;
	$borderCss = isset($pluginData->borderCss) ? $pluginData->borderCss : null;
	$background = isset($pluginData->background) ? $pluginData->background : null;
	$fontFamily = isset($pluginData->fontFamily) ? $pluginData->fontFamily : null;
	$fontSize = isset($pluginData->fontSize) ? $pluginData->fontSize : null;
	$fontColor = isset($pluginData->fontColor) ? $pluginData->fontColor : null;
	$plugin_unique_id = sprintf("%08x", crc32(microtime().'|'.$shop_id));

  \BeGateway\Settings::$shopId = $shop_id;
  \BeGateway\Settings::$shopKey = $shop_key;
  \BeGateway\Settings::$checkoutBase = 'https://checkout.bepaid.by';

	$transaction = new \BeGateway\GetPaymentToken;

	try {
    $protocol = isHttps() ? "https" : "http";
    $hostname = $_SERVER['HTTP_HOST'];
    $path = dirname(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']);

    $transaction->money->setAmount($amount);
    $transaction->money->setCurrency($currency);
    $transaction->setDescription($description);
    $transaction->setTrackingId($plugin_unique_id);
    $transaction->setLanguage($useLang);

    $transaction->setTestMode($test_mode == '1');

    $transaction->setSuccessUrl("{$protocol}://{$hostname}{$path}");
    $transaction->setDeclineUrl("{$protocol}://{$hostname}{$path}");
    $transaction->setFailUrl("{$protocol}://{$hostname}{$path}");

    $response = $transaction->submit();

    if ($response->isSuccess() ) {
  		$paymentUrl = $response->getRedirectUrl();
  		echo '<script type="text/javascript"> location.href = "'.$paymentUrl.'"; </script>';
    } else {
      throw new Exception($response->getMessage());
    }

	} catch (Exception $e) {
		echo '<script>alert("'.htmlspecialchars($e->getMessage()).'");</script>';
	}
}
