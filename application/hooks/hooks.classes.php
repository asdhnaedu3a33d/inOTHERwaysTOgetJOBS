<?php

/**
 *
 *
 * ref: http://codefury.net/2009/11/enable-site-wide-profiling-with-codeigniter/
 */
class ProfilerEnabler
{
	function EnableProfiler()
	{
		$CI = &get_instance();
		$CI->output->enable_profiler( config_item('enable_profiling') );
	}
}

?>