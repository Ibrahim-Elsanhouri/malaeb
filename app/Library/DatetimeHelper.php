<?php namespace App\Library\DatetimeHelper;
/**
* Custom class for date time functionality ( range ... )
* @author Ahmed Bltagy
*/
class DateTimeDiff 
{
	
	public static function diffHours($range)
	{	
		if ( empty( $range ) ) return false;
		$date1 = new \DateTime(date('h:i a',$range['from']));
		$date2 = new \DateTime(date('h:i a',$range['to']));
		$diff = $date2->diff($date1);
		$hours = $diff->h;
		$hours = $hours + ($diff->days*24);
		return  $hours;

	}

	public static function diffDays($range)
	{	
		if ( empty( $range ) ) return false;
		$date1 = new \DateTime(date('m/d/Y',$range['from']));
		$date2 = new \DateTime(date('m/d/Y',$range['to']));
		$diff = $date2->diff($date1);
		$days = $diff->days;
		return  $days;

	}

	public static function diffDaysFromNow($to)
	{	
		if ( empty( $to ) ) return false;
		$date1 = new \DateTime(date('Y-m-d'));
		$date2 = new \DateTime(date('Y-m-d',strtotime($to)));
		$diff = $date2->diff($date1);
		$days = $diff->days;
		return  $days;
	}

	public static function diffHoursFromNow($to)
	{	
		if ( empty( $to ) ) return false;
		$date1 = new \DateTime(date('Y-m-d h:i a'));
		$date2 = new \DateTime(date('Y-m-d h:i a',strtotime($to)));
		$diff = $date2->diff($date1);
		$hours = $diff->h;
		$hours = $hours + ($diff->days*24);
		return  $hours;
	}


	public static function validateDate($date)
	{
		return true;
	    $d = \DateTime::createFromFormat('Y-m-d', $date);
	    return $d && $d->format('Y-m-d') === $date;
	}

	public static function validateDateTime($datetime)
	{
	    $d = \DateTime::createFromFormat('Y-m-d Hi', $datetime);
	    return $d && $d->format('Y-m-d Hi') === $datetime;
	}

	public static function dayDate($date)
	{
	    $d = \DateTime::createFromFormat('Y-m-d', $date);
	    $days = ["Sat" => "السبت", "Sun" => "الأحد", "Mon" => "الإثنين", "Tue" => "الثلاثاء", "Wed" => "الأربعاء", "Thu" => "الخميس", "Fri" => "الجمعة"];
	    return $days[$d->format('D')].'  '.$date;
	}

	
}
