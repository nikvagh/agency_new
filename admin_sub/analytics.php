<?php 	
	$page = "analytics";
	$page_selected = "analytics";
	include('header.php');

	// if ($row = @mysql_fetch_array ($query, MYSQL_ASSOC)) {
	// 	echo "ff".$row;
	// 	// $query = "UPDATE agency_messages SET deleted='1' WHERE message_id='$message_id'";
	// 	// if(mysql_query($query)){
	// 	// 	$res = true;
	// 	// }
	// 	// echo '<td colspan="4">message deleted</td>';
	// }

?>
 
    <div id="page-wrapper">
		<div class="row" id="content">

			<?php if(array_key_exists('view_sales_report', $user_privilege)){ ?>

				<section class="col-lg-6">
					<div class="card">
						<h4>SALES</h4>
						<canvas id="graph"></canvas>

						<button id="year_btn" class="btn btn-sm btn-primary">Years</button>
						<button id="month_btn" class="btn btn-sm btn-danger">Month</button>
						<button id="week_btn" class="btn btn-sm btn-warning">Week</button>
						<button id="day_btn" class="btn btn-sm btn-success">Day</button>
					</div>
				</section>

			<?php } ?>

			<div class="col-lg-3 col-xs-6">
	          <!-- small box -->
	          <div class="small-box bg-aqua">
	            <div class="inner">
	            	<?php
        				$sql = "SELECT count(user_id) as total_failed FROM agency_profiles WHERE account_type='client'";
						$total_client = mysql_result(mysql_query ($sql),0);
					?>
	              <h3><?php echo $total_client; ?></h3>
	              <p>Casting Director </p>
	            </div>
	            <div class="icon">
	              <i class="fa fa-user"></i>
	            </div>
	            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
	          </div>
	        </div>

	        <div class="col-lg-3 col-xs-6">
	          <!-- small box -->
	          <div class="small-box bg-green">
	            <div class="inner">
	              	<?php
        				$sql = "SELECT count(user_id) as total_failed FROM agency_profiles WHERE account_type='talent'";
						$totalREG = mysql_result(mysql_query ($sql),0);
					?>
	              <h3><?php echo $totalREG; ?></h3>
	              <p>Talent</p>
	            </div>
	            <div class="icon">
	              <i class="fa fa-user"></i>
	            </div>
	            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
	          </div>
	        </div>

	        <div class="col-lg-3 col-xs-6">
	          <!-- small box -->
	          <div class="small-box bg-purple">
	            <div class="inner">
	              	<?php
        				$sql = "SELECT count(user_id) as total_failed FROM agency_profiles WHERE account_type='talent_manager'";
						$total_t_manager = mysql_result(mysql_query ($sql),0);
					?>
	              <h3><?php echo $total_t_manager; ?></h3>
	              <p>Talent manager</p>
	            </div>
	            <div class="icon">
	              <i class="fa fa-user"></i>
	            </div>
	            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
	          </div>
	        </div>

	        <div class="col-lg-3 col-xs-6">
	          <!-- small box -->
	          <div class="small-box bg-yellow">
	            <div class="inner">
	            	<?php
        				$sql = "SELECT count(user_id) as total_failed FROM agency_profiles WHERE account_type='talent' AND payAuthorized ='1' ";
						$totaldue = mysql_result(mysql_query ($sql),0);
					?>
	              <h3><?php echo $totaldue; ?></h3>
	              <p>Payment Due</p>
	            </div>
	            <div class="icon">
	              <i class="fa fa-dollar"></i>
	            </div>
	            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
	          </div>
	        </div>

	        <div class="col-lg-3 col-xs-6">
	          <!-- small box -->
	          <div class="small-box bg-red">
	            <div class="inner">
            		<?php
            			$sql = "SELECT count(user_id) as total_failed FROM agency_profiles WHERE account_type='talent' AND payFailed='1'";
						$totalFailed = mysql_result(mysql_query ($sql),0);
					?>
	              <h3><?php echo $totalFailed; ?></h3>
	              <p>Payment Failed</p>
	            </div>
	            <div class="icon">
	              <i class="fa fa-dollar"></i>
	            </div>
	            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
	          </div>
	        </div>

		</div>

		<!-- <div class="row">
			<section class="col-lg-7">
				<div class="card">
					<h4>SALES</h4>
					<canvas id="canvas_bar_chart"></canvas>
				</div>
			</section>
		</div> -->

		<div class="row">
			<!-- <section class="col-lg-7">
				<div class="card">
					<h4>SALES</h4>
					<canvas id="canvas_line_chart"></canvas>
				</div>

				<button id="randomizeData">Randomize Data</button>
				<button id="addDataset">Add Dataset</button>
				<button id="removeDataset">Remove Dataset</button>
				<button id="addData">Add Data</button>
				<button id="removeData">Remove Data</button>
			</section> -->

			<!-- <section class="col-lg-7">
				<div class="card">
					<h4>SALES</h4>
					<canvas id="graph"></canvas>

					<button id="year_btn" class="btn btn-sm btn-primary">Years</button>
					<button id="month_btn" class="btn btn-sm btn-danger">Month</button>
					<button id="week_btn" class="btn btn-sm btn-warning">Week</button>
					<button id="day_btn" class="btn btn-sm btn-success">Day</button>
				</div>
			</section> -->
			<br/>
			<section class="col-lg-6">
				<div class="alert alert-success" role="alert" id="alert-success-form" style="display:none"></div>
				<div class="alert alert-danger" role="alert" id="alert-danger-form" style="display:none"></div>
				<div class="box">
		            <div class="box-header">
		              <i class="fa fa-envelope"></i>

		              <h3 class="box-title">Email To All Failed Payments Accounts</h3>
		              <!-- tools box -->
		              <!-- <div class="pull-right box-tools">
		                <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
		                  <i class="fa fa-times"></i></button>
		              </div> -->
		              <!-- /. tools -->
		            </div>
		            <div class="box-body">
		              <form action="#" method="post" id="failed_notification">
		                <!-- <div class="form-group">
		                  <input type="email" class="form-control" name="emailto" placeholder="Email to:">
		                </div> -->
		                <div class="form-group">
		                  <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject">
		                </div>
		                <div>
		                  <textarea class="textarea" name="message" id="message" placeholder="Message" style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
		                </div>

		                <input type="button" name="failed_notification_submit" class="pull-right btn btn-default" id="failed_notification_btn" value="Submit"/>
		              </form>
		            </div>
		            <!-- <div class="box-footer clearfix">
		              	<input type="submit" name="failed_notification_submit" class="pull-right btn btn-default" id="failed_notification_btn" />
		            </div> -->
		        </div>
			</section>

		</div>

	</div>

