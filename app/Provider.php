<?php

namespace App;

use DB;
use Memcached;

class Provider extends BaseSearch
{

	public function providerHasClass()
	{
		$searchData = DB::table('course')->leftjoin('curriculum', 'course.curriculum_id', 'curriculum.curriculum_id')
			->leftjoin('program', 'program.program_id', 'curriculum.program_id')
			->join('provider', 'program.provider_id', 'provider.provider_id')
			->leftjoin('schedule', 'course.course_id', 'schedule.course_id')->distinct()->pluck('program.provider_id');

		$providers = [];

		foreach ($searchData as $provider) {
			$providers[] = $provider;
		}

		return $providers;
	}

	public function search($conditions)
	{

		// TODO: Implement search() method.
		$limit = (isset($conditions['limit']) && $conditions['limit'] !== null ? $conditions['limit'] : 10);
		$result = array();
		$alphabets = [];

		DB::enableQueryLog();

		$mem = new Memcached();
		$mem->addServer("memcache.excuri.com", 11211);

		/* Get Default Logo Link on setting_object table */
		$logoMem = $mem->get('logo_provider');

		if ($logoMem)
		{
			$default_logo = $logoMem;
		}
		else
		{
			$default_logo = $this->getDefaultLogo();
			$mem->set('logo_provider', $default_logo);
		}

		if (isset($conditions['alphabet']) && $conditions['alphabet'] !== null && sizeof($conditions['alphabet']) > 0) {
			foreach ($conditions['alphabet'] as $item) {
				$alphabets[] = "lower(provider.name) like " . DB::Raw("'" . strtolower($item) . "%'");
			}
		}

		$extraGroup = (isset($conditions['sort']) && $conditions['sort'] !== null ? $conditions['sort'] : '' );
		$extraGroup = str_replace(' ASC', '',$extraGroup);
		$extraGroup = str_replace(' DESC', '',$extraGroup);

		$searchData = DB::table('provider')
			->select('provider.provider_id', DB::raw('provider.name provider_name'), DB::raw('entity.logo entity_logo'))
			->whereRaw(
				(isset($conditions['class_available']) && sizeof($conditions['class_available']) > 0 ? "(" . implode(" OR ", $conditions['class_available']) . ") AND " : '') .
				(isset($conditions['country_id']) && $conditions['country_id'] !== null ? "entity.country_id in (" . implode(",", $conditions['country_id']) . ") AND " : '') .
				(isset($conditions['city_id']) && $conditions['city_id'] !== null ? "entity.city_id in (" . implode(",", $conditions['city_id']) . ") AND " : '') .
				(isset($conditions['location_id']) && $conditions['location_id'] !== null ? "location.location_id in (" . implode(",", $conditions['location_id']) . ") AND " : '') .

				(isset($conditions['activity_id']) && $conditions['activity_id'] !== null ? "program.activity_id in (" . implode(",", $conditions['activity_id']) . ") AND " : '') .
				(isset($conditions['activity_classification_id']) && $conditions['activity_classification_id'] !== null ? "activity.activity_classification_id in (" . implode(",", $conditions['activity_classification_id']) . ") AND " : '') .
				(isset($conditions['activity_type_id']) && $conditions['activity_type_id'] !== null ? "activity_classification.activity_type_id in (" . implode(",", $conditions['activity_type_id']) . ") AND " : '') .

				(isset($conditions['keyword']) && sizeof($conditions['keyword']) > 0 ? "(" . implode(" OR ", $conditions['keyword']) . ") AND " : '') .

				(isset($alphabets) && sizeof($alphabets) > 0 ? "(" . join(" OR ", $alphabets) . ") AND " : '') .
				('program.program_id is not null and entity.is_provider = 1')
			)
			->join('entity', 'provider.entity_id', 'entity.entity_id')
			->join('location', 'entity.city_id', 'location.city_id')
			->leftjoin('program', 'provider.provider_id', 'program.provider_id')
			->leftjoin('activity', 'program.activity_id', 'activity.activity_id')
			->leftjoin('activity_classification', 'activity_classification.activity_classification_id', 'activity.activity_classification_id')

			->leftjoin('curriculum','program.program_id','curriculum.program_id')
			->leftjoin('course', 'curriculum.curriculum_id', 'course.curriculum_id')
			->leftjoin('schedule', 'course.course_id', 'schedule.course_id')
			->leftjoin('user', 'schedule.instructor_id', 'user.user_id')

			->groupBy(
				DB::raw('provider.provider_id, provider.name, entity.logo'
					// . (isset($conditions['sort']) && $conditions['sort'] !== null ? ", " . $extraGroup : '' )
				)
			)
			->orderByRaw(( isset($conditions['sort']) && $conditions['sort'] !== null ? $conditions['sort'] : 'course.course_id DESC'))
			->limit($limit)
			->offset(isset($conditions['page']) && $conditions['page'] !== null ? ($conditions['page'] - 1) * $limit : 0)
			->get();

		$searchAll = DB::table('provider')->select('provider.provider_id', DB::raw('provider.name provider_name'), DB::raw('entity.logo entity_logo'))
			->whereRaw(
				(isset($conditions['class_available']) && sizeof($conditions['class_available']) > 0 ? "(" . implode(" OR ", $conditions['class_available']) . ") AND " : '') .

				(isset($conditions['country_id']) && $conditions['country_id'] !== null ? "entity.country_id in (" . implode(",", $conditions['country_id']) . ") AND " : '') .
				(isset($conditions['city_id']) && $conditions['city_id'] !== null ? "entity.city_id in (" . implode(",", $conditions['city_id']) . ") AND " : '') .
				(isset($conditions['location_id']) && $conditions['location_id'] !== null ? "location.location_id in (" . implode(",", $conditions['location_id']) . ") AND " : '') .

				(isset($conditions['activity_id']) && $conditions['activity_id'] !== null ? "program.activity_id in (" . implode(",", $conditions['activity_id']) . ") AND " : '') .
				(isset($conditions['activity_classification_id']) && $conditions['activity_classification_id'] !== null ? "activity.activity_classification_id in (" . implode(",", $conditions['activity_classification_id']) . ") AND " : '') .
				(isset($conditions['activity_type_id']) && $conditions['activity_type_id'] !== null ? "activity_classification.activity_type_id in (" . implode(",", $conditions['activity_type_id']) . ") AND " : '') .

				(isset($conditions['keyword']) && sizeof($conditions['keyword']) > 0 ? "(" . implode(" OR ", $conditions['keyword']) . ") AND " : '') .

				(isset($alphabets) && sizeof($alphabets) > 0 ? "(" . join(" OR ", $alphabets) . ") AND " : '') .
				('program.program_id is not null and entity.is_provider = 1')
			)
			->join('entity', 'provider.entity_id', 'entity.entity_id')
			->join('location', 'entity.city_id', 'location.city_id')
			->leftjoin('program', 'provider.provider_id', 'program.provider_id')
			->leftjoin('activity', 'program.activity_id', 'activity.activity_id')
			->leftjoin('activity_classification', 'activity_classification.activity_classification_id', 'activity.activity_classification_id')

			->leftjoin('curriculum','program.program_id','curriculum.program_id')
			->leftjoin('course', 'curriculum.curriculum_id', 'course.curriculum_id')
			->leftjoin('schedule', 'course.course_id', 'schedule.course_id')
			->leftjoin('user', 'schedule.instructor_id', 'user.user_id')
			->groupBy(
				DB::raw('provider.provider_id, provider.name, entity.logo'
					// . (isset($conditions['sort']) && $conditions['sort'] !== null ? ", " . $extraGroup : '' )
				)
			)
			->orderByRaw(( isset($conditions['sort']) && $conditions['sort'] !== null ? $conditions['sort'] : 'course.course_id DESC'))
			->get();

		foreach ($searchData as $data) {
			/*echo('<br>' . $data->program_id . '|' . $data->program_name . '|'.  $data->activity_id . '|' . $data->provider_name . '|' . $data->location_name);*/
			//echo('<br>' . $data->provider_id . '|' . $data->provider_name . '|' . $data->location_name);
			//echo('<br>' . $data->provider_id . "~" . $data->provider_name . '#');

			$item = ['provider_id' => $data->provider_id,
				'entity_logo' => ($data->entity_logo ? $data->entity_logo : $default_logo),
				'provider_name' => $data->provider_name,
			];

			$activities = DB::table('program')
				->select('activity.logo')
				->join('activity', 'program.activity_id', 'activity.activity_id')
				->leftjoin('activity_classification', 'activity.activity_classification_id', 'activity_classification.activity_classification_id')
				->where('program.provider_id', $data->provider_id)
				->whereRaw(
					(isset($conditions['activity_id']) && $conditions['activity_id'] !== null ? "program.activity_id in (" . implode(",", $conditions['activity_id']) . ") AND " : '') .
					//(isset($conditions['activity_classification_id']) && $conditions['activity_classification_id'] !== null ? "activity.activity_classification_id = " . $conditions['activity_classification_id'] . " AND " : '') .
					(isset($conditions['activity_classification_id']) && $conditions['activity_classification_id'] !== null ? "activity.activity_classification_id in (" . implode(",", $conditions['activity_classification_id']) . ") AND " : '') .
					//(isset($conditions['activity_type_id']) && $conditions['activity_type_id'] !== null ? "activity_classification.activity_type_id = " . $conditions['activity_type_id'] . " AND " : '') .
					(isset($conditions['activity_type_id']) && $conditions['activity_type_id'] !== null ? "activity_classification.activity_type_id in (" . implode(",", $conditions['activity_type_id']) . ") AND " : '') .
					('program.status = 0')
				)
				->distinct()
				//->groupBy('activity.logo')
				->get();
			$searchActivities = [];
			foreach ($activities as $activity) {
				//echo('<img src="' . $activity->logo . '" style="width:2%;" alt="' . $activity->program_name . '" title="' . $activity->program_name . '">');
				$searchActivity = [
					'logo' => $activity->logo,
					//'name' => $activity->program_name,
				];
				array_push($searchActivities, $searchActivity);
			}
			array_push($result, array('item' => $item, 'activity' => $searchActivities));
		}


		$final = array('totalPage' => ceil(sizeof($searchAll) / $limit), 'total' => ( isset($searchAll) ? sizeof($searchAll) : 0), 'result' => $result, 'debug' => DB::getQueryLog()[0]['query']);
		return $final;
	}

