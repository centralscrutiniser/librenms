<?php

$rrd_filename = rrd_name($device['hostname'], 'sgos_server_connections_active');

require 'includes/graphs/common.inc.php';

$ds = 'server_conn_active';

$colour_area = '9999cc';
$colour_line = 'ff0000';

$colour_area_max = '9999cc';

//$graph_max = 1;
//$graph_min = 0;
$scale_min = '0';

$unit_text = 'Active Conn';

require 'includes/graphs/generic_simplex.inc.php';

