<?php

namespace App;

use DB;

class Filter
{
	public $data = null;
    public function __construct($name,$parameters = [])
	{
		$dataFilter = null;

		switch ($name) {
			case 'country': {
				$dataFilter = DB::table('country')
					->where('status',1)
					->select('country_id','name')->get();
				$this->data = $dataFilter;
				break;
			}
			case 'city': {
				$parameter = (isset($parameters['country']) && $parameters['country'] != null) ? $parameters['country'] : null;

				$dataFilter = DB::table('city')
					->whereRaw(((isset($parameter) && $parameter != null) ? "city.country_id = " . $parameter . "' and " : "") . "status=1")
					->select('city_id', 'name')->get();
				$this->data = $dataFilter;
				break;
			}
			case 'location': {
				$parameter = (isset($parameters['city']) && $parameters['city'] != null) ? $parameters['city'] : null;

				$dataFilter = DB::table('location')
					->whereRaw(((isset($parameter) && $$parameter != null) ? "location.city_id = " . $parameter . "' and " : "") . "status=1")
					->select('location_id', 'name')->get();
				$this->data = $dataFilter;

				break;
			}
			case 'activityType': {
				$dataFilter = DB::table('activity_type')
					->where('status',1)
					->select('activity_type_id','name')->get();
				$this->data = $dataFilter;
				break;
			}
			case 'activityClassification': {
				$parameter = (isset($parameters['activityType']) && $parameters['activityType'] != null) ? $parameters['activityType'] : null;

				$dataFilter = DB::table('activity_classification')->join('activity_type','activity_classification.activity_type_id','activity_type.activity_type_id')
					->whereRaw(
						((isset($parameter) && $parameter != null) ? 'lower(activity_type.name) like ' . DB::Raw("'%" . strtolower($parameter) . "%' and ") : "") .
						"activity_classification.status=1")
					->select('activity_classification_id','activity_classification.name')->distinct()->get();

				$this->data = $dataFilter;
				break;
			}
			case 'activity': {
				$parameter = (isset($parameters['activityClassification']) && $parameters['activityClassification'] != null) ? $parameters['activityClassification'] : null;

				$dataFilter = DB::table('activity')->join('activity_classification','activity_classification.activity_classification_id','activity_classification.activity_classification_id')
					->whereRaw(
						((isset($parameter) && $parameter != null) ? 'lower(activity_classification.name) like ' . DB::Raw("'%" . strtolower($parameter) . "%' and ") : "") .
						"activity.status=1")
					->select('activity_id','activity.name')->distinct()->get();

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
				$parameter = (isset($parameters['country']) && $parameters['country'] != null) ? $parameters['country'] : null;

				$dataFilter = DB::table('facility')->join('entity','facility.entity_id','entity.entity_id')->join('country','facility.country_id','country.country_id')
					->whereRaw(
						(isset($parameter) && $parameter != null ? "country.name = " . DB::Raw("'%" . strtolower($parameter) . "%' and ") : "") .
						"facility.status=1"
					)->distinct()
					->select('facility_id','facility.name')->get();

				$this->data = $dataFilter;
				break;
			}
			case 'campus': {
				$dataFilter = DB::table('campus')
					->where('status',1)
					->select('campus_id','name')->get();
				$this->data = $dataFilter;

				break;
			}
			case 'arena': {
				$dataFilter = DB::table('arena')
					->where('status',1)
					->select('arena_id','name')->get();
				$this->data = $dataFilter;
				break;
			}
			case 'provider': {
				$dataFilter = DB::table('provider')->join('entity','provider.entity_id','entity.entity_id')
					->whereRaw('entity.is_provider = 1 and provider.status = 1')
					->select('provider.provider_id',DB::raw('provider.name provider_name'), DB::raw('entity.name entity_name'))->get();
				$this->data = $dataFilter;
				break;
			}
			case 'instructor': {
				$dataFilter = DB::table('user')
					->whereRaw('is_instructor = 1 and status = 2')
					->select('user_id', DB::raw("trim(first_name) as first_name"),DB::raw("trim(middle_name) as middle_name"),DB::raw("trim(last_name) as last_name"))->get();
				$this->data = $dataFilter;
				break;
			}
			case 'program': {
				$dataFilter = DB::table('program')
					->whereRaw('status = 0')
					->select('program_id','name')->get();
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
					->select('audience_generation_id','name')->get();
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
