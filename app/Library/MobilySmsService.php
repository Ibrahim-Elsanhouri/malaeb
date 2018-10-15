<?php namespace App\Library;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
/**
* Custom class for SMS service functionality 
* @author Ahmed Bltagy
*/
class MobilySmsService 
{
	
	public static function send( $mobile, $code, $message='' )
	{	
		if ( empty( $mobile ) || empty( $code ))
			return false;
		$mobile = substr($mobile, -9);
		$mobile = '966'.$mobile;
		$client = new Client(); //GuzzleHttp\Client
		$message = ( empty( $message ) ) ? 'Your confirmation key is: '.$code : $message.' '.$code;
		$result = $client->request('GET','http://www.mobily.ws/api/msgSend.php', [
		'query' => [
			    'mobile' => '966502674789',
			    'password' => 'qwerty78',
			    'numbers' => $mobile,
			    'sender' => '0502674789',
			    'msg' => $message,
			    'lang' => 'UTF-8',
			    'applicationType' => '68'
			    ]

		]);
		if (  $result->getBody()->read(4) == 1 )
			return true;
		return false ;

	}
	
}
