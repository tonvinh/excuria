<?php

namespace App;

use DB;

class Filter
{
	public $data = null;
    public function __construct($name,$parameters = [])
	{
		//DB::enableQueryLog();
		$dataFilter = null;
		switch ($name) {
			case 'classAvailable-Provider': {
				$searchData = DB::table('course')->leftjoin('curriculum', 'course.curriculum_id', 'curriculum.curriculum_id')
					->leftjoin('program', 'program.program_id', 'curriculum.program_id')
					->join('provider', 'program.provider_id', 'provider.provider_id')
					->leftjoin('schedule', 'course.course_id', 'schedule.course_id')->distinct()
					->orderByRaw('program.name ASC')
					->pluck('program.provider_id');

				$dataFilter = [];

				foreach ($searchData as $provider) {
					$dataFilter[] = $provider;
				}

				$this->data = $dataFilter;
				break;
			}
			case 'classAvailable-Instructor': {
				$searchData = DB::table('user')
					->join('schedule', 'user.user_id', 'schedule.instructor_id')->distinct()->pluck('user.user_id');

				$dataFilter = [];

				foreach ($searchData as $instructor) {
					$dataFilter[] = $instructor;
				}

				$this->data = $dataFilter;
				break;
			}
			case 'country': {
				$dataFilter = DB::table('country')
					->where('status',1)
					->select('country_id','name')
					->orderBy('country.name')->get();
				$this->data = $dataFilter;
				break;
			}
			case 'city': {
				$items = (isset($parameters['country']) && $parameters['country'] != null && $parameters['country'] !== 'undefined' && $parameters['country'] !== 'any') ? explode(",",$parameters['country']) : null;

				if (sizeof($items) > 0){
					$strCondition = [];
					foreach ($items as $item) {
						$strCondition[] = "lower(country.name) like " . DB::Raw("'%" . strtolower($item) . "%'");
					}
				}

				$dataFilter = DB::table('city')
					->join('country', 'country.country_id', 'city.country_id')
					->whereRaw(((isset($items) && $items != null) ? join(" OR ", $strCondition) . " and " : '') . "city.status=1")
					->select('city_id', 'city.name')->orderBy('city.name')->get();
				$this->data = $dataFilter;

				break;
			}
			case 'location': {
				$items = (isset($parameters['city']) && $parameters['city'] != null && $parameters['city'] !== 'undefined' && $parameters['city'] !== 'any') ? explode(",",$parameters['city']) : null;

				if (sizeof($items) > 0){
					$strCondition = [];
					foreach ($items as $item) {
						$strCondition[] = "lower(city.name) like " . DB::Raw("'%" . strtolower($item) . "%'");
					}
				}

				$dataFilter = DB::table('location')
					->join('city', 'location.city_id', 'city.city_id')
					->whereRaw(((isset($items) && $items != null) ? join(" OR ", $strCondition) . " and " : '') . "location.status=1")
					->select('location_id', 'location.name')->orderBy('location.name')->get();
				$this->data = $dataFilter;

				break;
			}
			case 'activityType': {
				$dataFilter = DB::table('activity_type')
					->where('status',1)
					->select('activity_type_id','name')->orderBy('activity_type.name')->get();

				$this->data = $dataFilter;

				break;
			}
			case 'activityClassification': {
				$items = (isset($parameters['activityType']) && $parameters['activityType'] != null && $parameters['activityType'] !== 'undefined' && $parameters['activityType'] !== 'any') ? explode(",",$parameters['activityType']) : null;

				if (sizeof($items) > 0){
					$strCondition = [];
					foreach ($items as $item) {
						$strCondition[] = "lower(activity_type.name) like " . DB::Raw("'%" . strtolower($item) . "%'");
					}
				}

				$dataFilter = DB::table('activity_classification')
					->join('activity_type','activity_classification.activity_type_id','activity_type.activity_type_id')
					->whereRaw(
						((isset($items) && $items != null) ? join(" OR ", $strCondition) . " and " : "") .
						"activity_classification.status=1")
					->select('activity_classification_id','activity_classification.name')->distinct()->orderBy('activity_classification.name')->get();

				$this->data = $dataFilter;

				break;
			}
			case 'activity': {
				$items = (isset($parameters['activityClassification']) && $parameters['activityClassification'] != null && $parameters['activityClassification'] !== 'undefined' && $parameters['activityClassification'] !== 'any') ? explode(",",$parameters['activityClassification']) : null;

				if (sizeof($items) > 0){
					$strCondition = [];
					foreach ($items as $item) {
						$strCondition[] = "lower(activity_classification.name) like " . DB::Raw("'%" . strtolower($item) . "%'");
					}
				}

				$dataFilter = DB::table('activity')
					->join('activity_classification','activity.activity_classification_id','activity_classification.activity_classification_id')
					->whereRaw(
						((isset($items) && $items != null) ? join(" OR ", $strCondition) . " and " : "") .
						"activity.status=1")
					->select('activity_id','activity.name')->distinct()->orderBy('activity.name')->get();
				$this->data = $dataFilter;
				break;
			}
			case 'eventType': {
				$dataFilter = DB::table('event_type')
					->where('status',1)
					->select('event_type_id','name')->get();
				$this->data = $dataFilter;
				break;
			}
			case 'facility': {
				$dataFilter = null;

				$dataFilter = DB::table('facility')->join('entity','facility.entity_id','entity.entity_id')->join('country','facility.country_id','country.country_id')
					->distinct()
					->select(/*'facility_id',*/'facility.name')->orderBy('facility.name')->get();

				$this->data = $dataFilter;
				break;
			}
			case 'campus': {
				$items = (isset($parameters['facility']) && $parameters['facility'] != null && $parameters['facility'] !== 'undefined' && $parameters['facility'] !== 'any') ? explode(",",$parameters['facility']) : null;

				if (sizeof($items) > 0){
					$strCondition = [];
					foreach ($items as $item) {
						$strCondition[] = "lower(facility.name) like " . DB::Raw("'%" . strtolower($item) . "%'");
					}
				}

				$dataFilter = DB::table('campus')->join('facility','campus.facility_id','facility.facility_id')
					->whereRaw(((isset($items) && $items != null) ? join(" OR ", $strCondition) . " and " : '') . "campus.status=1")
					->select(/*'campus_id',*/'campus.name')->distinct()->orderBy('campus.name')->get();
				$this->data = $dataFilter;

				break;
			}
			case 'arena': {

				$dataFilter = DB::table('arena')
					->whereRaw("arena.status=1")
					->select(/*'arena_id',*/'name')->orderBy('arena.name')->distinct()->get();
				$this->data = $dataFilter;
				break;
			}
			case 'provider': {
				$dataFilter = DB::table('provider')->join('entity','provider.entity_id','entity.entity_id')
					->whereRaw('entity.is_provider = 1 and provider.status = 1')
					->select(/*'provider.provider_id',*/DB::raw('provider.name'), DB::raw('entity.name entity_name'))
					->distinct()->orderBy('provider.name')->get();
				$this->data = $dataFilter;
				break;
			}
			case 'instructor': {
				$dataFilter = DB::table('user')
					->whereRaw('is_instructor = 1 and status = 2')
					->select('user_id', DB::raw("trim(first_name) as first_name"),DB::raw("trim(middle_name) as middle_name"),DB::raw("trim(last_name) as last_name"))
					->distinct()->orderBy('user.first_name')->get();
				$this->data = $dataFilter;
				break;
			}
			case 'program': {
				$dataFilter = DB::table('program')
					->whereRaw('status = 0')
					->select(/*'program_id',*/'name')->distinct()->orderBy('program.name')->get();
				$this->data = $dataFilter;
				break;
			}
			case 'gender': {
				$dataFilter = DB::table('audience_gender')
					->whereRaw('status = 1')
					->select('audience_gender_id','name')->get();
				$this->data = $dataFilter;
				break;
			}
			case 'generation': {
				$dataFilter = DB::table('audience_generation')
					->whereRaw('status = 1')
					->select('audience_generation_id','name')->orderBy('audience_generation.name')->get();
				$this->data = $dataFilter;
				break;
			}
			case 'day': {
				$dataFilter = DB::table('day_of_week')
					->whereRaw('status = 1')
					->select('day_of_week_id','name')->get();
				$this->data = $dataFilter;
				break;
			}
			case 'timeStart': {
				$dataFilter = DB::table('course')
					->whereRaw('status = 1')
					->distinct()
					->select('time_start')->get();
				$this->data = $dataFilter;
				break;
			}
			case 'timeEnd': {
				$dataFilter = DB::table('course')
					->whereRaw('status = 1')
					->distinct()
					->select('time_end')->get();
				$this->data = $dataFilter;
				break;
			}
			case 'ageFrom': {
				$dataFilter = DB::table('course')
					/*->whereRaw('status = 1')*/
					->distinct()
					->select('age_range_top')
					->orderBy('age_range_top')
					->get();
				$this->data = $dataFilter;
				break;
			}
			case 'ageTo': {
				$dataFilter = DB::table('course')
					/*->whereRaw('status = 1')*/
					->distinct()
					->orderBy('age_range_bottom')
					->select('age_range_bottom')->get();
				$this->data = $dataFilter;
				break;
			}
			case 'autosuggest':
			{
				$dataSettingSuggests = DB::table('search_box')
					->select('table_name','column_name')
					->where('status','1')
					->get();

				$suggest = [];

				$keyword = (isset($parameters['keyword']) && $parameters['keyword'] != null) ? $parameters['keyword'] : null;

				if ($keyword === null)
				{
					$this->data = null;
					return $this->data;
				}

				/* Revice data from table.filed set on search_box */
				foreach ($dataSettingSuggests as $setting) {

					$data = DB::table($setting->table_name)
						->where($setting->table_name . '.' . $setting->column_name , 'like' , "%". $keyword . "%")
							->select($setting->table_name . '.' . $setting->column_name)->get();

					foreach($data as $item)
					{
						$filed = $setting->column_name;
						$suggest[] = $item->$filed;
					}
				}
				$suggest = array_unique($suggest);

				/* Remake array after remove duplicate */
				$returnData = [];
				foreach($suggest as $data)
				{
					$returnData[] = $data;
				}

				$this->data = $returnData;

				break;
			}
		}
	}
}