	public function prepare($parameters = [])
	{
		// TODO: Implement prepare() method.
		// echo('parameter support: keyword, city, country, activity, activityClassification, activityType, page');
		$conditions = array();

		foreach ($parameters as $name => $value) {
			switch ($name) {
				case 'sortBy': {
					$strCondition = "";
					if ($value === 'provider') {
						$strCondition = "provider.name ASC";
					} elseif ($value === 'class') {
						$strCondition = "course.course_id DESC";
					}
					$conditions['sort'] = $strCondition;
					break;
				}
				case 'classAvailable': {
					$items = explode(",", $value);
					$hasClasses = $this->providerHasClass();
					$strCondition = [];

					if (sizeof($items) > 0) {

						foreach ($items as $item) {

							if ($item === 'yes' && $item !== 'any') {
								$strCondition[] = "(provider.provider_id) in (" . implode(",", $hasClasses) . ")";
							} else if ($item === 'no' && $item !== 'any') {
								$strCondition[] = "(provider.provider_id) not in (" . implode(",", $hasClasses) . ")";
							} else {
								//$strCondition[] = "(provider.provider_id) not in (" . implode(",", $hasClasses) . ")";
							}
						}
					} else {
						if ($value === 'yes' && $value !== 'any') {
							$strCondition[] = "(provider.provider_id) in (" . join($value, ",") . ")";
						} else if ($value === 'no' && $value !== 'any') {
							$strCondition[] = "(provider.provider_id) not in (" . join($value, ",") . ")";
						}
					}

					$data = $strCondition;

					$conditions['class_available'] = $data;
					break;
				}
				case 'country': {

					$items = explode(",", $value);
					if (sizeof($items) > 0) {
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "lower(name) like " . DB::Raw("'%" . strtolower($item) . "%'");
						}
						$countries = DB::table('country')->whereRaw(join(" OR ", $strCondition))->pluck('country_id');
					} else {
						$countries = DB::table('country')->whereRaw('lower(name) like ' . DB::Raw("'%" . strtolower($value) . "%'"))->pluck('country_id');
					}

					$data = null;

					foreach ($countries as $country) {
						$data[] = $country;
					}
					$conditions['country_id'] = $data;
					break;
				}
				case 'city': {

					$items = explode(",", $value);
					if (sizeof($items) > 0) {
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "lower(name) like " . DB::Raw("'%" . strtolower($item) . "%'");
						}
						$cities = DB::table('city')->whereRaw(join(" OR ", $strCondition))->pluck('city_id');
					} else {
						$cities = DB::table('city')->whereRaw('lower(name) like ' . DB::Raw("'%" . strtolower($value) . "%'"))->pluck('city_id');
					}

					$data = null;

					foreach ($cities as $city) {
						$data[] = $city;
					}

					$conditions['city_id'] = $data;
					break;
				}
				case 'location' : {

					$items = explode(",", $value);
					if (sizeof($items) > 0) {
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "lower(name) like " . DB::Raw("'%" . strtolower($item) . "%'");
						}
						$locations = DB::table('location')->whereRaw(join(" OR ", $strCondition))->pluck('location_id');
					} else {
						$locations = DB::table('location')->whereRaw('lower(name) like ' . DB::Raw("'%" . strtolower($value) . "%'"))->pluck('location_id');
					}

					$data = null;

					foreach ($locations as $location) {
						$data[] = $location;
					}

					$conditions['location_id'] = $data;
					break;
				}
				case 'activityType': {

					$items = explode(",", $value);
					if (sizeof($items) > 0) {
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "lower(name) like " . DB::Raw("'%" . strtolower($item) . "%'");
						}
						$activitiesType = DB::table('activity_type')->whereRaw(join(" OR ", $strCondition))->pluck('activity_type_id');
					} else {
						$activitiesType = DB::table('activity_type')->whereRaw('lower(name) like ' . DB::Raw("'%" . strtolower($value) . "%'"))->pluck('activity_type_id');
					}

