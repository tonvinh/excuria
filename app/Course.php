<?php

namespace App;

use DB;
use Memcached;

class Course extends BaseSearch
{
	public function search($conditions)
	{
		// TODO: Implement search() method.
		$limit = (isset($conditions['limit']) && $conditions['limit'] !== null ? $conditions['limit'] : 10);
		$result = array();
		$alphabets = [];

		DB::enableQueryLog();

		$mem = new Memcached();
		$mem->addServer("127.0.0.1", 11211);

		/* Get Default Logo Link on setting_object table */
		$logoMem = $mem->get('logo_course');

		if ($logoMem)
		{
			$default_logo = $logoMem;
		}
		else
		{
			$default_logo = $this->getDefaultLogo();
			$mem->set('logo_course', $default_logo);
		}

		if (isset($conditions['alphabet']) && $conditions['alphabet'] !== null && sizeof($conditions['alphabet']) > 0) {
			foreach ($conditions['alphabet'] as $item) {
				$alphabets[] = "lower(entity.name) like " . DB::Raw("'" . strtolower($item) . "%'");
				$alphabets[] = "lower(curriculum.name) like " . DB::Raw("'" . strtolower($item) . "%'");
			}
		}

		$extraGroup = (isset($conditions['sort']) && $conditions['sort'] !== null ? $conditions['sort'] : '' );
		$extraGroup = str_replace(' ASC', '',$extraGroup);
		$extraGroup = str_replace(' DESC', '',$extraGroup);

		$searchData = DB::table('course')
			->select('curriculum.curriculum_id','course.course_id', DB::raw('entity.logo entity_logo'), 'course.day_of_week',
				DB::raw('entity.name entity_name, curriculum.name curriculum_name'), 'course.time_start', 'course.time_end')
			->whereRaw(
				(isset($conditions['is_free_trial'])&& sizeof($conditions['is_free_trial']) > 0 ? "course.is_free_trial in (" . implode(",", $conditions['is_free_trial']) . ") AND " : '') .

				(isset($conditions['country_id']) && $conditions['country_id'] !== null ? "campus.country_id in (" . implode(",",$conditions['country_id']) . ") AND " : '') .
				(isset($conditions['city_id']) && $conditions['city_id'] !== null ? "campus.city_id in (" . implode(",",$conditions['city_id']) . ") AND " : '') .
				(isset($conditions['location_id']) && $conditions['location_id'] !== null ? "campus.location_id in (" . implode(",",$conditions['location_id']) . ") AND " : '') .

				(isset($conditions['activity_id']) && $conditions['activity_id'] !== null ? "program.activity_id in (" . implode(",",$conditions['activity_id']) . ") AND " : '') .
				(isset($conditions['activity_classification_id']) && $conditions['activity_classification_id'] !== null ? "activity.activity_classification_id in (" . implode(",",$conditions['activity_classification_id']) . ") AND " : '') .
				(isset($conditions['activity_type_id']) && $conditions['activity_type_id'] !== null ? "activity_classification.activity_type_id in (" . implode(",",$conditions['activity_type_id']) . ") AND " : '') .

				(isset($conditions['event_type_id']) && $conditions['event_type_id'] !== null ? "program.event_type_id in (" . implode(",",$conditions['event_type_id']) . ") AND " : '') .
				(isset($conditions['facility_id']) && $conditions['facility_id'] !== null ? "campus.facility_id in (" . implode(",",$conditions['facility_id']) . ") AND " : '') .
				(isset($conditions['campus_id']) && $conditions['campus_id'] !== null ? "campus_arena.campus_id in (" . implode(",",$conditions['campus_id']) . ") AND " : '') .
				(isset($conditions['program_id']) && $conditions['program_id'] !== null ? "curriculum.program_id in (" . implode(",",$conditions['program_id']) . ") AND " : '') .
				(isset($conditions['arena_id']) && $conditions['arena_id'] !== null ? "campus_arena.arena_id in (" . implode(",",$conditions['arena_id']) . ") AND " : '') .
				(isset($conditions['provider_id']) && $conditions['provider_id'] !== null ? "program.provider_id in (" . implode(",",$conditions['provider_id']) . ") AND " : '') .
				(isset($conditions['user_id']) && $conditions['user_id'] !== null ? "schedule.instructor_id in (" . implode(",",$conditions['user_id']) . ") AND " : '') .
				(isset($conditions['day_of_week']) && $conditions['day_of_week'] !== null ? "course.day_of_week in (" . implode(",",$conditions['day_of_week']) . ") AND " : '') .

				(isset($conditions['time_start']) && $conditions['time_start'] !== null ? "time(course.time_start) = time('" . $conditions['time_start'] . "') AND " : '') .
				(isset($conditions['time_end']) && $conditions['time_end'] !== null ? "time(course.time_end) = time('" . $conditions['time_end'] . "') AND " : '') .

				(isset($conditions['audience_gender_id']) && $conditions['audience_gender_id'] !== null ? "course.audience_gender_id in (" . implode(",",$conditions['audience_gender_id']) . ") AND " : '') .
				(isset($conditions['audience_generation_id']) && $conditions['audience_generation_id'] !== null ? "course.audience_generation_id in (" . implode(",",$conditions['audience_generation_id']) . ") AND " : '') .

				(isset($conditions['age_range_top']) && $conditions['age_range_top'] !== null ? "course.age_range_top >= " . $conditions['age_range_top'] . " AND " : '') .
				(isset($conditions['age_range_bottom']) && $conditions['age_range_bottom'] !== null ? "course.age_range_bottom <= " . $conditions['age_range_bottom'] . " AND " : '') .

				(isset($conditions['keyword']) && sizeof($conditions['keyword']) > 0 ? "(" . implode(" OR ", $conditions['keyword']) . ") AND " : '') .
				(isset($alphabets) && sizeof($alphabets) > 0 ? "(" . join(" OR ", $alphabets) . ") AND " : '') .
				('course.status = 0')
			)
			->join('campus_arena', function($join){
				$join->on('course.campus_arena_id','campus_arena.campus_arena_id');
				//$join->on('course.arena_id', 'campus_arena.arena_id');
			})
			->leftjoin('campus', 'campus_arena.campus_id', 'campus.campus_id')
			->leftjoin('location', 'campus.location_id', 'location.location_id')

			->leftjoin('facility', function ($join) {
				$join->on('campus.facility_id','facility.facility_id');
				$join->on('campus.country_id','facility.country_id');
			})

			->leftjoin('curriculum', 'course.curriculum_id', 'curriculum.curriculum_id')

			->leftjoin('program','program.program_id','curriculum.program_id')
			->leftjoin('provider', 'program.provider_id', 'provider.provider_id')
			->leftjoin('entity','provider.entity_id','entity.entity_id')
			->leftjoin('event_type', 'program.event_type_id', 'event_type.event_type_id')
			/*->join('entity', 'provider.entity_id', 'entity.entity_id')*/

			->leftjoin('schedule', 'course.course_id', 'schedule.course_id')
			->leftjoin('user', 'schedule.instructor_id', 'user.user_id')
			->leftjoin('activity', 'program.activity_id', 'activity.activity_id')
			->leftjoin('activity_classification', 'activity_classification.activity_classification_id', 'activity.activity_classification_id')
			->groupBy(
				DB::raw('curriculum.curriculum_id, course.course_id, entity.logo, course.day_of_week, entity.name, curriculum.name, course.time_start, course.time_end' .
					(isset($conditions['sort']) && $conditions['sort'] !== null ? ", " . $extraGroup : '' )
				)
			)
			->orderByRaw(isset($conditions['sort']) && $conditions['sort'] !== null ? $conditions['sort'] : 'course.day_of_week DESC, course.course_id DESC')
			->limit($limit)
			->offset(isset($conditions['page']) && $conditions['page'] !== null ? ($conditions['page']-1) * $limit : 0)
			->get();

		$searchAll = DB::table('course')
			->select('curriculum.curriculum_id','course.course_id', DB::raw('entity.logo entity_logo'), 'course.day_of_week',
				DB::raw('entity.name entity_name, curriculum.name curriculum_name'), 'course.time_start', 'course.time_end')
			->whereRaw(
				(isset($conditions['is_free_trial'])&& sizeof($conditions['is_free_trial']) > 0 ? "course.is_free_trial in (" . implode(",", $conditions['is_free_trial']) . ") AND " : '') .

				(isset($conditions['country_id']) && $conditions['country_id'] !== null ? "campus.country_id in (" . implode(",",$conditions['country_id']) . ") AND " : '') .
				(isset($conditions['city_id']) && $conditions['city_id'] !== null ? "campus.city_id in (" . implode(",",$conditions['city_id']) . ") AND " : '') .
				(isset($conditions['location_id']) && $conditions['location_id'] !== null ? "campus.location_id in (" . implode(",",$conditions['location_id']) . ") AND " : '') .

				(isset($conditions['activity_id']) && $conditions['activity_id'] !== null ? "program.activity_id in (" . implode(",",$conditions['activity_id']) . ") AND " : '') .
				(isset($conditions['activity_classification_id']) && $conditions['activity_classification_id'] !== null ? "activity.activity_classification_id in (" . implode(",",$conditions['activity_classification_id']) . ") AND " : '') .
				(isset($conditions['activity_type_id']) && $conditions['activity_type_id'] !== null ? "activity_classification.activity_type_id in (" . implode(",",$conditions['activity_type_id']) . ") AND " : '') .

				(isset($conditions['event_type_id']) && $conditions['event_type_id'] !== null ? "program.event_type_id in (" . implode(",",$conditions['event_type_id']) . ") AND " : '') .
				(isset($conditions['facility_id']) && $conditions['facility_id'] !== null ? "campus.facility_id in (" . implode(",",$conditions['facility_id']) . ") AND " : '') .
				(isset($conditions['campus_id']) && $conditions['campus_id'] !== null ? "campus_arena.campus_id in (" . implode(",",$conditions['campus_id']) . ") AND " : '') .
				(isset($conditions['program_id']) && $conditions['program_id'] !== null ? "curriculum.program_id in (" . implode(",",$conditions['program_id']) . ") AND " : '') .
				(isset($conditions['arena_id']) && $conditions['arena_id'] !== null ? "campus_arena.arena_id in (" . implode(",",$conditions['arena_id']) . ") AND " : '') .
				(isset($conditions['provider_id']) && $conditions['provider_id'] !== null ? "program.provider_id in (" . implode(",",$conditions['provider_id']) . ") AND " : '') .
				(isset($conditions['user_id']) && $conditions['user_id'] !== null ? "schedule.instructor_id in (" . implode(",",$conditions['user_id']) . ") AND " : '') .
				(isset($conditions['day_of_week']) && $conditions['day_of_week'] !== null ? "course.day_of_week in (" . implode(",",$conditions['day_of_week']) . ") AND " : '') .

				(isset($conditions['time_start']) && $conditions['time_start'] !== null ? "time(course.time_start) = time('" . $conditions['time_start'] . "') AND " : '') .
				(isset($conditions['time_end']) && $conditions['time_end'] !== null ? "time(course.time_end) = time('" . $conditions['time_end'] . "') AND " : '') .

				(isset($conditions['audience_gender_id']) && $conditions['audience_gender_id'] !== null ? "course.audience_gender_id in (" . implode(",",$conditions['audience_gender_id']) . ") AND " : '') .
				(isset($conditions['audience_generation_id']) && $conditions['audience_generation_id'] !== null ? "course.audience_generation_id in (" . implode(",",$conditions['audience_generation_id']) . ") AND " : '') .

				(isset($conditions['age_range_top']) && $conditions['age_range_top'] !== null ? "course.age_range_top >= " . $conditions['age_range_top'] . " AND " : '') .
				(isset($conditions['age_range_bottom']) && $conditions['age_range_bottom'] !== null ? "course.age_range_bottom <= " . $conditions['age_range_bottom'] . " AND " : '') .

				(isset($conditions['keyword']) && sizeof($conditions['keyword']) > 0 ? "(" . implode(" OR ", $conditions['keyword']) . ") AND " : '') .
				(isset($alphabets) && sizeof($alphabets) > 0 ? "(" . join(" OR ", $alphabets) . ") AND " : '') .
				('course.status = 0')
			)
			->join('campus_arena', function($join){
				$join->on('course.campus_arena_id','campus_arena.campus_arena_id');
				//$join->on('course.arena_id', 'campus_arena.arena_id');
			})
			->leftjoin('campus', 'campus_arena.campus_id', 'campus.campus_id')
			->leftjoin('location', 'campus.location_id', 'location.location_id')

			->leftjoin('facility', function ($join) {
				$join->on('campus.facility_id','facility.facility_id');
				$join->on('campus.country_id','facility.country_id');
			})

			->leftjoin('curriculum', 'course.curriculum_id', 'curriculum.curriculum_id')

			->leftjoin('program','program.program_id','curriculum.program_id')
			->leftjoin('provider', 'program.provider_id', 'provider.provider_id')
			->leftjoin('entity','provider.entity_id','entity.entity_id')
			->leftjoin('event_type', 'program.event_type_id', 'event_type.event_type_id')
			/*->join('entity', 'provider.entity_id', 'entity.entity_id')*/

			->leftjoin('schedule', 'course.course_id', 'schedule.course_id')
			->leftjoin('user', 'schedule.instructor_id', 'user.user_id')
			->leftjoin('activity', 'program.activity_id', 'activity.activity_id')
			->leftjoin('activity_classification', 'activity_classification.activity_classification_id', 'activity.activity_classification_id')
			//->groupBy('course.course_id','schedule.instructor_id','curriculum.curriculum_id','entity.logo', 'course.day_of_week','entity.name','curriculum.name','course.time_start', 'course.time_end','event_type.name')
			->groupBy(
				DB::raw('curriculum.curriculum_id, course.course_id, entity.logo, course.day_of_week, entity.name, curriculum.name, course.time_start, course.time_end' .
					(isset($conditions['sort']) && $conditions['sort'] !== null ? ", " . $extraGroup : '' )
				)
			)
			->orderByRaw(isset($conditions['sort']) && $conditions['sort'] !== null ? $conditions['sort'] : 'course.day_of_week DESC, course.course_id DESC')
			->get();

		//echo("<br>Total : " . sizeof($searchData) . " records");
		foreach ($searchData as $data) {
			/*echo('<br>' . $data->program_id . '|' . $data->program_name . '|'.  $data->activity_id . '|' . $data->provider_name . '|' . $data->location_name);*/
			//echo('<br>' . $data->provider_id . '|' . $data->provider_name . '|' . $data->location_name);


			$day = DB::table('day_of_week')->where('day_of_week.day_of_week_id',$data->day_of_week)->first();
			$day = DB::table('day_of_week')->where('day_of_week.day_of_week_id',$data->day_of_week)->first();


			//echo('<br>' . $data->course_id . "~" . $data->program_name . '#'.$day->name. "|". date('h:i',strtotime($data->time_start)) . '-' .date('h:i',strtotime($data->time_end)));
			$item = ['course_id' => $data->course_id,
				'entity_logo' => ($data->entity_logo ? $data->entity_logo : $default_logo),
				'entity_name' => $data->entity_name,
				'curriculum_name' => $data->curriculum_name,
				'day_name' => $day->name,
				'time_start' => date('h:i',strtotime($data->time_start)),
				'time_end' => date('h:i',strtotime($data->time_end))];

			/*$schedules = DB::table('schedule')
				->select('schedule.schedule_id', 'program.name')
				->join('program', 'program.program_id', 'curriculum.program_id')
				->where('curriculum.curriculum_id', $data->curriculum_id)
				->orderBy('program.activity_id', 'DESC')
				->get();

			foreach ($schedules as $schedule) {
				//echo($program->program_id . '|' . $program->name );
			}*/

			$programs = DB::table('curriculum')
				->select('program.program_id', 'program.name')
				->join('program', 'program.program_id', 'curriculum.program_id')
				->where('curriculum.curriculum_id', $data->curriculum_id)
				->orderBy('program.activity_id', 'DESC')
				->get();

			$searchActivities = [];
			foreach ($programs as $program) {
				//echo($program->program_id . '|' . $program->name );
				$activities = DB::table('program')
					->select('activity.logo', 'program.provider_id', DB::raw('program.name program_name'))
					->join('activity', 'program.activity_id', 'activity.activity_id')
					->leftjoin('activity_classification', 'activity.activity_classification_id', 'activity_classification.activity_classification_id')
					->where('program.program_id', $program->program_id)
					->whereRaw(
						(isset($conditions['activity_id']) && $conditions['activity_id'] !== null ? "program.activity_id in (" . implode(",",$conditions['activity_id']) . ") AND " : '') .
						(isset($conditions['activity_classification_id']) && $conditions['activity_classification_id'] !== null ? "activity.activity_classification_id in (" . implode(",",$conditions['activity_classification_id']) . ") AND " : '') .
						(isset($conditions['activity_type_id']) && $conditions['activity_type_id'] !== null ? "activity_classification.activity_type_id in (" . implode(",",$conditions['activity_type_id']) . ") AND " : '') .
						('program.status = 0')
					)
					//->groupBy('activity.logo')
					->get();

				foreach ($activities as $activity) {
					//echo('<img src="' . $activity->logo . '" style="width:2%;" alt="' . $activity->program_name . '" title="' . $activity->program_name . '">');
					$searchActivity = [
						'logo' => $activity->logo,
						'name' => $activity->program_name,
					];
					array_push($searchActivities, $searchActivity);
				}
			}
			array_push($result, array('item' => $item, 'activity' => $searchActivities));
		}

		$final = array('totalPage' => ceil(sizeof($searchAll)/$limit), 'total' => ( sizeof($searchAll) > 0 ? sizeof($searchAll) : 0), 'result' => $result, 'debug' => DB::getQueryLog()[0]['query']);
		return $final;
	}

