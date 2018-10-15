<?php
namespace App\Library;
use App\User;
use Auth;
use DB;
use Illuminate\Support\Facades\Cache;

/**
 * Custom class for date time functionality ( range ... )
 * @author Ahmed Bltagy
 */
class CustomHelper {

	/**
	 * Get pages slug from DB for regex using
	 * @return string
	 */
	public static function pagesSlug() {
		$pages = \DB::table('pages')->pluck('slug')->toArray();

		return implode('|', $pages);
	}

	public static function is_admin() {
		if (Auth::user()->id) {
			$user = User::find(Auth::user()->id);
			if ($user->id_cms_privileges == 2) {
				return true;
			}

			return false;
		}
	}

	public static function siteOptions($name) {
		$options = Cache::get('site_options', function () {
		    return DB::table('site_options')->get()->toArray();
		});
		foreach ($options as $option) {
			if ( $option->option_name == $name )
				return $option->option_value;
		}
		return false;

	}

}
