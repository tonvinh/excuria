<?php $alphaCharacters = [];?>
<div class="pagination-area result-spacing-right">
    @if ( isset($debug))
        <script> console.log("{!! $debug !!}");</script>
    @endif
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
    <input type="hidden" id="total" value="{{ isset($total) ? $total  : 0}}">
    <input type="hidden" id="totalPage" value="{{ isset($totalPage ) ? $totalPage  : 1}}">
    <input type="hidden" id="currentPage" value="{{ isset($page) ? $page : 1}}">
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