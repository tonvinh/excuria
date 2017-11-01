<?php

namespace App;

use DB;
use Memcached;

//use Image;
//use Intervention\Image\Exception\NotReadableException;

class Navigator
{
	public $cities;
	public $activities;

	public function __construct()
	{
		$this->cities = $this->getCitites();

		$this->activities = $this->getActivites();
		$location_city = $this->getLocation();

		$firstCity = null;
		$arrCities = null;

		foreach ($this->cities as $city) {
			if (isset($location_city) && strlen($location_city) > 0 && strpos($location_city, $city->name) !== false) {
				$firstCity[] = $city;
			} else {
				$arrCities[] = $city;
			}
		}

		if (isset($firstCity) && is_array($firstCity)) {
			/* make first city data is 1st */
			$arrCities = array_merge($firstCity, $arrCities);
		}

		$this->cities = $arrCities;
	}

	private function getLocation()
	{
		$location_city = null;
		$geocode = null;

		$ip = Location::get_client_ip();

		if (isset($ip) && strlen($ip) > 0) {
			$mem = new Memcached();
			$mem->addServer(env('memcached_server'), env('memcached_port'));

			$resultGeo = $mem->get($ip);

			if ($resultGeo) {
				$geocode = $resultGeo;
			} else {
				$none_memcache = false;

				$dataGeo = app('geocoder')->geocode($ip)->get();

				$mem->set($ip, $dataGeo) or $none_memcache = true;

				if ($none_memcache) {
					/* None memcache */
					$geocode = $dataGeo;
				} else {
					/* Use memcache */
					$geocode = $mem->get($ip);
				}
			}
		}

		if (isset($geocode)) {
			/* get current city by IPS */
			$location_city = $geocode->first()->getLocality();

			if (isset($location_city) && strlen($location_city) > 0) {
				$location = new Location();
				/* Write site visitor's log */
				$location->logSiteVistor();
			}
		}

		return $location_city;
	}

	private function getActivites()
	{
		$activities = null;

		$mem = new Memcached();
		$mem->addServer(env('memcached_server'), env('memcached_port'));
		$resultMem = $mem->get('rollout_activities');

		if ($resultMem) {

			$activities = $resultMem;

		} else {

			$none_memcache = false;

			$data = DB::table('roullout_city_activity')
				->leftjoin('activity', 'roullout_city_activity.activity_id', 'activity.activity_id')
				->select('roullout_city_activity.activity_id', 'activity.name', 'activity.image')
				->orderBy('activity.name')
				->distinct()
				->get();

			/* image resize */
			/*foreach ($data as $activity) {
				$activity->image_s = ImageJob::Resize($activity->image,568, 398);
			}*/

			$result = $data;

			$mem->set('rollout_activities', $result) or $none_memcache = true;

			if ($none_memcache) {
				/* None memcache */
				$activities = $data;
			} else {
				/* Use memcache */
				$activities = $mem->get('rollout_activities');
			}

		}

		return $activities;
	}


	private function getCitites()
	{
		$cities = null;

		$mem = new Memcached();
		$mem->addServer(env('memcached_server'), env('memcached_port'));
		$resultMem = $mem->get('rollout_cities');

		if ($resultMem) {

			$cities = $resultMem;

		} else {

			$none_memcache = false;

			$data = DB::table('city')
				->leftjoin('roullout_city_activity', 'city.city_id', 'roullout_city_activity.city_id')
				->leftjoin('country', 'city.country_id', 'country.country_id')
				->select('city.city_id', 'city.name', 'city.image')
				->orderBy('city.name')
				->distinct()
				->get();

			/* image resize */
			/*foreach ($data as $city) {
				$city->image_s = ImageJob::Resize($city->image,568, 398);
			}*/

			$result = $data;

			$mem->set('rollout_cities', $result) or $none_memcache = true;

			if ($none_memcache) {
				/* None memcache */
				$cities = $data;
			} else {
				/* Use memcache */
				$cities = $mem->get('rollout_cities');
			}

		}

		return $cities;
	}

	/**
	 * Return activity info by name
	 * @param $name
	 * @return null
	 */
	public function getActivityByName($name)
	{
		$dataActivity = null;
		foreach ($this->activities as $activity) {
			if (strpos($name, $activity->name) !== false) {
				$dataActivity = $activity;
				break;
			}
		}

		return $dataActivity;
	}

	/**
	 * Return city info by name
	 * @param $name
	 * @return null
	 */
	public function getCityByName($name)
	{
		$dataCity = null;
		foreach ($this->cities as $city) {
			if (strpos($name, $city->name) !== false) {
				$dataCity = $city;
				break;
			}
		}

		return $dataCity;
	}
}