upload:
	cd bepaid && rsync -rltP . ubuntu@sitepro.begateway.com:/var/www/html/plugins/bepaid
