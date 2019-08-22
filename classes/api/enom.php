<?php
require_once( __DIR__ . '/enom/transaction.php' );


Class Enom 
{

	public static function send($command,$values,$ns = NULL, $owner = NULL, $adminc = NULL, $techc = NULL)
	{
		$transaction = new EnomTransaction();

		//General
 		$transaction->_setCommand($command); //Check|Purchase|Extend|TP_CreateOrder|GetRegLock|SetRegLock|GetDomainExp|GetContacts|Contacts|GetDNS|ModifyNS|GetDomainNameID|SynchAuthInfo
    		$transaction->_setParseDomain($values["domainname"]);

		//Check
		$transaction->addParam("DomainSpinner", "0"); //Sugguested other domainnames @ availibility check
		
  		//Purchase
		if ($values["extension"] == "nu")
    			$transaction->addParam("NumYears"	 , "2"	  );
		else
    			$transaction->addParam("NumYears"	 , "1"	  );
		$transaction->addParam("NS1"		 ,$ns->ns1);
		$transaction->addParam("NS2"		 ,$ns->ns2);
		$transaction->addParam("NS3"		 ,$ns->ns3);
      		$transaction->addParam('UnLockRegistrar', '0'    );
      		$transaction->addParam('Lock'		 , ($command != "SetDomainExp") ? "1" : "0");

		foreach (Array("Registrant" => $owner
                              ,"Admin"      => $adminc
                              ,"Tech"       => $techc) as $contactType => $contact)
		{
      			$transaction->addParam( $contactType . 'EmailAddress'		, $contact->email 	);
			$transaction->addParam( $contactType . 'Fax'			, $contact->fax 	);
			$transaction->addParam( $contactType . 'Phone'			, $contact->phone 	);
			$transaction->addParam( $contactType . 'Country'		, "NL"                  ); 
			$transaction->addParam( $contactType . 'PostalCode'		, $contact->zipcode 	);
			$transaction->addParam( $contactType . 'StateProvinceChoice'	, 'Blank' 		);
			$transaction->addParam( $contactType . 'StateProvince'		, '' 			);
			$transaction->addParam( $contactType . 'City'			, $contact->city 	);
			$transaction->addParam( $contactType . 'Address1'		, $contact->street . " " . $contact->housenr 	);
			$transaction->addParam( $contactType . 'LastName'		, $contact->lastname 	);
			$transaction->addParam( $contactType . 'FirstName'		, $contact->firstname	);
			//$transaction->addParam( $contactType . 'JobTitle'		, "Dhr"			);
			$transaction->addParam( $contactType . 'OrganizationName'	, $contact->company	);
		}

		//TP_CreateOrder
    		$transaction->addParam('TLD1'		, $transaction->params['TLD']	);
    		$transaction->addParam('SLD1'		, $transaction->params['SLD']	);
    		//unset($transaction->params['TLD'], $transaction->params['SLD']);
    		$transaction->addParam('OrderType'	, 'Autoverification'	);
    		$transaction->addParam('DomainCount'	, '1'			);
      		$transaction->addParam('AuthInfo1'	, $values["authcode"]	);
      		$transaction->addParam('UseContacts'	, '0'			); //no contact info transfer from Whois

		//SynchAuthInfo
    		$transaction->addParam('EmailEPP'		, "1"		);
    		$transaction->addParam('RunSynchAutoInfo'	, "1"		);
    
		return $transaction->process()
		
		;//DomainCheck
    		//if( !isset($transaction->response['DomainCount']) || '1' == $transaction->response['DomainCount'] )
    		//{
    		//  return ('210' == $transaction->response['RRPCode']) ? true : false;
    		//}

		//if ($transaction->response['ErrCount'] != "0")
		//	return $transaction->_returnError();
		//else
		//	return true;
      		//return $transaction->response['OrderID']; //Purchase|Extend
      		//return $transaction->response['transferorderid']; //TP_CreateOrder
    		//return $transaction->response['DomainNameID']; //GetDomainNameID

	}
}
  
?>