	public function prepare($parameters)
	{
		// TODO: Implement prepare() method.
		// echo ("Parameter support: Free Trial, Country, City, Location, Activity Type, Activity Classification, Activity,<br>");
		// echo ("Event Type, Facility, Campus, Arena, Provider, Instructor, Program, Day, Start Time, End Time, Gender, Generation, Age, Keyword");
		$conditions = array();

		foreach ($parameters as $name => $value) {
			switch ($name) {
				case 'sortBy': {
					if ($value === 'day') {
						$strCondition = "course.day_of_week DESC, course.course_id DESC";
					}
					elseif ($value === 'location') {
						$strCondition = "location.location_id ASC, entity.name ASC, course.course_id DESC";
					}
					elseif ($value === 'timing') {
						$strCondition = "course.time_start ASC, course.course_id DESC";
					}

					$conditions['sort'] = $strCondition;
					break;
				}
				case 'freeTrial': {
					$items = explode(",",$value);
					$data=[];
					if (sizeof($items) > 0){
						$strCondition = [];
						foreach ($items as $item) {
							if (strtolower($item) == 'yes' && strtolower($item) !== 'any') {
								$data[] = 1;
							}
							else if (strtolower($item) == 'no'  && strtolower($item) !== 'any') {

								$data[] = 0;
							}
						}
					}
					else
					{
						if (strtolower($value) == 'yes' && strtolower($value) == 'any') {
							$data[] = 1;
						}
						else if (strtolower($value) == 'no' && strtolower($value) == 'any') {
							$data[] = 0;
						}
					}
					$conditions['is_free_trial'] = $data;

					break;
				}
				case 'country': {
					$items = explode(",",$value);
					if (sizeof($items) > 0){
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "lower(name) like " . DB::Raw("'%" . strtolower($item) . "%'");
						}
						$countries = DB::table('country')->whereRaw( join(" OR ", $strCondition))->pluck('country_id');
					}
					else
					{
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

					$items = explode(",",$value);
					if (sizeof($items) > 0){
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "lower(name) like " . DB::Raw("'%" . strtolower($item) . "%'");
						}
						$cities = DB::table('city')->whereRaw( join(" OR ", $strCondition))->pluck('city_id');
					}
					else
					{
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

					$items = explode(",",$value);
					if (sizeof($items) > 0){
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "lower(name) like " . DB::Raw("'%" . strtolower($item) . "%'");
						}
						$locations = DB::table('location')->whereRaw( join(" OR ", $strCondition))->pluck('location_id');
					}
					else
					{
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

					$items = explode(",",$value);
					if (sizeof($items) > 0){
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "lower(name) like " . DB::Raw("'%" . strtolower($item) . "%'");
						}
						$activitiesType = DB::table('activity_type')->whereRaw( join(" OR ", $strCondition))->pluck('activity_type_id');
					}
					else
					{
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
					$items = explode(",",$value);
					if (sizeof($items) > 0){
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "lower(name) like " . DB::Raw("'%" . strtolower($item) . "%'");
						}
						$activitiesClassification = DB::table('activity_classification')->whereRaw( join(" OR ", $strCondition))->pluck('activity_classification_id');
					}
					else
					{
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
					$items = explode(",",$value);
					if (sizeof($items) > 0){
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "lower(name) like " . DB::Raw("'%" . strtolower($item) . "%'");
						}
						$activities = DB::table('activity')->whereRaw( join(" OR ", $strCondition))->pluck('activity_id');
					}
					else
					{
						$activities = DB::table('activity')->whereRaw('lower(name) like ' . DB::Raw("'%" . strtolower($value) . "%'"))->pluck('activity_id');
					}

					$data = null;
					foreach ($activities as $activity) {
						$data[] = $activity;
					}

					$conditions['activity_id'] = $data;
					break;
				}
				case 'eventType': {
					$items = explode(",",$value);
					if (sizeof($items) > 0){
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "lower(name) like " . DB::Raw("'%" . strtolower($item) . "%'");
						}
						$eventsTypes = DB::table('event_type')->whereRaw( join(" OR ", $strCondition))->pluck('event_type_id');
					}
					else
					{
						$eventsTypes = DB::table('event_type')->whereRaw('lower(name) like ' . DB::Raw("'%" . strtolower($value) . "%'"))->pluck('event_type_id');
					}

					$data = null;
					foreach ($eventsTypes as $eventType) {
						$data[] = $eventType;
					}

					$conditions['event_type_id'] = $data;
					break;
				}
				case 'facility': {
					$items = explode(",",$value);
					if (sizeof($items) > 0){
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "lower(name) like " . DB::Raw("'%" . strtolower($item) . "%' ");
						}
						$facilities = DB::table('facility')
							->whereRaw(join(" OR ", $strCondition))
							->where("facility.status","=","1")->pluck('facility_id');
					}
					else
					{
						$facilities = DB::table('facility')->whereRaw(
							(isset($value) && $value !== null ? "facility.name like " . DB::Raw("'%" . strtolower($value) . "%' ") : "")
						)->where("facility.status","=","1")->pluck('facility_id');
					}

					$data = null;
					foreach ($facilities as $facility) {
						$data[] = $facility;
					}

					$conditions['facility_id'] = $data;
					break;
				}
				case 'campus': {
					$items = explode(",",$value);
					if (sizeof($items) > 0){
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "lower(name) like " . DB::Raw("'%" . strtolower($item) . "%'");
						}
						$campuses = DB::table('campus')->whereRaw( join(" OR ", $strCondition))->pluck('campus_id');
					}
					else
					{
						$campuses = DB::table('campus')->whereRaw('lower(name) like ' . DB::Raw("'%" . strtolower($value) . "%'"))->pluck('campus_id');
					}

