<?php

namespace App;

use DB;

class City
{

	/**
	 * List cites by Activity (id)
	 *
	 * @param $activity_id
	 * @return null
	 */
	public static function CitiesByActivity($activity_id, $activity_name = '', $activity_image)
	{
		$data = null;
		if (strlen($activity_id) > 0) {
			$cities = DB::table('city')
				->leftjoin('roullout_city_activity', 'city.city_id', 'roullout_city_activity.city_id')
				->leftjoin('country', 'city.country_id', 'country.country_id')
				->select('city.city_id','city.name', 'city.image')
				->where('roullout_city_activity.activity_id', '=', $activity_id)
				->distinct()
				->distinct()
				->get();

			/* image resize */
			/*foreach ($cities as $city)
			{
				$city->image_s = ImageJob::Resize($city->image,568, 398);
			}*/

			$data = array('activity_id' => $activity_id, 'activity_name' => $activity_name, 'activity_image' => $activity_image, 'cities' => $cities);
		}
		return $data;
	}
}