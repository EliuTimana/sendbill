<?php 

	class CustomHeaders extends SoapHeader { 
		private 
		$wss_ns = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd'; 
		function __construct($user, $pass, $ns = null) { 
			if ($ns) { $this->wss_ns = $ns; } 
			$auth = new stdClass(); 
			$auth->Username = new SoapVar($user, XSD_STRING, NULL, $this->wss_ns, NULL, $this->wss_ns); 
			$auth->Password = new SoapVar($pass, XSD_STRING, NULL, $this->wss_ns, NULL, $this->wss_ns); 
			$username_token = new stdClass(); 
			$username_token->UsernameToken = new SoapVar($auth, SOAP_ENC_OBJECT, NULL, $this->wss_ns, 'UsernameToken', $this->wss_ns); 
			$security_sv = new SoapVar( new SoapVar(
				$username_token, 
				SOAP_ENC_OBJECT, 
				NULL, $this->wss_ns, 
				'UsernameToken', $this->wss_ns
			), SOAP_ENC_OBJECT, NULL, $this->wss_ns, 'Security', $this->wss_ns); 
			parent::__construct($this->wss_ns, 'Security', $security_sv, true); 
		} 
	}

	$service = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl'; 

	$headers = new CustomHeaders('20532710066MODDATOS', 'MODDATOS'); 
	$client = new SoapClient($service, [ 
		'cache_wsdl' => WSDL_CACHE_NONE, 
		'trace' => TRUE , 
		'soap_version' => SOAP_1_1 ] 
	); 
	$client->__setSoapHeaders([$headers]); 

	$fcs = $client->__getFunctions();

	$fileName = '20532710066-01-F001-1.zip'; 	
	$params = array( 'fileName' => $fileName, 'contentFile' => file_get_contents($fileName) ); 
	$status = $client->sendBill($params);



	//print_r($status);



?>