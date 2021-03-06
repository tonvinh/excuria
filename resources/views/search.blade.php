<html lang="en">
<head>
    <title>ExecuriA</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="/vendor/jquery/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/main.js"></script>
    <script type="text/javascript" src="/vendor/modernizr/modernizr.js"></script>
    <link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
<div class="wrap-content">
    <div class="col-md-offset-2 result-content-top">
        <div class="deleteSearchBox">
            <input type="text" id="search-keyword" name="keyword" class="keyword text-box"><div class="clear-btn"></div>
            <input type="button" id="search" value="Search" name="search" class="button-default button-save">
            <div id="suggesstion-box"></div>
        </div>
        <div class="clear-float"></div>

        @if (!isset($_REQUEST['hideHeatherRawTab']) || strtolower($_REQUEST['hideHeatherRawTab']) != 'true')
            <div class="search-bar-left">
                <div class="search-bar result-spacing-right">

                    <a id="entity-provider" href="/search?activeTAB=provider" class="object-entity button-default-bd {{ isset($path) && $path=='provider' ? 'button-active' : '' }}">Providers</a>
                    <a id="entity-course" href="/search?activeTAB=course" class="object-entity button-default-bd {{ isset($path) && $path=='course' ? 'button-active' : '' }}">Courses</a>
                    <a id="entity-instructor" href="/search?activeTAB=instructor" class="object-entity button-default-bd {{ isset($path) &&  $path=='instructor' ? 'button-active' : '' }}">Instructors</a>
                </div>
            </div>
            <div class="clear-float"></div>
            <hr class="break-line result-spacing-right">
        @endif
        @if (!isset($_REQUEST['hideHeatherRawChoosenFilter']) || strtolower($_REQUEST['hideHeatherRawChoosenFilter']) != 'true')
        <div id="tips" class="tips-area">
            {{--<div class="tips-element">Clear all
                <div class="close-item">
                    <span class="close-left"></span><span class="close-right"></span>
                </div>
            </div>--}}
        </div>
        @endif

        <div class="search-bar result-spacing-right last">
        @if (!isset($_REQUEST['hideHeatherRawSort']) || strtolower($_REQUEST['hideHeatherRawSort']) != 'true')
            <div class="search-bar-right">
                <label>Sort by</label>
                <div class="select-parent">
                    <select id="sort" class="select-box sort">
                        @if ( isset($path) && ($path == 'instructor') )
                            <option value="firstName">Instructor (First + LAST)</option>
                            <option value="lastName">Instructor (LAST + First)</option>
                        @endif

                        @if ( isset($path) && ($path == 'provider') )
                            <option value="class">Class Availability</option>
                            <option value="provider">Provider</option>
                        @endif

                        @if ( isset($path) && ($path == 'course') )
                            <option value="day">Date</option>
                            <option value="location">Location</option>
                            <option value="timing">Timing</option>
                        @endif
                    </select>
                    <span class="arrow-icon"></span>
                </div>
                <label>Items per Page</label>
                <div class="select-parent item">
                    <select id="perPage"class="select-box item">
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </select>
                    <span class="arrow-icon"></span>
                </div>
            </div>
        @endif
        </div>
    </div>

    @if (!isset($_REQUEST['hideLeftFilter']) || strtolower($_REQUEST['hideLeftFilter']) != 'true')
    <div class="col-md-2 sider-bar">
        <ul>
            @if ( isset($path) && ($path == 'course') )
                <li class="parent-sider-bar">
                    <span>Free Trial</span>
                    <ul id="freeTrial" class="child-sider-bar">
                        <li>
                            <input type="checkbox" name="checkresult" value="any" checked class="checkbox-default">
                            <lable><span>Any</span></lable>
                        </li>
                        <li>
                            <input type="checkbox" name="checkresult" value="yes" class="checkbox-default">
                            <lable><span>Yes</span></lable>
                        </li>
                        <li>
                            <input type="checkbox" name="checkresult" value="no" class="checkbox-default">
                            <lable><span>No</span></lable>
                        </li>
                    </ul>
                </li>
            @else
                <li class="parent-sider-bar">
                    <span>Class Availability</span>
                    <ul id="classAvailable" class="child-sider-bar">
                        <li>
                            <input type="checkbox" name="checkresult" value="any" checked class="checkbox-default">
                            <lable><span>Any</span></lable>
                        </li>
                        <li>
                            <input type="checkbox" name="checkresult" value="yes" class="checkbox-default">
                            <lable><span>Yes</span></lable>
                        </li>
                        <li>
                            <input type="checkbox" name="checkresult" value="no" class="checkbox-default">
                            <lable><span>No</span></lable>
                        </li>
                    </ul>
                </li>
            @endif

            <li class="parent-sider-bar">
                <span>Country</span>
                <ul id="country" class="child-sider-bar">
                    <li>
                        <input type="checkbox" name="checkresult" value="any" checked class="checkbox-default">
                        <lable><span>Any</span></lable>
                    </li>
                </ul>
            </li>

            <li class="parent-sider-bar">
                <span>City</span>
                <ul id="city" class="child-sider-bar">
                    <li>
                        <input type="checkbox" name="checkresult" value="any" checked class="checkbox-default">
                        <lable><span>Any</span></lable>
                    </li>
                </ul>
            </li>

            <li class="parent-sider-bar">
                <span>Location</span>
                <ul id="location" class="child-sider-bar">
                    <li>
                        <input type="checkbox" name="checkresult" value="any" checked class="checkbox-default">
                        <lable><span>Any</span></lable>
                    </li>
                </ul>
            </li>

            <li class="parent-sider-bar">
                <span>Activity Type</span>
                <ul id="activityType" class="child-sider-bar">
                    <li class="activityType">
                        <input type="checkbox" name="checkresult" value="any" checked class="checkbox-default">
                        <lable><span>Any</span></lable>
                    </li>
                </ul>
            </li>

            <li class="parent-sider-bar">
                <span>Activity Classification</span>
                <ul id="activityClassification" class="child-sider-bar">
                    <li>
                        <input type="checkbox" name="checkresult" value="any" checked class="checkbox-default">
                        <lable><span>Any</span></lable>
                    </li>
                </ul>
            </li>

            <li class="parent-sider-bar">
                <span>Activity</span>
                <ul id="activity" class="child-sider-bar">
                    <li>
                        <input type="checkbox" name="checkresult" value="any" checked class="checkbox-default">
                        <lable><span>Any</span></lable>
                    </li>
                </ul>
            </li>

            @if ( isset($path) && ($path == 'course') )

                <li class="parent-sider-bar">
                    <span>Event Type</span>
                    <ul id="eventType" class="child-sider-bar">
                        <li>
                            <input type="checkbox" name="checkresult" value="any" checked class="checkbox-default">
                            <lable><span>Any</span></lable>
                        </li>
                    </ul>
                </li>

                <li class="parent-sider-bar">
                    <span>Facility</span>
                    <ul id="facility" class="child-sider-bar">
                        <li>
                            <input type="checkbox" name="checkresult" value="any" checked class="checkbox-default">
                            <lable><span>Any</span></lable>
                        </li>
                    </ul>
                </li>

                <li class="parent-sider-bar">
                    <span>Campus</span>
                    <ul id="campus" class="child-sider-bar">
                        <li>
                            <input type="checkbox" name="checkresult" value="any" checked class="checkbox-default">
                            <lable><span>Any</span></lable>
                        </li>
                    </ul>
                </li>

                <li class="parent-sider-bar">
                    <span>Arena</span>
                    <ul id="arena" class="child-sider-bar">
                        <li>
                            <input type="checkbox" name="checkresult" value="any" checked class="checkbox-default">
                            <lable><span>Any</span></lable>
                        </li>
                    </ul>
                </li>
            @endif

            @if ( isset($path) && ($path == 'course' || $path == 'instructor') )
                <li class="parent-sider-bar">
                    <span>Provider</span>
                    <ul id="provider" class="child-sider-bar">
                        <li>
                            <input type="checkbox" name="checkresult" value="any" checked class="checkbox-default">
                            <lable><span>Any</span></lable>
                        </li>
                    </ul>
                </li>
            @endif

            @if ( isset($path) && ($path == 'course') )
                <li class="parent-sider-bar">
                    <span>Instructor</span>
                    <ul id="instructor" class="child-sider-bar">
                        <li>
                            <input type="checkbox" name="checkresult" value="any" checked class="checkbox-default">
                            <lable><span>Any</span></lable>
                        </li>
                    </ul>
                </li>

                <li class="parent-sider-bar">
                    <span>Program</span>
                    <ul id="program" class="child-sider-bar">
                        <li>
                            <input type="checkbox" name="checkresult" value="any" checked class="checkbox-default">
                            <lable><span>Any</span></lable>
                        </li>
                    </ul>
                </li>

                <li class="parent-sider-bar">
                    <span>Day</span>
                    <ul id="day" class="child-sider-bar">
                        <li>
                            <input type="checkbox" name="checkresult" value="any" checked class="checkbox-default">
                            <lable><span>Any</span></lable>
                        </li>
                    </ul>
                </li>

                <li class="parent-sider-bar">
                    <span>Start Time</span>
                    <ul id="timeStart" class="child-sider-bar">
                        <li>
                            <input id="timeStar-any" type="checkbox" name="checkresult" value="any" checked class="checkbox-default">
                            <lable><span>Any</span></lable>
                        </li>
                        <li>
                            <div class="select-parent item time">
                                <select id="timeStartHour" class="select-box item time">
                                    <option></option>
                                    @for($i = 0; $i<= 24; $i++)
                                        <option value="{{ sprintf('%02d', $i) }}">{{ $i . 'h' }}</option>
                                    @endfor
                                </select>
                                <span class="arrow-icon"></span>
                            </div>
                            <div class="select-parent item time">
                                <select id="timeStartMinute" class="select-box item time">
                                    <option></option>
                                    @for($i = 0; $i<= 60; $i++)
                                        <option value="{{ sprintf('%02d', $i) }}">{{ $i . 'm' }}</option>
                                    @endfor
                                </select>
                                <span class="arrow-icon"></span>
                            </div>
                        </li>
                    </ul>
                </li>

                <li class="parent-sider-bar">
                    <span>End Time</span>
                    <ul id="timeEnd" class="child-sider-bar">
                        <li>
                            <input id="timeEnd-any" type="checkbox" name="checkresult" value="any" checked class="checkbox-default">
                            <lable><span>Any</span></lable>
                        </li>
                        <li>
                            <div class="select-parent item time">
                                <select id="timeEndHour" class="select-box item time">
                                    <option></option>
                                    @for($i = 0; $i<= 24; $i++)
                                        <option value="{{ sprintf('%02d', $i) }}">{{ $i . 'h' }}</option>
                                    @endfor
                                </select>
                                <span class="arrow-icon"></span>
                            </div>
                            <div class="select-parent item time">
                                <select id="timeEndMinute" class="select-box item time">
                                    <option></option>
                                    @for($i = 0; $i<= 60; $i++)
                                        <option value="{{ sprintf('%02d', $i) }}">{{ $i . 'm' }}</option>
                                    @endfor
                                </select>
                                <span class="arrow-icon"></span>
                            </div>
                        </li>
                    </ul>
                </li>

                <li class="parent-sider-bar">
                    <span>Gender</span>
                    <ul id="gender" class="child-sider-bar">
                        <li>
                            <input type="checkbox" name="checkresult" value="any" checked class="checkbox-default">
                            <lable><span>Any</span></lable>
                        </li>
                    </ul>
                </li>

                <li class="parent-sider-bar">
                    <span>Generation</span>
                    <ul id="generation" class="child-sider-bar">
                        <li>
                            <input type="checkbox" name="checkresult" value="any" checked class="checkbox-default">
                            <lable><span>Any</span></lable>
                        </li>
                    </ul>
                </li>

                <li class="parent-sider-bar">
                    <span>Age</span>
                    <ul class="child-sider-bar age-item">
                        <li>
                            <input id="age" type="checkbox" name="checkresult" value="any" checked class="checkbox-default">
                            <lable><span>Any</span></lable>
                        </li>
                        <li>
                            <div>
                                <label class="label-age">From</label>
                                <div class="select-parent item time">
                                    <select id="ageFrom" class="select-box item time">
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                    </select>
                                    <span class="arrow-icon"></span>
                                </div>
                            </div>

                            <div>
                                <label class="label-age">To</label>
                                <div class="select-parent item time">
                                    <select id="ageTo" class="select-box item time">
                                        <option>15</option>
                                        <option>25</option>
                                        <option>35</option>
                                        <option>45</option>
                                    </select>
                                    <span class="arrow-icon"></span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

                <li class="parent-sider-bar">
                    <span>Keyword</span>
                    <ul class="child-sider-bar">
                        <li>
                            <input type="text" id="keyword" name="keyword" class="keyword text-box">
                            <div class="select-parent item keyword">
                                <select id="searchKeywordBy" class="select-box item keyword">
                                    <option value="match">Match All</option>
                                    <option value="exact">Exact Phrase</option>
                                </select>
                                <span class="arrow-icon"></span>
                            </div>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </div>
    @endif

    <div class="col-md-10 result-content">
        <div class="col-md-12 result-list-area">
            <div class="result-list result-area-spacing-right">
                <?php $alphaCharacters = [];?>

                <div class="pagination-area result-spacing-right">
                    @if ( isset($debug))
                        <script> console.log("{!! $debug !!}");</script>
                    @endif
                    <input type="hidden" id="total" value="{{ isset($total) ? $total  : 0}}">
                    <input type="hidden" id="totalPage" value="{{ isset($totalPage ) ? $totalPage  : 1}}">
                    <input type="hidden" id="currentPage" value="{{ isset($page) ? $page : 1}}">
                    @if ( isset($page) && sizeof($result) > 0 && $totalPage > 1)
                        <ul id="pagesTop" class="pagination-group">
                            <li class="pagination-item"><a href="?page={{ $page > 1 ? $page -1 : 1 }}" class="pagination-arrow left"></a></li>
                            @for($i = 1; $i <= $totalPage; $i++)
                                @if ($i == $page)
                                    <li class="pagination-item active"><a href="?page={{$i}}"><span>{{ $i }}</span></a></li>
                                @else
                                    <li class="pagination-item"><a href="?page={{$i}}"><span>{{ $i }}</span></a></li>
                                @endif
                            @endfor

                            <li class="pagination-item"><a href="?page={{ $page < $totalPage ? $page+1 : $totalPage }}" class="pagination-arrow right"></a></li>
                        </ul>
                    @endif
                </div>

                <div class="clear-float"></div>

                <div class="result-item-area">
                    <div class="result-item-content custom-scrollbar">
                        @if ( isset($result) && (sizeof($result) > 0))
                            @foreach($result as $data)
                                <div class="result-item-list">
									<?php

									$left = $data['item'];
									$activities = $data['activity'];

									?>
                                    <div class="result-left-list">
                                        <!-- New Logo CR Task -->

                                        <div class="result-right-list">
                                            <div class="result-right-itemparent">
                                                @if ( isset($path) && ($path == 'instructor') )
                                                    <div class="result-right-item">
                                                        <div class="result-border circle">
                                                            <div style="background: url('{{ isset($data['item']['user_logo']) && $data['item']['user_logo'] != null ?  $data['item']['user_logo'] : '' }}');" class="result-img"></div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div style="background: url('{{ isset($data['item']['entity_logo']) && $data['item']['entity_logo'] != null ?  $data['item']['entity_logo'] : '' }}');" class="result-img"></div>
                                        <p class="result-name">
                                            @if ( isset($path) && ($path == 'course') )
												<?php
												$row = '';
												if( isset($data['item']['entity_name']) && $data['item']['entity_name'] != null)
												{
													$row .= '<span title="' . $data['item']['entity_name'] . '">'
														.  ( strlen($data['item']['entity_name']) > 15 ? substr($data['item']['entity_name'], 0, 15) . '...' : $data['item']['entity_name']) . "</span> | " ;
												}
												if( isset($data['item']['curriculum_name']) && $data['item']['curriculum_name'] != null)
												{
													$row .= '<span title="' . $data['item']['curriculum_name'] . '">'
														.  ( strlen($data['item']['curriculum_name']) > 15 ? substr($data['item']['curriculum_name'], 0, 15) . '...' : $data['item']['curriculum_name']) . "</span> | " ;
												}
												if( isset($data['item']['day_name']) && $data['item']['day_name'] != null)
												{
													$row .= $data['item']['day_name'] . " | " ;
												}
												if( isset($data['item']['time_start']) && $data['item']['time_start'] != null)
												{
													$row .= $data['item']['time_start'] . " - " ;
												}
												if( isset($data['item']['time_end']) && $data['item']['time_end'] != null)
												{
													$row .= $data['item']['time_end'] ;
												}

												$content = trim($row);
												echo $content;
												/* Prepare Alpha available data */
												$alphaCharacters [] = strtoupper(substr($content,0,1));

												?>
                                            @elseif ( isset($path) && ($path == 'provider') )
												<?php  if( isset($data['item']['provider_name']) && $data['item']['provider_name'] != null)
												{
													$content  = trim($data['item']['provider_name']);
													echo $content;

													/* Prepare Alpha available data */
													$alphaCharacters [] = strtoupper(substr($content,0,1));
												}
												?>
                                            @elseif ( isset($path) && ($path == 'instructor') )
												<?php
												if( isset($_REQUEST['sortBy']) && $_REQUEST['sortBy'] === 'firstName' &&
													((isset($data['item']['first_name']) && $data['item']['first_name'] != null) || (isset($data['item']['last_name']) && $data['item']['last_name'] != null))
												) {
													$content  = trim($data['item']['first_name'] . " " . strtoupper($data['item']['last_name']));
													echo $content;
												}
												else if( isset($_REQUEST['sortBy']) && $_REQUEST['sortBy'] === 'lastName' &&
													((isset($data['item']['first_name']) && $data['item']['first_name'] != null) || (isset($data['item']['last_name']) && $data['item']['last_name'] != null))
												) {

													$content  = trim(strtoupper($data['item']['last_name']) . ' ' . $data['item']['first_name']);
													echo $content;
												}
												else {
													$content  = trim($data['item']['first_name'] . " " . strtoupper($data['item']['last_name']));
													echo $content;
												}

												/* Prepare Alpha available data */
												$alphaCharacters [] = strtoupper(substr($content,0,1));

												?>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="result-right-list">
										<?php
										foreach ($activities as $activity){
										?>
                                        <div class="result-right-itemparent">
                                            <div class="result-right-item">
                                                <div class="result-border">
                                                    <div style="background: url({{$activity['logo']}});" class="result-img"></div>
                                                </div>
                                            </div>
                                        </div>
										<?php } ?>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="enpty-result">No Search Results</div>
                        @endif
                    </div>

                    @if (!isset($_REQUEST['hideRightAplhabet']) || strtolower($_REQUEST['hideRightAplhabet']) != 'true')
                    <div class="result-item-alpha">
                        <ul class="alpha-filter">
                            <li class="{{ in_array("A", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">A</li>
                            <li class="{{ in_array("B", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">B</li>
                            <li class="{{ in_array("C", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">C</li>
                            <li class="{{ in_array("D", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">D</li>
                            <li class="{{ in_array("E", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">E</li>
                            <li class="{{ in_array("F", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">F</li>
                            <li class="{{ in_array("G", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">G</li>
                            <li class="{{ in_array("H", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">H</li>
                            <li class="{{ in_array("I", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">I</li>
                            <li class="{{ in_array("J", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">J</li>
                            <li class="{{ in_array("K", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">K</li>
                            <li class="{{ in_array("L", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">L</li>
                            <li class="{{ in_array("M", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">M</li>
                            <li class="{{ in_array("N", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">N</li>
                            <li class="{{ in_array("O", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">O</li>
                            <li class="{{ in_array("p", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">P</li>
                            <li class="{{ in_array("Q", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">Q</li>
                            <li class="{{ in_array("R", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">R</li>
                            <li class="{{ in_array("S", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">S</li>
                            <li class="{{ in_array("T", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">T</li>
                            <li class="{{ in_array("U", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">U</li>
                            <li class="{{ in_array("V", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">V</li>
                            <li class="{{ in_array("W", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">W</li>
                            <li class="{{ in_array("X", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">X</li>
                            <li class="{{ in_array("Y", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">Y</li>
                            <li class="{{ in_array("Z", $alphaCharacters) === true ? 'alapha-data' : 'alpha-nodata' }}">Z</li>
                        </ul>
                    </div>
                    @endif

                </div>

                <div class="pagination-area result-spacing-right bottom">
                    @if ( isset($page) && sizeof($result) > 0)
                        <ul id="pagesBottom" class="pagination-group">
                            <li class="pagination-item"><a href="?page={{ $page > 1 ? $page -1 : 1 }}" class="pagination-arrow left"></a></li>
                            @for($i = 1; $i <= $totalPage; $i++)
                                @if ($i == $page)
                                    <li class="pagination-item active"><a href="?page={{$i}}"><span>{{ $i }}</span></a></li>
                                @else
                                    <li class="pagination-item"><a href="?page={{$i}}"><span>{{ $i }}</span></a></li>
                                @endif
                            @endfor

                            <li class="pagination-item"><a href="?page={{ $page < $totalPage ? $page+1 : $totalPage }}" class="pagination-arrow right"></a></li>
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="loading" id="loading"></div>
</body>
</html>