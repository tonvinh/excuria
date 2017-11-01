<?php

namespace App\Http\Controllers;

use App\City;
use App\CityActivites;
use App\ImageJob;
use App\Navigator;

use DB, View;
use Illuminate\Http\Request;

class NavigatorController extends Controller
{
	public function index(Request $request)
	{
		$navigator = new Navigator();

		if (sizeof($request->input()) === 0) {

			return view('navigator', array(
				'cities' => $navigator->cities,
				'activities' => $navigator->activities,
				'search' => 0,
			));
		} elseif ($request->input('ObjectType')) {
			$data = null;
			$objectType = $request->input(('ObjectType'));

			switch ($objectType) {
				case 'city':
					$city_name = $request->input(('City'));
					$city = $navigator->getCityByName($city_name);

					$data = CityActivites::ActivitiesByCity($city->city_id, $city->name, $city->image);
					$base64 = null;

					/* Resize image for Banner */
					$image_s = ImageJob::Resize($city->image,1140, 470);

					return view('navigator', array(
						'city' => array('id' => $data['city_id'], 'name' => $data['city_name'], 'image' => $data['city_image'], 'image_s' => $image_s),
						'activities' => $data['activities'],
						'search' => 1,
					));

					break;
				case 'activity':
					$activity_name = $request->input(('Activity'));
					$activity = $navigator->getActivityByName($activity_name);

					$data = City::CitiesByActivity($activity->activity_id, $activity->name, $activity->image);
					$base64 = null;

					/* Resize image for Banner */
					$image_s = ImageJob::Resize($activity->image,1140, 470);

					return view('navigator', array(
						'activity' => array('id' => $data['activity_id'], 'name' => $data['activity_name'], 'image' => $data['activity_image'], 'image_s' => $image_s),
						'cities' => $data['cities'],
						'search' => 1,
					));

					break;
			}
		} else {
			return view('navigator', array(
				'cities' => $navigator->cities,
				'activities' => $navigator->activities,
				'search' => 0,
			));
		}
	}
}