<?php include('footer_js.php'); ?>

<!-- <script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script> -->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<!-- <script type="text/javascript" src="../dashboard/assets/Chart-js/samples/utils.js"></script> -->

<script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script>

<!-- <script>
	var xAxis = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	var config_sales = {
		type: 'line',
		data: {
			labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
			datasets: [{
				label: 'SALES',
				backgroundColor: 'rgb(255, 99, 132)',
				borderColor: 'rgb(255, 99, 132)',
				data: [
					5,7,82,2,85,30,8,55,37,20,29,30
					// randomScalingFactor(),
					// randomScalingFactor(),
					// randomScalingFactor(),
					// randomScalingFactor(),
					// randomScalingFactor(),
					// randomScalingFactor(),
					// randomScalingFactor()
				],
				fill: true,
			}, 
			// {
			// 	label: 'My Second dataset',
			// 	fill: false,
			// 	backgroundColor: window.chartColors.blue,
			// 	borderColor: window.chartColors.blue,
			// 	data: [
			// 		randomScalingFactor(),
			// 		randomScalingFactor(),
			// 		randomScalingFactor(),
			// 		randomScalingFactor(),
			// 		randomScalingFactor(),
			// 		randomScalingFactor(),
			// 		randomScalingFactor()
			// 	],
			// }
			]
		},
		options: {
			responsive: true,
			// title: {
			// 	display: true,
			// 	text: 'Chart.js Line Chart'
			// },
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			scales: {
				x: {
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Month'
					}
				},
				y: {
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Value'
					}
				}
			}
		}
	};

	window.onload = function() {
		// bar chart
		// var ctx = document.getElementById('canvas_bar_chart').getContext('2d');
		// window.myBar = new Chart(ctx, {
		// 	type: 'bar',
		// 	data: barChartData,
		// 	options: {
		// 		responsive: true,
		// 		legend: {
		// 			position: 'top',
		// 		},
		// 		title: {
		// 			// display: true,
		// 			// text: 'Chart.js Bar Chart'
		// 		}
		// 	}
		// });

		// line chat
		var ctx_line = document.getElementById('canvas_line_chart').getContext('2d');
		window.myLine = new Chart(ctx_line, config_sales);
	};

	document.getElementById('randomizeData').addEventListener('click', function() {

		xAxis = ['June', 'July', 'August', 'September', 'October', 'November', 'December'];

		config_sales.data.dataset.data = [
					3,3,3,3,3,3,3
					// randomScalingFactor(),
					// randomScalingFactor(),
					// randomScalingFactor(),
					// randomScalingFactor(),
					// randomScalingFactor(),
					// randomScalingFactor(),
					// randomScalingFactor()
				];
		// config_sales.data.datasets.forEach(function(dataset) {
		// 	dataset.data = dataset.data.map(function() {
		// 		return randomScalingFactor();
		// 	});

		// });

		window.myLine.update();
	});

	// var colorNames = Object.keys(window.chartColors);
	// document.getElementById('addDataset').addEventListener('click', function() {
	// 	var colorName = colorNames[config_sales.data.datasets.length % colorNames.length];
	// 	var newColor = window.chartColors[colorName];
	// 	var newDataset = {
	// 		label: 'Dataset ' + config_sales.data.datasets.length,
	// 		backgroundColor: newColor,
	// 		borderColor: newColor,
	// 		data: [],
	// 		fill: false
	// 	};

	// 	for (var index = 0; index < config_sales.data.labels.length; ++index) {
	// 		newDataset.data.push(randomScalingFactor());
	// 	}

	// 	config_sales.data.datasets.push(newDataset);
	// 	window.myLine.update();
	// });

	// document.getElementById('addData').addEventListener('click', function() {
	// 	if (config_sales.data.datasets.length > 0) {
	// 		var month = MONTHS[config_sales.data.labels.length % MONTHS.length];
	// 		config_sales.data.labels.push(month);

	// 		config_sales.data.datasets.forEach(function(dataset) {
	// 			dataset.data.push(randomScalingFactor());
	// 		});

	// 		window.myLine.update();
	// 	}
	// });

	// document.getElementById('removeDataset').addEventListener('click', function() {
	// 	config_sales.data.datasets.splice(0, 1);
	// 	window.myLine.update();
	// });

	// document.getElementById('removeData').addEventListener('click', function() {
	// 	config_sales.data.labels.splice(-1, 1); // remove the label first

	// 	config_sales.data.datasets.forEach(function(dataset) {
	// 		dataset.data.pop();
	// 	});

	// 	window.myLine.update();
	// });
