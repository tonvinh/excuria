<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteVistor extends Model
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'site_visitor';

    protected $fillable = array('ip_address', 'country_isp_name', 'country_id','city_isp_name','city_id');

    protected $primaryKey = 'site_visitor_id';

	public $timestamps = false; /* skip timestamp update_at, create_at fields on Model */
}
