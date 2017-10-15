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
				$mem->addServer("127.0.0.1", 11211);
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
				//exit;
				$search = new Course();
				$conditions = $search->prepare($parameters);

				$page = $request->input('page');

				$mem = new Memcached();
				$mem->addServer("127.0.0.1", 11211);

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
				$mem->addServer("127.0.0.1", 11211);
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
				$mem->addServer("127.0.0.1", 11211);
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
				$mem->addServer("127.0.0.1", 11211);
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
				$mem->addServer("127.0.0.1", 11211);
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

	public function prepareFilter(Request $request, $name)
	{
		$mem = new Memcached();
		$mem->addServer("127.0.0.1", 11211);

		switch ($name) {
			case 'country': {

				$resultMem = $mem->get($name);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$mem->set($name, new Filter('country', null)) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = new Filter('country', null);
						return Response::json($result->data);
					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem->data);
					}
				}
				break;
			}
			case 'city': {

				$resultMem = $mem->get($name);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$mem->set($name, new Filter('city', ['country' => $request->input('country')])) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = new Filter('city', ['country' => $request->input('country')]);
						return Response::json($result->data);
					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem->data);
					}
				}
				break;
			}
			case 'location': {

				$resultMem = $mem->get($name);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$mem->set($name, new Filter('location', ['city' => $request->input('country')])) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = new Filter('location', ['city' => $request->input('country')]);
						return Response::json($result->data);
					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem->data);
					}
				}
				break;
			}
			case 'activityType': {

				$resultMem = $mem->get($name);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$mem->set($name, new Filter('activityType',null)) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = new Filter('activityType',null);
						return Response::json($result->data);
					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem->data);
					}
				}
				break;
			}
			case 'activityClassification': {

				$resultMem = $mem->get($name);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$mem->set($name, new Filter('activityClassification',['activityType'=>$request->input('activityType')])) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = new Filter('activityClassification',['activityType'=>$request->input('activityType')]);
						return Response::json($result->data);
					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem->data);
					}
				}
				break;
			}
			case 'activity': {

				/* Use memcache */
				$resultMem = $mem->get($name);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$mem->set($name, new Filter('activity',['activityClassification'=>$request->input('activityClassification')])) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = new Filter('activity',['activityClassification'=>$request->input('activityClassification')]);
						return Response::json($result->data);
					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem->data);
					}
				}
				break;
			}
			case 'eventType': {

				$resultMem = $mem->get($name);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$mem->set($name, new Filter('eventType',null)) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = new Filter('eventType',null);
						return Response::json($result->data);
					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem->data);
					}
				}
				break;
			}
			case 'facility': {

				$resultMem = $mem->get($name);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$mem->set($name, new Filter('facility',null)) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = new Filter('facility',null);
						return Response::json($result->data);
					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem->data);
					}
				}
				break;
			}
			case 'campus': {

				$resultMem = $mem->get($name);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$mem->set($name, new Filter('campus',null)) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = new Filter('campus',null);
						return Response::json($result->data);
					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem->data);
					}
				}
				break;
			}
			case 'arena': {

				$resultMem = $mem->get($name);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$mem->set($name, new Filter('arena',null)) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = new Filter('arena',null);
						return Response::json($result->data);
					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem->data);
					}
				}
				break;
			}
			case 'provider': {

				$resultMem = $mem->get($name);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$mem->set($name, new Filter('provider',null)) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = new Filter('provider',null);
						return Response::json($result->data);
					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem->data);
					}
				}
				break;
			}
			case 'instructor': {

				$resultMem = $mem->get($name);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$mem->set($name, new Filter('instructor',null)) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = new Filter('instructor',null);
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

				$resultMem = $mem->get($name);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$mem->set($name, new Filter('program',null)) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = new Filter('program',null);
						return Response::json($result->data);
					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem->data);
					}
				}
				break;
			}			
			case 'gender': {
				/* None memcache */
				/*$result = new Filter('gender',null);
				return Response::json($result->data);*/

				/* Use memcache */
				$resultMem = $mem->get($name);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$mem->set($name, new Filter('gender',null)) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = new Filter('gender',null);
						return Response::json($result->data);
					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem->data);
					}
				}
				break;
			}
			case 'generation': {

				$resultMem = $mem->get($name);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$mem->set($name, new Filter('generation',null)) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = new Filter('generation',null);
						return Response::json($result->data);
					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem->data);
					}
				}
				break;
			}
			case 'day': {

				$resultMem = $mem->get($name);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$mem->set($name, new Filter('day',null)) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = new Filter('day',null);
						return Response::json($result->data);
					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem->data);
					}
				}
				break;
			}
			case 'timeStart': {

				$resultMem = $mem->get($name);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$mem->set($name, new Filter('timeStart',null)) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = new Filter('timeStart',null);
						return Response::json($result->data);
					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem->data);
					}
				}
				break;
			}			
			case 'timeEnd': {
				/* None memcache */
				/*$result = new Filter('timeEnd',null);
				return Response::json($result->data);*/

				/* Use memcache */
				$resultMem = $mem->get($name);

				if ($resultMem) {

					return Response::json($resultMem->data);

				} else {

					$none_memcache = false;

					$mem->set($name, new Filter('timeEnd',null)) or $none_memcache = true;

					if ($none_memcache) {
						/* None memcache */
						$result = new Filter('timeEnd',null);
						return Response::json($result->data);
					} else {

						/* Use memcache */
						$resultMem = $mem->get($name);
						return Response::json($resultMem->data);
					}
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
				$mem->addServer("127.0.0.1", 11211);

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
				$mem->addServer("127.0.0.1", 11211);

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
				$mem->addServer("127.0.0.1", 11211);

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
}