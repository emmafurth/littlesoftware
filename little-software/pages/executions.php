<?php
/**
 * Little Software Stats
 *
 * An open source program that allows developers to keep track of how their software is being used
 *
 * @package		Little Software Stats
 * @author		Little Apps
 * @copyright           Copyright (c) 2011, Little Apps
 * @license		http://www.gnu.org/licenses/gpl.html GNU General Public License v3
 * @link		http://little-apps.org
 * @since		Version 0.1
 */

if ( !defined( 'LSS_LOADED' ) ) die( 'This page cannot be loaded directly' );

require_once '../inc/main.php';

// Make sure user is logged in
verify_user( );

// Create date range
$date_range = create_date_range_array( $start_time, $end_time );

$start_point = $date_range[0] * 1000;

$area_chart_data_execs = array();
$chart_data_by_period = array();
$chart_data_total = 0;

for ( $i = 0; $i < count( $date_range ) - 1 ;$i++ ) {
    $start = $date_range[$i];
    $end = $date_range[$i + 1];
    
    $total_for_period = $db->select_sessions( $app_id, $app_ver, $start, $end, '*', false, true );
    
    $area_chart_data_execs[] = $total_for_period;
    
    $chart_data_by_period[] = array(
        'start' => $start,
        'end' => $end,
        'total' => $total_for_period
    );
    
    $chart_data_total += $total_for_period;
}

// Get lowest & highest date
$query = "SELECT MIN( StartApp ) AS lowest, MAX( StopApp ) AS highest FROM `".$db->prefix."sessions` WHERE `ApplicationId` = '".$app_id."' ". ( ( $app_ver != 'all') ? ( "AND `ApplicationVersion` = '" . $app_ver . "' " ) : ( '' ) );
$db->execute_sql( $query );
$db->array_result();

$min_date = intval( $db->arrayed_result['lowest'] );
$max_date = intval( $db->arrayed_result['highest'] );

// Get execution stats
$total_execs = $db->select_sessions( $app_id, $app_ver, $min_date, $max_date, '*', false, true );
$period_execs = $db->select_sessions( $app_id, $app_ver, $start_time, $end_time, '*', false, true );

$day_execs_total = 0;
$month_execs_total = 0;

$date_range_total_day = create_date_range_array( $min_date, $max_date, 'day' );
$date_range_total_month = create_date_range_array( $min_date, $max_date, 'month' );

$day_execs_total = $db->select_sessions( $app_id, $app_ver, $date_range_total_day[0], end( $date_range_total_day ), '*', false, true );
$month_execs_total = $db->select_sessions( $app_id, $app_ver, $date_range_total_month[0], end( $date_range_total_month ), '*', false, true );

$day_execs = 0;
$month_execs = 0;

if ( count( $date_range_total_day ) - 1 > 0 )
    $day_execs = ( $day_execs_total / ( count( $date_range_total_day ) - 1 ) );
if ( count( $date_range_total_month ) - 1 > 0 )
    $month_execs = ( $month_execs_total / ( count( $date_range_total_month ) - 1 ) );

// Get percentage difference from last month
$last_month = $db->select_sessions( $app_id, $app_ver, $start_time - ( 30 * 24 * 3600 ), $start_time, '*', false, true );

$percentage_increase = calculate_percentage_increase( $last_month, $period_execs );

$percentage_increase_str = $percentage_increase . '%';
if ( $percentage_increase > 0 )
    $percentage_increase_str = '+' . $percentage_increase_str;
?>
<script type="text/javascript">
var chart;
$(document).ready(function() {
        chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'chart_div',
                    defaultSeriesType: 'line',
                    height: 200
                },
                title: {
                    text: '<?php echo __( 'Statistics for ' ) . date( "F j, Y", $start_time ) . ' to ' . date( "F j, Y", $end_time ); ?>',
                    x: -20 //center
                },
                plotOptions: {
                    series: {
                        pointStart: <?php printf( '%d000', $start_point ); ?>,
                        pointInterval: <?php echo $tick_interval * 1000; ?> 
                    }
                },
                xAxis: {
                    type: 'datetime',
                    allowDecimals: false
                },
                yAxis: {
                    title: ''
                },
                legend: {
                    layout: 'horizontal',
                    align: 'right',
                    verticalAlign: 'top',
                    floating: true,
                    x: -10,
                    y: -10,
                    borderWidth: 0
                },
                series: <?php echo json_encode( array( array( 'name' => 'Executions','data' => $area_chart_data_execs ) ) ); ?>
        });
});
</script>

