<?php
/**
 * Fami 统一入口
 */

/**
// start profiling
xhprof_enable();
 */

require_once dirname(__FILE__) . '/../init.php';

//装载你的接口
DI()->loader->addDirs('Apps/Fami');

/** ---------------- 响应接口请求 ---------------- **/

$api = new PhalApi();
$rs = $api->response();
$rs->output();

/**
// stop profiler
$xhprof_data = xhprof_disable();

$XHPROF_ROOT = '/path/to/xhprof';
include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";

// save raw data for this profiler run using default
// implementation of iXHProfRuns.
$xhprof_runs = new XHProfRuns_Default();

// save the run under a namespace "xhprof_foo"
$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo");

echo "http://<xhprof-ui-address>/index.php?run=$run_id&source=xhprof_foo\n";
 */
