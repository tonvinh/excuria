
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
        @if ( isset($result))
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
									$row .= $data['item']['entity_name'] . " | " ;
								}
								if( isset($data['item']['curriculum_name']) && $data['item']['curriculum_name'] != null)
								{
									$row .= $data['item']['curriculum_name'] . " | " ;
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
								echo trim($row);
								?>
                            @elseif ( isset($path) && ($path == 'provider') )
								<?php  if( isset($data['item']['provider_name']) && $data['item']['provider_name'] != null)
								{
									echo trim($data['item']['provider_name']);
								}
								?>
                            @elseif ( isset($path) && ($path == 'instructor') )
								<?php if( (isset($data['item']['first_name']) && $data['item']['first_name'] != null) || (isset($data['item']['last_name']) && $data['item']['last_name'] != null)) {
									echo trim($data['item']['first_name'] . " " . $data['item']['last_name']);
								}
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
        @endif
    </div>

    @if (!isset($_REQUEST['HideRightAplhabet']) || strtolower($_REQUEST['HideRightAplhabet']) != 'true')
        <div class="result-item-alpha">
            <ul class="alpha-filter">
                <li>A</li>
                <li>B</li>
                <li>C</li>
                <li>D</li>
                <li>E</li>
                <li>F</li>
                <li>G</li>
                <li>H</li>
                <li>I</li>
                <li>J</li>
                <li>K</li>
                <li>L</li>
                <li>M</li>
                <li>N</li>
                <li>O</li>
                <li>P</li>
                <li>Q</li>
                <li>R</li>
                <li>S</li>
                <li>T</li>
                <li>U</li>
                <li>V</li>
                <li>W</li>
                <li>X</li>
                <li>Y</li>
                <li>Z</li>
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