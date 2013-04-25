<?php
/**
 * job template
 *
 * Website jobs page
 *
 * PHP version 5
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author        Wan Qi Chen <kami@kamisama.me>
 * @copyright     Copyright 2012, Wan Qi Chen <kami@kamisama.me>
 * @link          http://resqueboard.kamisama.me
 * @package       resqueboard
 * @subpackage	  resqueboard.template
 * @since         1.0.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

?>

	<ul class="stats unstyled clearfix split-four">
		<li>
			<a href="/jobs/view">
				<strong data-status="processed"><?php echo number_format($stats[ResqueBoard\Lib\ResqueStat::JOB_STATUS_COMPLETE]) ?></strong>
				<b>Processed</b> jobs
			</a>
		</li>
		<li><div>
			<strong class="warning" data-status="failed"><?php echo round($stats[ResqueBoard\Lib\ResqueStat::JOB_STATUS_FAILED]*100/$stats[ResqueBoard\Lib\ResqueStat::JOB_STATUS_COMPLETE], 2) ?>%</strong>
			<b><?php echo number_format($stats[ResqueBoard\Lib\ResqueStat::JOB_STATUS_FAILED]) ?> failed</b> jobs</div>
		</li>
		<li>
			<a href="/jobs/pending">
				<strong><?php echo '00x00' ?></strong>
				<b>Pending</b> jobs
			</a>
		</li>
		<li>
			<a href="/jobs/scheduled">
				<strong><?php echo number_format($stats[ResqueBoard\Lib\ResqueStat::JOB_STATUS_SCHEDULED]) ?></strong>
				<b>Scheduled</b> jobs
			</a>
		</li>
	</ul>


	<div id="jobs-activities-graph">

	</div>

	<div class="row">
	<div class="bloc">
		<div class="span5">
			<h2><a href="/jobs/distribution/class" title="View jobs distribution by classes">Distribution by classes</a></h2>

			<div id="jobRepartition">
				<?php
					$pieDatas = array();
					$total = 100;
					foreach($jobsRepartitionStats->stats as $stat) {
						if ($stat['percentage'] >= 15) {
							$pieDatas[] = array('name' => $stat['_id'], 'count' => $stat['percentage']);
							$total -= $stat['percentage'];
						}
					}
					if (count($pieDatas) < count($jobsRepartitionStats->stats)) {
						$pieDatas[] = array('name' => 'Other', 'count' => $total);
					}

					//$diff = $jobsStats->oldest === null ? 0 : date_diff($jobsStats->oldest, new DateTime())->format('%a');
					//$jobsDailyAverage = empty($diff) ? 0 : round($jobsStats->total / $diff);

					echo "<script type='text/javascript'>";
					echo "$(document).ready(function() { ";
						echo "pieChart('jobRepartition', " . $jobsRepartitionStats->total . ", " . json_encode($pieDatas) . ");";
					echo "})</script>";
				?>
			</div>

			<table class="table table-condensed table-hover table-greyed">
				<thead>
					<tr>
						<th class="name">Job class</th>
						<th>Count</th>
						<th>Distribution</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$total = 0;
					foreach ($jobsRepartitionStats->stats as $stat) {
						echo '<tr>';
						echo '<td class="name">' . $stat['_id'] . '</td>';
						echo '<td>' . number_format($stat['value']) . '</td>';
						echo '<td><div style="position:relative;">';
						echo '<span class="chart-bar" style="width:' . $stat['percentage'] . '%;"></span>';
						echo '<b>' . ($stat['percentage'] != 0 ? '' : '~') . $stat['percentage'] . '%</b></div></div></td>';
						echo '</tr>';

						$total += $stat['value'];
					}

					if ($total < $jobsRepartitionStats->total) {
						$p = round(($jobsRepartitionStats->total - $total) / $jobsRepartitionStats->total * 100, 2);

						echo '<tr>';
						echo '<td class="name">Other</td>';
						echo '<td>' . number_format($jobsRepartitionStats->total - $total) . '</td>';
						echo '<td><div style="position:relative;">';
						echo '<span class="chart-bar" style="width:' . $p . '%;"></span>';
						echo '<b>' . ($p != 0 ? '' : '~') . $p . '%</b></div></div></td>';
						echo '</tr>';
					}

					if ($jobsRepartitionStats->total > 0) {
						echo '<tr class="info">';
						echo '<td>Total</td>';
						echo '<td>' . number_format($jobsRepartitionStats->total) . '</td>';
						echo '<td>100%</td>';
						echo '</tr>';
					} else {
						echo '<tr class="info">';
						echo '<td colspan=3>No jobs found</td>';
						echo '</tr>';
					}

					?>
				</tbody>
			</table>
			<a href="jobs/distribution">View all</a>
		</div>

		<div class="span6">

			<h2>Queues <span class="badge badge-info queues-count"><?php echo count($queues)?></span></h2>

			<?php
			    echo '<table class="table table-condensed table-greyed"><thead>'.
				    '<tr><th class="name">Name</th><th>Pending jobs</th><th>Total jobs</th><th>Workers</th></tr></thead><tbody>';

				if (!empty($queues)) {

					$totalPendingJobs = 0;
					array_walk($queues, function($q) use (&$totalPendingJobs) { $totalPendingJobs += $q['jobs']; });

					foreach ($queues as $queueName => $queueStat) {
						if ($queueName === ResqueScheduler\ResqueScheduler::QUEUE_NAME) {
							continue;
						} ?>
					<tr>
						<td class="name"><?php echo $queueName?></td>
						<td>
							<div style="position:relative;">
								<span class="chart-bar" style="width:<?php echo $totalPendingJobs === 0 ? 0 : round($queueStat['jobs'] * 100 / $totalPendingJobs, 2) ?>%;"></span>
							</div>
							<a href="/jobs/pending?queue=<?php echo $queueName ?>"><?php echo number_format($queueStat['jobs']); ?></a>
						</td>
						<td><a href="/jobs/view?queue=<?php echo $queueName ?>">00x00</a></td>
						<td>00x00</td>

					</tr>
				<?php
				    }
				}
				echo '</tbody></table>';
			 ?>


			<h2>Latest activities</h2>
			<div id="latest-jobs-graph"></div>
			<div id="latest-jobs-list">
				<p>Click on the graph to show the associated jobs</p>
			</div>
			<script id="latest-jobs-list-tpl" type="text/x-jsrender">
				<li class="accordion-group">
					<div class="accordion-heading" data-toggle="collapse" data-target="#{{>id}}">
						<div class="accordion-toggle">
							<span class="job-status-icon" data-event="tooltip" data-original-title="Job scheduled">
							<img src="/img/job_scheduled.png" title="Job scheduled" height="24" width="24"></span>

							<h4>#{{>id}}</h4>

							<small>Performing <code>{{>class}}</code> in
							<span class="label label-success">{{>queue}}</span></small>

						</div>
					</div>
					<div class="collapse accordion-body" id="{{>id}}">
						<div class="accordion-inner">
							<p><i class="icon-time"></i> <b>Added on </b>{{>created}}</p>
							<pre class="job-args"><code class="language-php">{{>args}}</code></pre>
						</div>
					</div>
				</li>
			</script>
		</div>

	</div>
</div>

</div>