</script> -->


<script type="text/javascript">

	var config_sales = {
		fillColor: "rgba(220,220,220,1)",
		strokeColor: 'rgba(220,220,220,1)',
		pointColor: 'rgba(220,220,220,1)',
		pointStrokeColor: '#fff',
		pointHighlightFill: '#fff',
		pointHighlightStroke: 'rgba(220,220,220,1)',
	};

	// var data = {
 //    	labels: ['January', 'February', 'March'],
	//     datasets: [
	//         {
	// 	        fillColor: config_sales.fillColor,
	// 	        strokeColor: config_sales.strokeColor,
	// 	        pointColor: config_sales.pointColor,
	// 	        pointStrokeColor: config_sales.pointStrokeColor,
	// 	        pointHighlightFill: config_sales.pointHighlightFill,
	// 	        pointHighlightStroke: config_sales.pointHighlightStroke,
	// 	        data: [30,120,90]
	//         },
	//         // {
	// 	       //  fillColor: "rgba(100,220,220,0.7)",
	// 	       //  strokeColor: "rgba(220,220,220,1)",
	// 	       //  pointColor: "rgba(220,220,220,1)",
	// 	       //  pointStrokeColor: "#fff",
	// 	       //  pointHighlightFill: "#fff",
	// 	       //  pointHighlightStroke: "rgba(220,220,220,1)",
	// 	       //  data: [10,70,110]
	//         // }
	//     ]
 //    };

	// var data1 = {
	//     labels: ['March', 'Apr', 'May', 'june'],
	    
	//     datasets: [
	//         {
	// 	        fillColor: config_sales.fillColor,
	// 	        strokeColor: config_sales.strokeColor,
	// 	        pointColor: config_sales.pointColor,
	// 	        pointStrokeColor: config_sales.pointStrokeColor,
	// 	        pointHighlightFill: config_sales.pointHighlightFill,
	// 	        pointHighlightStroke: config_sales.pointHighlightStroke,
	// 	        data: [30,120,90]
	//         },
	//         // {
	// 	       //  fillColor: "rgba(100,220,220,0.7)",
	// 	       //  strokeColor: "rgba(220,220,220,1)",
	// 	       //  pointColor: "rgba(220,220,220,1)",
	// 	       //  pointStrokeColor: "#fff",
	// 	       //  pointHighlightFill: "#fff",
	// 	       //  pointHighlightStroke: "rgba(220,220,220,1)",
	// 	       //  data: [40,70,200]
	//         // }
	//     ]
 //    };

 // 	new Chart(context , {
 //    	type: "line",
	//     data: data, 
	// });

	// ===========

	$(document).ready( function () {
	 	$("#year_btn").trigger('click');
	});

	var context = document.querySelector('#graph').getContext('2d');
	// new Chart(context).Line(data);
	// new Chart(context,data);
	
	$("#year_btn").on("click", function() {
		$.ajax({
		    url: '../ajax/dashboard_request.php',
		    type: 'post',
		    data: {name:'get_sales_year_analytics'},
		    dataType: 'json',
		    success: function(response){ 

		    	// console.log(response);
		  //   	var response = response.filter(function (el) {
				//   return el != null;
				// });
		    	// return false;

		    	labels_new = [];y_axis_new = [];
		    	$.each(response, function(index, value){
		    		// if(value.year != "" && value.year != null){
				    	labels_new[index] = value.year;
				    	y_axis_new[index] = value.total;
				    // }
			    });

			    console.log(labels_new);
			    console.log(y_axis_new);

		    	new_data = {
			    	labels: labels_new,
				    datasets: [
				        {
				        	label:"Sales",
					        fillColor: config_sales.fillColor,
					        strokeColor: config_sales.strokeColor,
					        pointColor: config_sales.pointColor,
					        pointStrokeColor: config_sales.pointStrokeColor,
					        pointHighlightFill: config_sales.pointHighlightFill,
					        pointHighlightStroke: config_sales.pointHighlightStroke,
					        data: y_axis_new
				        }
				    ]
			    };

			    var context1 = document.querySelector('#graph').getContext('2d');
	    		new Chart(context , {type: "line",data: new_data});
		    }
	  	});
	});

	$("#month_btn").on("click", function() {
		$.ajax({
		    url: '../ajax/dashboard_request.php',
		    type: 'post',
		    data: {name:'get_sales_month_analytics'},
		    dataType: 'json',
		    success: function(response){ 

		    	// console.log(response);
		  //   	var response = response.filter(function (el) {
				//   return el != null;
				// });
		    	// return false;

		    	labels_new = [];y_axis_new = [];
		    	$.each(response, function(index, value){
		    		// if(value.year != "" && value.year != null){
				    	labels_new[index] = value.month;
				    	y_axis_new[index] = value.total;
				    // }
			    });

			    // console.log(labels_new);
			    // console.log(y_axis_new);

		    	new_data = {
			    	labels: labels_new,
				    datasets: [
				        {
				        	label:"Sales",
					        fillColor: config_sales.fillColor,
					        strokeColor: config_sales.strokeColor,
					        pointColor: config_sales.pointColor,
					        pointStrokeColor: config_sales.pointStrokeColor,
					        pointHighlightFill: config_sales.pointHighlightFill,
					        pointHighlightStroke: config_sales.pointHighlightStroke,
					        data: y_axis_new
				        }
				    ]
			    };

			    var context1 = document.querySelector('#graph').getContext('2d');
	    		new Chart(context , {type: "line",data: new_data});
		    }
	  	});
	});

	$("#week_btn").on("click", function() {
		$.ajax({
		    url: '../ajax/dashboard_request.php',
		    type: 'post',
		    data: {name:'get_sales_week_analytics'},
		    dataType: 'json',
		    success: function(response){ 

		    	// console.log(response);
		  //   	var response = response.filter(function (el) {
				//   return el != null;
				// });
		    	// return false;

		    	labels_new = [];y_axis_new = [];
		    	$.each(response, function(index, value){
		    		// if(value.year != "" && value.year != null){
				    	labels_new[index] = value.week;
				    	y_axis_new[index] = value.total;
				    // }
			    });

			    // console.log(labels_new);
			    // console.log(y_axis_new);

		    	new_data = {
			    	labels: labels_new,
				    datasets: [
				        {
				        	label:"Sales",
					        fillColor: config_sales.fillColor,
					        strokeColor: config_sales.strokeColor,
					        pointColor: config_sales.pointColor,
					        pointStrokeColor: config_sales.pointStrokeColor,
					        pointHighlightFill: config_sales.pointHighlightFill,
					        pointHighlightStroke: config_sales.pointHighlightStroke,
					        data: y_axis_new
				        }
				    ]
			    };

			    var context1 = document.querySelector('#graph').getContext('2d');
	    		new Chart(context , {type: "line",data: new_data});
		    }
	  	});
	});


	$("#day_btn").on("click", function() {
		$.ajax({
		    url: '../ajax/dashboard_request.php',
		    type: 'post',
		    data: {name:'get_sales_day_analytics'},
		    dataType: 'json',
		    success: function(response){ 

		    	// console.log(response);
		  //   	var response = response.filter(function (el) {
				//   return el != null;
				// });
		    	// return false;

		    	labels_new = [];y_axis_new = [];
		    	$.each(response, function(index, value){
		    		// if(value.year != "" && value.year != null){
				    	labels_new[index] = value.month;
				    	y_axis_new[index] = value.total;
				    // }
			    });

			    // console.log(labels_new);
			    // console.log(y_axis_new);

		    	new_data = {
			    	labels: labels_new,
				    datasets: [
				        {
				        	label:"Sales",
					        fillColor: config_sales.fillColor,
					        strokeColor: config_sales.strokeColor,
					        pointColor: config_sales.pointColor,
					        pointStrokeColor: config_sales.pointStrokeColor,
					        pointHighlightFill: config_sales.pointHighlightFill,
					        pointHighlightStroke: config_sales.pointHighlightStroke,
					        data: y_axis_new
				        }
				    ]
			    };

			    var context1 = document.querySelector('#graph').getContext('2d');
	    		new Chart(context , {type: "line",data: new_data});
		    }
	  	});
	});


	// $("#failed_notification_btn").on("click", function(e) {
		// 	$("#failed_notification").submit();
			// e.preventDefault();
		// });

		$("#failed_notification").validate({
			rules: {
				subject: "required",
				message: "required"
			},
			messages: {
				// lastname: "Please enter your lastname",
			},
			errorPlacement: function ( error, element ) {
				// Add the `help-block` class to the error element
				error.addClass( "help-block" );

				if ( element.prop( "type" ) === "checkbox") {
					error.insertAfter(element.parent("label"));
				} else {
					error.insertAfter(element);
				}
			},
			highlight: function ( element, errorClass, validClass ) {
				$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
			},
			unhighlight: function (element, errorClass, validClass) {
				$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
			},
			submitHandler: function (){
				formData = new FormData($('#failed_notification')[0]);
				formData.append('name', 'failed_notification');

			   // AJAX request
			   $.ajax({
				    url: '../ajax/dashboard_request.php',
				    type: 'post',
				    data: formData,
				    dataType: 'json',
				    processData: false,
					contentType: false,
					cache:false,
				    success: function(response){ 
				    	console.log(response);

				    	if(response.success){
				    		$("#alert-success-form").html(response.success);
				    		$("#alert-success-form").show();
							setTimeout(function() { $("#alert-success-form").hide(); }, 4000);
				    	}else if(response.error){
							$("#alert-danger-form").html(response.error);
							$("#alert-danger-form").show();
							setTimeout(function() { $("#alert-danger-form").hide(); }, 4000);
				    	}

						$("#failed_notification").find("input[type=text], textarea").val("");

				    	// console.log(response);
				    	// return false; 
				    }
			  	});
			}
		});

	// });

	$("#failed_notification_btn").click(function(e){
		// console.log($('#article_content').attr('id'));
		$("#failed_notification").submit();
	});

</script>

<!-- ========== -->

<?php include('footer.php'); ?>