					$data = null;

					foreach ($activitiesType as $activityType) {
						$data[] = $activityType;
					}

					$conditions['activity_type_id'] = $data;
					break;
				}
				case 'activityClassification': {
					$items = explode(",", $value);
					if (sizeof($items) > 0) {
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "lower(name) like " . DB::Raw("'%" . strtolower($item) . "%'");
						}
						$activitiesClassification = DB::table('activity_classification')->whereRaw(join(" OR ", $strCondition))->pluck('activity_classification_id');
					} else {
						$activitiesClassification = DB::table('activity_classification')->whereRaw('lower(name) like ' . DB::Raw("'%" . strtolower($value) . "%'"))->pluck('activity_classification_id');
					}

					$data = null;

					foreach ($activitiesClassification as $activityClassification) {
						$data[] = $activityClassification;
					}

					$conditions['activity_classification_id'] = $data;
					break;
				}
				case 'activity': {
					$items = explode(",", $value);
					if (sizeof($items) > 0) {
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "lower(name) like " . DB::Raw("'%" . strtolower($item) . "%'");
						}
						$activities = DB::table('activity')->whereRaw(join(" OR ", $strCondition))->pluck('activity_id');
					} else {
						$activities = DB::table('activity')->whereRaw('lower(name) like ' . DB::Raw("'%" . strtolower($value) . "%'"))->pluck('activity_id');
					}

					$data = null;
					foreach ($activities as $activity) {
						$data[] = $activity;
					}

					$conditions['activity_id'] = $data;
					break;
				}
				case 'alphabet': {
					$conditions['alphabet'] = explode(",", $value);
					break;
				}
				case 'searchKeywordBy': {

					$searches =[];
					$settingKeyWord = $this->getSetting();

					if (isset($parameters['searchKeywordBy']) && $parameters['searchKeywordBy'] !== null && isset($parameters['keyword']) && $parameters['keyword'] !== null) {

						if ($parameters['searchKeywordBy'] === 'match') {
							/* (field1 like %yo% and field1 like %go%) or (field2 like %yo% and field2 like %go%) */
							$keywords = explode(" ", $parameters['keyword']);

							foreach ($settingKeyWord as $setting) {

								$match = [];
								foreach ($keywords as $keyword) {
									$match[] = $setting->table_name . '.' . $setting->column_name . " like '%" . $keyword . "%'";
								}
								$searches[] = '( ' . implode(" AND " , $match) . ')';
							}
						} elseif ($parameters['searchKeywordBy'] === 'exact') {
							/* (field1 like %yo go% or field2 like %yo go%) */
							foreach ($settingKeyWord as $setting) {
								$searches[] = $setting->table_name . '.' . $setting->column_name . " like '%" . $parameters['keyword'] . "%'";
							}

						} elseif ($parameters['searchKeywordBy'] === 'contain') {
							/* (field1 like %yo% or field1 like %go% or field2 like %yo% or field2 like %go%) */
							$keywords = explode(" ", $parameters['keyword']);

							foreach ($keywords as $keyword) {
								foreach ($settingKeyWord as $setting) {
									$searches[] = $setting->table_name . '.' . $setting->column_name . " like '%" . $keyword . "%'";
								}
							}
						}

					}

					$conditions['searchKeywordBy'] = $parameters['searchKeywordBy'];
					$conditions['keyword'] = $searches;
					break;
				}
				case 'page': {
					$conditions['page'] = $value;
					break;
				}
				case 'perPage': {
					$conditions['limit'] = $value;
					break;
				}
			}
		}

		return $conditions;

	}

	function getSetting(){
		$dataKeyWord = DB::table('search_box')
			->select('table_name','column_name')
			->where('status','1')
			->get();
		return $dataKeyWord;
	}

	function getDefaultLogo()
	{
		$dataKeyWord = DB::table('setting_object')
			//->select('setting_object_id','setting_name','setting_value')
			->select('setting_value')
			->where('setting_object_id','1')
			->first();
		return $dataKeyWord->setting_value;
	}
}