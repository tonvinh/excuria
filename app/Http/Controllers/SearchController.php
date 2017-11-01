<?php

namespace App\Http\Controllers;

use App\Instructor;
use App\Provider;
use App\Course;
use App\Filter;

use Illuminate\Http\Request;
use DB, View;
use Response;
use Memcached;


class SearchController extends Controller
{
	public function index()
	{
		return redirect('search?activeTAB=provider');
	}

	public function search(Request $request, $type = null)
	{
		$result = null;
		$parameter = null;
		$conditions = array();

		$type = (!isset($type) ? 'provider' : $type);

		if ($request->activeTAB != '' && $request->activeTAB !== '')
		{
			$type = $request->activeTAB;
		}
		else{
			$type = 'provider';
		}

		switch ($type) {
			case 'provider': {
				$parameters = $request->all();

				/*echo('Parameters support: keyword, city, country, activity, activityClassification, activityType, page<br>');*/

				$search = new Provider();
				$conditions = $search->prepare($parameters);

				$page = $request->input('page');

				$mem = new Memcached();
				$mem->addServer(env('memcached_server'), env('memcached_port'));
				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return view('search',array(
						'path'=>$type,
						'result'=>$resultMem['result'],
						'totalPage'=>$resultMem['totalPage'],
						'debug'=>$resultMem['debug'],
						'total'=>$resultMem['total'],
						'page'=> (isset($page) && $page !=null ? $page : 1)
					));

				} else {

					$none_memcache = false;
					$result = $search->search($conditions);
					$mem->set($_SERVER['REQUEST_URI'], $result) or $none_memcache = true;

					if ($none_memcache) {

						/* None memcache */
						return view('search',array(
							'path'=>$type,
							'result'=>$resultMem['result'],
							'totalPage'=>$resultMem['totalPage'],
							'debug'=>$resultMem['debug'],
							'total'=>$resultMem['total'],
							'page'=> (isset($page) && $page !=null ? $page : 1)
						));
					} else {

						/* Use memcache */
						$resultMem = $mem->get($_SERVER['REQUEST_URI']);

						return view('search',array(
							'path'=>$type,
							'result'=>$resultMem['result'],
							'totalPage'=>$resultMem['totalPage'],
							'debug'=>$resultMem['debug'],
							'total'=>$resultMem['total'],
							'page'=> (isset($page) && $page !=null ? $page : 1)
						));
					}
				}
				break;
			}
			case 'course': {
				$parameters = $request->all();

				/*echo("Parameters support: Free Trial, Country, City, Location, Activity Type, Activity Classification, Activity,<br>");
				echo("Event Type, Facility, Campus, Arena, Provider, Instructor, Program, Day, Start Time, End Time, Gender, Generation, Age, Keyword<br>");*/

				$search = new Course();
				$conditions = $search->prepare($parameters);

				$page = $request->input('page');

				$mem = new Memcached();
				$mem->addServer(env('memcached_server'), env('memcached_port'));

				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return view('search',array(
						'path'=>$type,
						'result'=>$resultMem['result'],
						'totalPage'=>$resultMem['totalPage'],
						'debug'=>$resultMem['debug'],
						'total'=>$resultMem['total'],
						'page'=> (isset($page) && $page !=null ? $page : 1)
					));

				} else {

					$none_memcache = false;
					$result = $search->search($conditions);
					$mem->set($_SERVER['REQUEST_URI'], $result) or $none_memcache = true;

					if ($none_memcache) {

						/* None memcache */
						return view('search',array(
							'path'=>$type,
							'result'=>$resultMem['result'],
							'totalPage'=>$resultMem['totalPage'],
							'debug'=>$resultMem['debug'],
							'total'=>$resultMem['total'],
							'page'=> (isset($page) && $page !=null ? $page : 1)
						));
					} else {

						/* Use memcache */
						$resultMem = $mem->get($_SERVER['REQUEST_URI']);

						return view('search',array(
							'path'=>$type,
							'result'=>$resultMem['result'],
							'totalPage'=>$resultMem['totalPage'],
							'debug'=>$resultMem['debug'],
							'total'=>$resultMem['total'],
							'page'=> (isset($page) && $page !=null ? $page : 1)
						));
					}
				}
				break;
			}
			case 'instructor': {
				$parameters = $request->all();

				/*echo('Parameters support: keyword, city, country, activity, activityClassification, activityType, page<br>');*/
				$search = new Instructor();
				$conditions = $search->prepare($parameters);

				$page = $request->input('page');

				$mem = new Memcached();
				$mem->addServer(env('memcached_server'), env('memcached_port'));
				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return view('search',array(
						'path'=>$type,
						'result'=>$resultMem['result'],
						'totalPage'=>$resultMem['totalPage'],
						'debug'=>$resultMem['debug'],
						'total'=>$resultMem['total'],
						'page'=> (isset($page) && $page !=null ? $page : 1)
					));

				} else {

					$none_memcache = false;
					$result = $search->search($conditions);
					$mem->set($_SERVER['REQUEST_URI'], $result) or $none_memcache = true;

					if ($none_memcache) {

						/* None memcache */
						return view('search',array(
							'path'=>$type,
							'result'=>$resultMem['result'],
							'totalPage'=>$resultMem['totalPage'],
							'debug'=>$resultMem['debug'],
							'total'=>$resultMem['total'],
							'page'=> (isset($page) && $page !=null ? $page : 1)
						));
					} else {

						/* Use memcache */
						$resultMem = $mem->get($_SERVER['REQUEST_URI']);

						return view('search',array(
							'path'=>$type,
							'result'=>$resultMem['result'],
							'totalPage'=>$resultMem['totalPage'],
							'debug'=>$resultMem['debug'],
							'total'=>$resultMem['total'],
							'page'=> (isset($page) && $page !=null ? $page : 1)
						));
					}
				}
				break;
			}
			default:
				die('Un-supported object');
		}
	}

	public function searchUpdate(Request $request, $type = null)
	{
		$result = null;
		$parameter = null;
		$conditions = array();

		$type = (!isset($type) ? 'provider' : $type);

		if ($request->activeTAB != '' && $request->activeTAB !== '')
		{
			$type = $request->activeTAB;
		}
		else{
			$type = 'provider';
		}

		switch ($type) {
			case 'provider': {
				$parameters = $request->all();
				$search = new Provider();
				$conditions = $search->prepare($parameters);

				$page = $request->input('page');

				$mem = new Memcached();
				$mem->addServer(env('memcached_server'), env('memcached_port'));
				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return view('search_update',array(
						'path'=>$type,
						'result'=>$resultMem['result'],
						'totalPage'=>$resultMem['totalPage'],
						'debug'=>$resultMem['debug'],
						'total'=>$resultMem['total'],
						'page'=> (isset($page) && $page !=null ? $page : 1)
					));

				} else {

					$none_memcache = false;
					$result = $search->search($conditions);
					$mem->set($_SERVER['REQUEST_URI'], $result) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = $search->search($conditions);
						return view('search_update',array(
							'path'=>$type,
							'result'=>$result['result'],
							'totalPage'=>$result['totalPage'],
							'debug'=>$result['debug'],
							'total'=>$result['total'],
							'page'=> (isset($page) && $page !=null ? $page : 1)
						));
					} else {

						/* Use memcache */
						$resultMem = $mem->get($_SERVER['REQUEST_URI']);
						return view('search_update',array(
							'path'=>$type,
							'result'=>$resultMem['result'],
							'totalPage'=>$resultMem['totalPage'],
							'debug'=>$resultMem['debug'],
							'total'=>$resultMem['total'],
							'page'=> (isset($page) && $page !=null ? $page : 1)
						));
					}
				}
				break;
			}
			case 'course': {
				$parameters = $request->all();
				$search = new Course();
				$conditions = $search->prepare($parameters);

				$page = $request->input('page');

				$mem = new Memcached();
				$mem->addServer(env('memcached_server'), env('memcached_port'));
				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return view('search_update',array(
						'path'=>$type,
						'result'=>$resultMem['result'],
						'totalPage'=>$resultMem['totalPage'],
						'debug'=>$resultMem['debug'],
						'total'=>$resultMem['total'],
						'page'=> (isset($page) && $page !=null ? $page : 1)
					));

				} else {

					$none_memcache = false;
					$result = $search->search($conditions);
					$mem->set($_SERVER['REQUEST_URI'], $result) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = $search->search($conditions);
						return view('search_update',array(
							'path'=>$type,
							'result'=>$result['result'],
							'totalPage'=>$result['totalPage'],
							'debug'=>$result['debug'],
							'total'=>$result['total'],
							'page'=> (isset($page) && $page !=null ? $page : 1)
						));
					} else {

						/* Use memcache */
						$resultMem = $mem->get($_SERVER['REQUEST_URI']);
						return view('search_update',array(
							'path'=>$type,
							'result'=>$resultMem['result'],
							'totalPage'=>$resultMem['totalPage'],
							'debug'=>$resultMem['debug'],
							'total'=>$resultMem['total'],
							'page'=> (isset($page) && $page !=null ? $page : 1)
						));
					}
				}
				break;
			}
			case 'instructor': {
				$parameters = $request->all();

				$search = new Instructor();
				$conditions = $search->prepare($parameters);
				$page = $request->input('page');

				$mem = new Memcached();
				$mem->addServer(env('memcached_server'), env('memcached_port'));
				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return view('search_update',array(
						'path'=>$type,
						'result'=>$resultMem['result'],
						'totalPage'=>$resultMem['totalPage'],
						'debug'=>$resultMem['debug'],
						'total'=>$resultMem['total'],
						'page'=> (isset($page) && $page !=null ? $page : 1)
					));

				} else {

					$none_memcache = false;
					$result = $search->search($conditions);
					$mem->set($_SERVER['REQUEST_URI'], $result) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = $search->search($conditions);
						return view('search_update',array(
							'path'=>$type,
							'result'=>$result['result'],
							'totalPage'=>$result['totalPage'],
							'debug'=>$result['debug'],
							'total'=>$result['total'],
							'page'=> (isset($page) && $page !=null ? $page : 1)
						));
					} else {

						/* Use memcache */
						$resultMem = $mem->get($_SERVER['REQUEST_URI']);
						return view('search_update',array(
							'path'=>$type,
							'result'=>$resultMem['result'],
							'totalPage'=>$resultMem['totalPage'],
							'debug'=>$resultMem['debug'],
							'total'=>$resultMem['total'],
							'page'=> (isset($page) && $page !=null ? $page : 1)
						));
					}
				}

				break;
			}
			default:
				die('Un-supported object');
		}
	}

	public function searchCounter(Request $request, $type = null)
	{
		$result = null;
		$parameter = null;
		$conditions = array();

		$type = (!isset($type) ? 'provider' : $type);

		if ($request->activeTAB != '' && $request->activeTAB !== '')
		{
			$type = $request->activeTAB;
		}
		else{
			$type = 'provider';
		}

		switch ($type) {
			case 'provider': {
				$parameters = $request->all();
				$search = new Provider();
				$conditions = $search->prepare($parameters);

				$result = $search->search($conditions);

				return $result['total'];

				break;
			}
			case 'course': {
				$parameters = $request->all();
				$search = new Course();
				$conditions = $search->prepare($parameters);
				$result = $search->search($conditions);

				return $result['total'];

				break;
			}
			case 'instructor': {
				$parameters = $request->all();

				$search = new Instructor();
				$conditions = $search->prepare($parameters);

				$result = $search->search($conditions);


				return $result['total'];

				break;
			}
			default:
				return 0;
		}
	}

	public function prepareFilter(Request $request, $name)
	{
		$mem = new Memcached();
		$mem->addServer(env('memcached_server'), env('memcached_port'));

		switch ($name) {
			case 'classAvailable': {
				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return Response::json($resultMem);

				} else {

					$none_memcache = false;
					$_request = $request;
					$_request->query->add(['classAvailable' => 'yes']);
					$counter_yes = $this->searchCounter($_request, $_request['activeTAB']);
					$_request['classAvailable'] = 'no';
					$counter_no = $this->searchCounter($_request, $_request['activeTAB']);

					$result[] = ['name'=> 'Yes', 'counter' => $counter_yes];
					$result[] = ['name'=> 'No', 'counter' => $counter_no];

					$mem->set($name, $result) or $none_memcache = true;

					if ($none_memcache) {

						/* None memcache */
						return Response::json($result);

					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem);

					}
				}

				break;
			}
			case 'freeTrial': {

				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return Response::json($resultMem);

				} else {

					$none_memcache = false;
					$_request = $request;
					$_request->query->add(['freeTrial' => 'yes']);
					$counter_yes = $this->searchCounter($_request, $_request['activeTAB']);
					$_request['freeTrial'] = 'no';
					$counter_no = $this->searchCounter($_request, $_request['activeTAB']);

					$result[] = ['name'=> 'Yes', 'counter' => $counter_yes];
					$result[] = ['name'=> 'No', 'counter' => $counter_no];

					$mem->set($name, $result) or $none_memcache = true;

					if ($none_memcache) {

						/* None memcache */
						return Response::json($result);

					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem);

					}
				}

				break;
			}
			case 'country': {

				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$data = $this->getResultMemcached($request, $mem, $name,null);
					return Response::json($data);

				}

				break;
			}
			case 'city': {

				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$data = $this->getResultMemcached($request, $mem, $name, ['country' => $request->input('country')]);
					return Response::json($data);

				}

				break;
			}
			case 'location': {
				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$data = $this->getResultMemcached($request, $mem, $name, ['city' => $request->input('city')]);
					return Response::json($data);

				}

				break;
			}
			case 'activityType': {

				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$data = $this->getResultMemcached($request, $mem, $name,null);
					return Response::json($data);

				}
			}
			case 'activityClassification': {

				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$data = $this->getResultMemcached($request, $mem, $name, ['activityType' => $request->input('activityType')]);
					return Response::json($data);

				}

				break;
			}
			case 'activity': {
				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$data = $this->getResultMemcached($request, $mem, $name, ['activityClassification' => $request->input('activityClassification')]);
					return Response::json($data);

				}

				break;
			}
			case 'eventType': {

				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$data = $this->getResultMemcached($request, $mem, $name,null);
					return Response::json($data);

				}

				break;
			}
			case 'facility': {

				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$data = $this->getResultMemcached($request, $mem, $name,null);
					return Response::json($data);

				}

				break;
			}
			case 'campus': {

				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$data = $this->getResultMemcached($request, $mem, $name,['facility' => $request->input('facility')]);
					return Response::json($data);

				}

				break;
			}
			case 'arena': {

				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$data = $this->getResultMemcached($request, $mem, $name,['campus' => $request->input('campus')]);
					return Response::json($data);

				}

				break;
			}
			case 'provider': {

				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$data = $this->getResultMemcached($request, $mem, $name,null);
					return Response::json($data);

				}

				break;
			}
			case 'instructor': {

				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$result = new Filter('instructor',null);

					foreach ($result->data as $data)
					{

						$_request = $request;
						$_request->query->add(['user_id' => $data->user_id]);

						$counter = $this->searchCounter($_request, $_request['activeTAB']);


						$data->counter = $counter;
					}

					$mem->set($name, $result) or $none_memcache = true;

					if ($none_memcache) {

						/* None memcache */
						return Response::json($result->data);

					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem->data);

					}
				}

				break;
			}
			case 'program': {

				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$data = $this->getResultMemcached($request, $mem, $name,null);
					return Response::json($data);

				}

				break;
			}			
			case 'gender': {

				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$data = $this->getResultMemcached($request, $mem, $name,null);
					return Response::json($data);

				}

				break;
			}
			case 'generation': {

				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$data = $this->getResultMemcached($request, $mem, $name,null);
					return Response::json($data);

				}

				break;
			}
			case 'day': {

				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$data = $this->getResultMemcached($request, $mem, $name,null);
					return Response::json($data);

				}

				break;
			}
			case 'timeStart': {

				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$data = $this->getResultMemcached($request, $mem, $name,null);
					return Response::json($data);

				}

				break;
			}			
			case 'timeEnd': {

				$resultMem = $mem->get($_SERVER['REQUEST_URI']);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$data = $this->getResultMemcached($request, $mem, $name,null);
					return Response::json($data);

				}

				break;
			}
			case 'ageFrom': {

				$resultMem = $mem->get($name);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$mem->set($name, new Filter('ageFrom',null)) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = new Filter('ageFrom',null);
						return Response::json($result->data);
					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem->data);
					}
				}
				break;
			}
			case 'ageTo': {

				$resultMem = $mem->get($name);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$mem->set($name, new Filter('ageTo',null)) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = new Filter('ageTo',null);
						return Response::json($result->data);
					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem->data);
					}
				}
				break;
			}

			case 'autosuggest':
			{
				$resultMem = $mem->get($name."-".$request->keyword);

				if ($resultMem) {
					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$mem->set($name, new Filter('autosuggest', ['keyword' => $request->keyword])) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = new Filter('autosuggest', ['keyword' => $request->keyword]);
						return Response::json($result->data);
					} else {
						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem->data);
					}
				}

				break;
			}
			default:
				return null;
		}
	}

	public function getTotalRecord(Request $request, $entity_type = null)
	{
		$entity_type = (!isset($entity_type) ? 'provider' : $entity_type);

		switch ($entity_type) {
			case 'provider': {
				$search = new Provider();

				$mem = new Memcached();
				$mem->addServer(env('memcached_server'), env('memcached_port'));

				$resultMem = $mem->get('entity_provider');

				if ($resultMem) {

					return $resultMem['total'];

				} else {

					$none_memcache = false;

					$result = $search->search(null);
					$mem->set('entity_provider', $result) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						return $result['total'];
					} else {

						/* Use memcache */
						$resultMem = $mem->get('entity_provider');
						return $resultMem['total'];
					}
				}
				break;
			}
			case 'course':
			{
				$search = new Course();

				$mem = new Memcached();
				$mem->addServer(env('memcached_server'), env('memcached_port'));

				$resultMem = $mem->get('entity_course');

				if ($resultMem) {

					return $resultMem['total'];

				} else {

					$none_memcache = false;

					$result = $search->search(null);
					$mem->set('entity_course', $result) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						return $result['total'];
					} else {

						/* Use memcache */
						$resultMem = $mem->get('entity_course');
						return $resultMem['total'];
					}
				}
				break;
			}
			case 'instructor':
			{
				$search = new Instructor();

				$mem = new Memcached();
				$mem->addServer(env('memcached_server'), env('memcached_port'));

				$resultMem = $mem->get('entity_instructor');

				if ($resultMem) {

					return $resultMem['total'];

				} else {

					$none_memcache = false;

					$result = $search->search(null);
					$mem->set('entity_instructor', $result) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						return $result['total'];
					} else {

						/* Use memcache */
						$resultMem = $mem->get('entity_instructor');
						return $resultMem['total'];
					}
				}
				break;
			}
		}
	}

	public function getResultMemcached(Request $request, $mem, $name, $parameters)
	{
		/*$mem = new Memcached();
		$mem->addServer(env('memcached_server'), env('memcached_port'));*/

		$none_memcache = false;

		if ( sizeof($parameters) > 0)
		{
			$result = new Filter($name,$parameters);
		}
		else {
			$result = new Filter($name, null);
		}

		foreach ($result->data as $data)
		{

			$_request = $request;
			$_request->query->add([$name => $data->name]);

			$counter = $this->searchCounter($_request, $_request['activeTAB']);

			$data->counter = $counter;
		}

		$mem->set($_SERVER['REQUEST_URI'], $result) or $none_memcache = true;

		if ($none_memcache) {
			/* None memcache */
			return $result->data;
		} else {
			/* Use memcache */
			$resultMem = $mem->get($_SERVER['REQUEST_URI']);
			return $resultMem->data;
		}
	}
}