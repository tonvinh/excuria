<?php

namespace App;

use DB;

class Location
{
	public $city; /* current location's city */
	public $country; /* current location's country */
	public $country_code; /* country code */
	public $ip_address; /* ip address */
	public $latitude; /* latitude */
	public $longitude; /* longtitude */
	public $country_id; /* country_id on database */
	public $city_id; /* city_id on database */
	public $citiesData; /* all cities of that country on Database */

    public function __construct()
	{
		$this->ip_address = $this::get_client_ip();

		if (isset($this->ip_address) && strlen($this->ip_address) > 0) {

			$results = app('geocoder')->geocode($this->ip_address)->get();

			$this->country = $results->first()->getCountry()->getName();
			$this->country_code = $results->first()->getCountry()->getCode();
			$this->city = $results->first()->getLocality();
			$this->latitude = $results->first()->getcoordinates()->getLatitude();
			$this->longitude = $results->first()->getcoordinates()->getLongitude();

			if ($this->country_code != null && $this->city != null) {
				/* all cities of that country on Database */
				$this->citiesData = $this->getCityInfo($this->country_code);
			}
		}
	}

	/**
	 * Return client ip
	 *
	 * @return string
	 */
	public static function get_client_ip() {

		try {
			$ip = file_get_contents('https://api.ipify.org');
		}
		catch (\Exception $ex)
		{
			return null;
		}

		return $ip;
	}

	/***
	 * If exit _country code on database will return all cities of that _country
	 * @param string $_country <p>
	 * country code</p>
	 * @param string $_city <p>
	 * city name</p>
	 * @return
	 */
	private function getCityInfo($_country)
	{
		$cities = DB::table('city')
			->leftjoin('roullout_city_activity', 'city.city_id', 'roullout_city_activity.city_id')
			->leftjoin('country', 'city.country_id', 'country.country_id')
			->select('city.city_id','city.name', 'city.image')
			->where('country.code', 'like', $_country)
			->distinct()
			->get();

		$country= DB::table('country')->where('country.code', 'like', $_country)->select('country_id')->first();
		$this->country_id = $country->country_id;

		$result = null;

		foreach ($cities as $city) {
			if (strpos($this->city,$city->name) !== false)
			{
				$this->city_id = $city->city_id;
			}
			$result[] = $city;
		}

		//$this->logSiteVistor();

		return $result;
	}

	public function logSiteVistor()
	{
		if (isset($this->ip_address) && strlen($this->ip_address) > 0
			&& isset($this->country_id) && strlen($this->country_id) > 0
			&& isset($this->city_id) && strlen($this->city_id) > 0
		) {
			$log = new SiteVistor();
			$log->ip_address = $this->ip_address;
			$log->country_isp_name = $this->country;
			$log->country_id = $this->country_id;
			$log->city_isp_name = $this->city;
			$log->city_id = $this->city_id;
			$log->save();
		}
	}
}