<!--  start page-heading -->
<div id="page-heading">
    <h1><?php _e( 'Executions' ); ?></h1>
</div>
<!-- end page-heading -->

<!-- start stats graph -->
<table id="content-table" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tbody>
        <tr>
            <th class="sized" rowspan="3"><img height="300" width="20" alt="" src="<?php file_url('/images/shared/side_shadowleft.jpg'); ?>"></th>
            <th class="topleft"></th>
            <td id="tbl-border-top">&nbsp;</td>
            <th class="topright"></th>
            <th class="sized" rowspan="3"><img height="300" width="20" alt="" src="<?php file_url('/images/shared/side_shadowright.jpg'); ?>"></th>
        </tr>
        <tr>
            <td id="tbl-border-left"></td>
            <td>
                <div id="content-table-inner" style="height: 200px">
                    <div id="chart_div"></div>
                </div>
            </td>
            <td id="tbl-border-right"></td>
        </tr>
        <tr>
            <th class="sized bottomleft"></th>
            <td id="tbl-border-bottom">&nbsp;</td>
            <th class="sized bottomright"></th>
        </tr>
    </tbody>
</table>
<!-- end stats graph -->

<div class="clear">&nbsp;</div>
<div class="clear">&nbsp;</div>

<div class="contentcontainers">
    <!-- Overview Start -->
    <div class="contentcontainer med left">
        <div class="headings alt">
            <h2><?php _e( 'Overview' ); ?></h2>
        </div>
        <div class="contentbox">
            <div>
                <p><span class="total"><?php echo $total_execs; ?></span> <?php _e( 'executions' ); ?></p>
                <p><span class="total"><?php echo $period_execs; ?></span> <?php _e( 'executions in the period' ); ?></p>
                <p><span class="total"><?php echo round( $month_execs, 2); ?></span> <?php _e( 'executions per month (average)' ); ?></p>
                <p><span class="total"><?php echo round( $day_execs, 2); ?></span> <?php _e( 'executions per day (average)' ); ?></p>
            </div>
        </div>
    </div>
    <!-- Overview End -->

    <!-- Last Month Period Start -->
    <div class="contentcontainer sml right">
        <div class="headings alt">
            <h2><?php _e( 'Last Month Period' ); ?></h2>
        </div>
        <div class="contentbox" style="text-align: center; padding-top: 30px;">
            <span class="<?php echo ( ( $percentage_increase > 0 ) ? ( 'green' ) : ( 'red' ) ); ?>" style="font-weight: bold; font-size: 52px !important;"><?php echo $percentage_increase_str; ?></span>
            <br />
            <strong><?php _e( 'last month period' ); ?></strong>
        </div>
    </div>
    <!-- Last Month Period End -->
    
    <div style="clear: both"></div>
    
    <!-- Executions Chart Data Start -->
    <div class="contentcontainer">
        <div class="headings alt">
            <h2 class="left"><?php _e( 'Executions Chart Data' ); ?></h2>
        </div>
        <div class="contentbox">
            <table>
                <?php foreach ( $chart_data_by_period as $chart_data ) : ?>
                <tr>
                    <td>
                        <?php
                            if ( $graph_by == 'day' )
                                echo date( 'l, F j, o', $chart_data['start'] );
                            else 
                                echo date( 'l, F j, o', $chart_data['start'] ) . ' to ' . date( 'l, F j, o', $chart_data['end'] );
                        ?>
                    </td>
                    <td width="900">
                        <?php $percent = calculate_percent( $chart_data['total'], $chart_data_total ); ?>
                        <div class="usagebox">
                            <div class="lowbar" style="width: <?php echo $percent . '%'; ?>"></div>
                        </div>
                    </td>
                    <td><strong><?php echo $percent; ?>% (<?php echo $chart_data['total']; ?>)<strong></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <!-- Executions Chart Data End -->
</div>
	