					$data = null;
					foreach ($campuses as $campus) {
						$data[] = $campus;
					}

					$conditions['campus_id'] = $data;
					break;
				}
				case 'arena': {
					$items = explode(",",$value);
					if (sizeof($items) > 0){
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "lower(name) like " . DB::Raw("'%" . strtolower($item) . "%'");
						}
						$arenas = DB::table('arena')->whereRaw( join(" OR ", $strCondition))->pluck('arena_id');
					}
					else
					{
						$arenas = DB::table('arena')->whereRaw('lower(name) like ' . DB::Raw("'%" . strtolower($value) . "%'"))->pluck('arena_id');
					}

					$data = null;
					foreach ($arenas as $arena) {
						$data[] = $arena;
					}
					$conditions['arena_id'] = $data;
					break;
				}
				case 'provider': {
					$items = explode(",",$value);
					if (sizeof($items) > 0){
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "lower(name) like " . DB::Raw("'%" . strtolower($item) . "%'");
						}
						$providers = DB::table('provider')->whereRaw( join(" OR ", $strCondition))->pluck('provider_id');
					}
					else
					{
						$providers = DB::table('provider')->whereRaw('lower(name) like ' . DB::Raw("'%" . strtolower($value) . "%'"))->pluck('provider_id');
					}

					$data = null;
					foreach ($providers as $provider) {
						$data[] = $provider;
					}
					$conditions['provider_id'] = $data;
					break;
				}
				case 'instructor': {
					$items = explode(",",$value);
					if (sizeof($items) > 0){
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "(lower(concat(trim(first_name), ' ', trim(last_name))) like " . DB::Raw("'%" . strtolower($item) . "%')");
						}
						$instructors = DB::table('user')->whereRaw( join(" OR ", $strCondition))->where('is_instructor','=','1')->where('status','=','2')->pluck('user_id');
					}
					else
					{
						$instructors = DB::table('user')->whereRaw(
						"lower(concat(trim(first_name), ' ', trim(last_name))) like " . DB::Raw("'%" . strtolower($value) . "%'")
					)->where('is_instructor','=','1')->where('status','=','2')->pluck('user_id');
					}

					$data = null;
					foreach ($instructors as $instructor) {
						$data[] = $instructor;
					}
					$conditions['user_id'] = $data;
					break;
				}
				case 'program': {
					$items = explode(",",$value);
					if (sizeof($items) > 0){
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "lower(name) like " . DB::Raw("'%" . strtolower($item) . "%'");
						}
						$programs = DB::table('program')->whereRaw( join(" OR ", $strCondition))->pluck('program_id');
					}
					else
					{
						$programs = DB::table('program')->whereRaw('lower(name) like ' . DB::Raw("'%" . strtolower($value) . "%'"))->pluck('program_id');
					}

					$data = null;
					foreach ($programs as $program) {
						$data[] = $program;
					}
					$conditions['program_id'] = $data;
					break;
				}
				case 'day': {
					$items = explode(",",$value);
					if (sizeof($items) > 0){
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "lower(name) like " . DB::Raw("'%" . strtolower($item) . "%'");
						}
						$days = DB::table('day_of_week')->whereRaw( join(" OR ", $strCondition))->pluck('day_of_week_id');
					}
					else
					{
						$days = DB::table('day_of_week')->whereRaw('lower(name) like ' . DB::Raw("'%" . strtolower($value) . "%'"))->pluck('day_of_week_id');
					}

					$data = null;
					foreach ($days as $day) {
						$data[] = $day;
					}
					$conditions['day_of_week'] = $data;
					break;
				}
				case 'timeStart': {
					$conditions['time_start'] = $value;
					break;
				}
				case 'timeEnd': {
					$conditions['time_end'] = $value;
					break;
				}
				case 'gender': {
					$items = explode(",",$value);
					if (sizeof($items) > 0){
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "lower(name) like " . DB::Raw("'%" . strtolower($item) . "%'");
						}
						$genders = DB::table('audience_gender')->whereRaw( join(" OR ", $strCondition))->pluck('audience_gender_id');
					}
					else
					{
						$genders = DB::table('audience_gender')->whereRaw('lower(name) like ' . DB::Raw("'%" . strtolower($value) . "%'"))->pluck('audience_gender_id');
					}

					$data = null;
					foreach ($genders as $gender) {
						$data[] = $gender;
					}
					$conditions['audience_gender_id'] = $data;
					break;
				}
				case 'generation': {
					$items = explode(",",$value);
					if (sizeof($items) > 0){
						$strCondition = [];
						foreach ($items as $item) {
							$strCondition[] = "lower(name) like " . DB::Raw("'%" . strtolower($item) . "%'");
						}
						$generations = DB::table('audience_generation')->whereRaw( join(" OR ", $strCondition))->pluck('audience_generation_id');
					}
					else
					{
						$generations = DB::table('audience_generation')->whereRaw('lower(name) like ' . DB::Raw("'%" . strtolower($value) . "%'"))->pluck('audience_generation_id');
					}

					$data = null;
					foreach ($generations as $generation) {
						$data[] = $generation;
					}
					$conditions['audience_generation_id'] = $data;
					break;
				}
				case 'ageFrom': {
					$conditions['age_range_top'] = (strtolower($value) !== 'any' ? $value : 0);
					break;
				}
				case 'ageTo': {
					$conditions['age_range_bottom'] = (strtolower($value) !== 'any' ? $value : 100);
					break;
				}
				case 'alphabet': {
					$conditions['alphabet'] = explode(",",$value);
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
