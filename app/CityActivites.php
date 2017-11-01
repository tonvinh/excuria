<?php

namespace App;

use DB;

class CityActivites
{
	public $location = null;    /* location infos IPS by IP address */
	public $activites = null;    /* activities of all city of the location country */

	public function __construct()
	{
		$location = new Location();

		if (isset($location)) {
			$this->location = $location;

			foreach ($this->location->citiesData as $city) {
				$this->activites[] = ['city' => $city->name, 'activities' => $this->CityActivites($city->city_id)];
			}
		}
	}

	/**
	 * All activities of the city
	 * select entity.city_id, program.activity_id from `program` left join `provider` on `program`.`provider_id` = `provider`.`provider_id` left join `entity` on `provider`.`entity_id` = `entity`.`entity_id` left join `activity` on `program`.`activity_id` = `activity`.`activity_id` left join `activity_classification` on `activity_classification`.`activity_classification_id` = `activity`.`activity_classification_id` left join `location` on `entity`.`city_id` = `location`.`city_id` group by entity.country_id, entity.city_id, program.activity_id
	 *
	 * @param $city_id
	 * @return null
	 */
	private function CityActivites($city_id)
	{
		$activities = null;
		if (strlen($city_id) > 0) {
			$activities = DB::table('roullout_city_activity')
				->leftjoin('activity', 'roullout_city_activity.activity_id', 'activity.activity_id')
				->select('roullout_city_activity.activity_id', 'activity.name', 'activity.image')
				->where('city_id', 'like', $city_id)
				->distinct()
				->get();
		}
		return $activities;
	}

	/**
	 * Return activites of the location's current city
	 *
	 * @param $city_name
	 * @return null
	 */
	public function ActivitiesOfCurrentCity($city_name)
	{
		$result = null;

		foreach ($this->activites as $info) {
			if (strpos($city_name, $info['city']) !== false) {
				$result = $info['activities'];
				break;
			}
		}

		return $result;
	}

	/**
	 * Activities by the city id (none IPS)
	 *
	 * @param $city_id
	 * @param string $city_name
	 * @return array|null
	 */
	public static function ActivitiesByCity($city_id, $city_name = '', $city_image = '')
	{
		$data = null;
		if (strlen($city_id) > 0) {
			$dataActivites = DB::table('roullout_city_activity')
				->leftjoin('activity', 'roullout_city_activity.activity_id', 'activity.activity_id')
				->select('roullout_city_activity.activity_id', 'activity.name', 'activity.image')
				->where('roullout_city_activity.city_id', 'like', $city_id)
				->distinct()
				->get();

			/* image resize */
			/*foreach ($dataActivites as $activity) {
				$activity->image_s = ImageJob::Resize($activity->image,568, 398);
			}*/

			$data = array('city_id' => $city_id, 'city_name' => $city_name, 'city_image' => $city_image, 'activities' => $dataActivites);
		}
		return $data;
	}
}