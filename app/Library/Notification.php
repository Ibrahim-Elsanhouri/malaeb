<?php namespace App\Library;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
/**
* Custom class for Push Notification  
* @author Ahmed Bltagy
*/
class Notification 
{
	
	public static function send( $user_id = 'all', $data )
	{	
		if ( empty( $data ) )
			return false;
 		


	}
	
}
