<?php
  $page = "schedule";
  $page_selected = "schedule";
  include('header.php');
  include('../includes/agency_dash_functions.php');

  $user_id = $_SESSION['user_id'];

  // echo "<br/>";
  $notification = array();

  if(isset($_POST['delete_event_submit']) && $_POST['delete_event_submit'] == "Delete"){
    // print_r($_POST);
    $dlt_sql = "DELETE FROM agency_event WHERE event_id = ".$_POST['event_id'];
    if(mysql_query($dlt_sql)){
      $notification['success'] = "Event Deleted Successfully!";
    }
  }

  if(isset($_POST['add_event']) && $_POST['add_event'] == "Add Event"){
    // print_r($_POST);exit;
    $sql_add = "INSERT INTO agency_event
				SET
				event_color_id = ".$_POST['event_color_id'].",
        title = '".$_POST['title']."',
        notes = '".$_POST['notes']."',
				start = '".$_POST['start']."',
				end = '".$_POST['end']." 23:59:59'
				";

		if(mysql_query($sql_add)){
      $notification['success'] = "Event Added Successfully!";
    }
  }

  if(isset($_POST['edit_event_submit']) && $_POST['edit_event_submit'] == "Save"){
    // print_r($_POST);
    // exit;

    $up_sql = "UPDATE agency_event
              SET
              title = '".$_POST['title']."', 
              notes = '".$_POST['notes']."', 
              start = '".$_POST['start']."', 
              end = '".$_POST['end']." 23:59:59'
              WHERE event_id = ".$_POST['event_id']."
              ";
    if(mysql_query($up_sql)){
      $notification['success'] = "Event Updated Successfully!";
    }
  }
?>

<style>
  .fc-time {
      display: none;
  }
  /* .datepicker{ z-index:99999 !important; } */

  .datepicker{
    z-index: 1100 !important;
  }

  .ui-datepicker{
    z-index: 1100 !important;
  }

  .datepicker.dropdown-menu {
    z-index: 10002 !important;
  }
  .fc-day-grid-event{
    font-size: 14px;
    padding: 5px 10px;
    border-radius: 0px;
    margin: 5px 0px;
  }
</style>

<div id="page-wrapper">
  <div class="" id="main">

    <div class="row">
      <div class="col-sm-12 col-xs-12">
        <h3>schedule</h3>

          <?php if(isset($notification['success'])){ ?>
            <div class="alert alert-success" role="alert">
              <?php echo $notification['success']; ?>
            </div>
          <?php } ?>
        <?php
          // $booking_sql = "select atr.*,ap.firstname,ap.lastname,fu.user_avatar from agency_talent_request atr
          //                             LEFT JOIN agency_profiles ap ON ap.user_id = atr.user_id
          //                             LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
          //                             WHERE ap.roster_id = " . $_SESSION['user_id'] . "
          //                             AND atr.request_for = 'booking'
          //                             AND atr.request_status = 'approve'
          //                             AND atr.scheduled = 'N'
          //                           ";
          // $booking_res = mysql_query($booking_sql);

          // $booking = array();
          // while ($row = mysql_fetch_assoc($booking_res)) {
          //   $newAry = array();
          //   $newAry['title'] = 'Booking - ' . $row['firstname'] . ' ' . $row['lastname'];
          //   $newAry['start'] = $row['request_date'];
          //   $newAry['backgroundColor'] = '#f56954';
          //   $newAry['borderColor'] = '#f56954';
          //   $booking[] = $newAry;
          // }

          // $casting_sql = "select atr.*,ap.firstname,ap.lastname,fu.user_avatar from agency_talent_request atr
          //                             LEFT JOIN agency_profiles ap ON ap.user_id = atr.user_id
          //                             LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
          //                             WHERE ap.roster_id = " . $_SESSION['user_id'] . "
          //                             AND atr.request_for = 'casting'
          //                             AND atr.request_status = 'approve'
          //                             AND atr.scheduled = 'N'
          //                           ";
          // $casting_res = mysql_query($casting_sql);

          // $casting = array();
          // while ($row = mysql_fetch_assoc($casting_res)) {
          //   $newAry = array();
          //   $newAry['title'] = 'Casting - ' . $row['firstname'] . ' ' . $row['lastname'];
          //   $newAry['start'] = $row['request_date'];
          //   $newAry['backgroundColor'] = '#f39c12';
          //   $newAry['borderColor'] = '#f39c12';
          //   $casting[] = $newAry;
          // }

          // $events_ary = array_merge($booking, $casting);

          $event_sql = "select ae.event_id,ae.title,ae.start,ae.end,aec.color_code from agency_event ae
                                      LEFT JOIN agency_event_color aec ON aec.event_color_id = ae.event_color_id
                                    ";
          $event_query = mysql_query($event_sql);
          $events_ary = array();
          while ($row = mysql_fetch_assoc($event_query)) {
            $newAry = array();
            $newAry['id'] = $row['event_id'];
            $newAry['title'] = $row['title'];
            $newAry['start'] = $row['start'];
            $newAry['end'] = $row['end'];
            // $newAry['start'] = date('Y-m-d',strtotime($row['start']));
            // $newAry['end'] = date('Y-m-d',strtotime($row['end']));
            $newAry['backgroundColor'] = $row['color_code'];
            $newAry['borderColor'] = $row['color_code'];
            $events_ary[] = $newAry;
          }

          // echo "<pre>";
          // print_r($events_ary);
          // echo "</pre>";
          
        ?>

        <div class="row">

          <div class="col-md-3">

            <div class="box box-solid">
              <div class="box-header with-border">
                <h3 class="box-title">Create Event</h3>
              </div>
              <div class="box-body">
                <form action="" method="post" id="event_add_frm">
                  <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                    <ul class="fc-color-picker" id="color-chooser">
                      <?php 
                        $color_sql = "SELECT * FROM agency_event_color";
                        $color_query = mysql_query($color_sql);
                        while ($row = mysql_fetch_assoc($color_query)) {
                      ?>
                        <li><a class="<?php echo 'color-picker text-'.$row['color_name'].' color_code_'.$row['color_code']; ?>" id="<?php echo 'color_id_'.$row['event_color_id']; ?>" href="#"><i class="fa fa-square"></i></a></li>
                      <?php } ?>
                    </ul>
                  </div>
                  <div class="form-group">
                    <input id="new-event" name="title" type="text" class="form-control" placeholder="Event Title">
                    <!-- <div class="input-group-btn">
                      <button id="add-new-event" type="button" class="btn btn-primary btn-flat">Add</button>
                    </div> -->
                  </div>
                  <div class="form-group">
                    <input id="event_start_date" name="start" type="text" class="form-control" placeholder="Event Start Date">
                  </div>
                  <div class="form-group">
                    <input id="event_end_date" name="end" type="text" class="form-control" placeholder="Event End Date">
                  </div>

                  <div class="form-group">
                    <textarea name="notes" id="notes" cols="30" rows="4" placeholder="Event Notes" class="form-control"></textarea>
                  </div>

                  <input id="event_color_id" name="event_color_id" type="hidden" value="3">
                  <!-- <button id="add-new-event" type="button" class="btn btn-primary btn-flat btn-block">Add</button> -->
                  <input name="add_event" id="add_event" type="submit" class="btn btn-primary btn-flat btn-block" value="Add Event">
                </form>
              </div>
            </div>

            <!-- <div class="box box-solid">
              <div class="box-header with-border">
                <h4 class="box-title">Draggable Events</h4>
              </div>
              <div class="box-body">
                <div id="external-events">
                  <div class="external-event bg-green">Lunch</div>
                  <div class="external-event bg-yellow">Go home</div>
                  <div class="external-event bg-aqua">Do homework</div>
                  <div class="external-event bg-light-blue">Work on UI design</div>
                  <div class="external-event bg-red">Sleep tight</div>
                  <div class="checkbox">
                    <label for="drop-remove">
                      <input type="checkbox" id="drop-remove">
                      remove after drop
                    </label>
                  </div>
                </div>
              </div>
            </div> -->
            
          </div>

          <div class="col-md-9">
            <div class="box box-primary">
              <div class="box-body no-padding">
                <div id="calendar"></div>
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>

  </div>
</div>

<div class="modal fade" id="sch_edit_Modal" role="dialog"></div>

<?php include('footer_js.php'); ?>

<!-- <script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script> -->
<!-- <script type="text/javascript" src="../dashboard/assets/ckeditor/ckeditor.js"></script> -->

<!-- <script type="text/javascript" src="../dashboard/assets/bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.min.js"></script> -->
<script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script> -->

<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script>

<!-- fullCalendar 2.2.5 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="../dashboard/assets/fullcalendar/fullcalendar.min.js"></script>

<script>
  $(function() {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function ini_events(ele) {
      ele.each(function() {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        };

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject);

        // make the event draggable using jQuery UI
        // $(this).draggable({
        //   zIndex: 5000,
        //   revert: true, // will cause the event to go back to its
        //   revertDuration: 0 //  original position after the drag
        // });

      });

      // console.log($(this));
    }

    ini_events($('#external-events div.external-event'));

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)

    // var sample =[
    //     {
    //       title: 'All Day Event',
    //       start: new Date('2020-06-22'),
    //       backgroundColor: "#f56954",
    //       borderColor: "#f56954"
    //     },
    //     {
    //       title: 'Long Event',
    //       start: new Date('2020-06-22'),
    //       end: new Date('2020-06-28'),
    //       backgroundColor: "#f39c12", 
    //       borderColor: "#f39c12" 
    //     }];

    // console.log(sample);

    sample = <?php echo json_encode($events_ary); ?>;
    // console.log(sample[1].start);
    // console.log(sample);
    $.each(sample, function(key, value) {
      sample[key].start = new Date(value.start);
      // console.log(sample[key].start);
      // console.log(value);
    });
    // console.log(sample);

    var date = new Date();
    var d = date.getDate(),
    m = date.getMonth(),
    y = date.getFullYear();
    calender = $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
      },
      buttonText: {
        today: 'today',
        month: 'month',
        week: 'week',
        day: 'day'
      },
      //Random default events
      events: sample
        // [
        //   {
        //     title: 'All Day Event',
        //     start: new Date(y, m, 1),
        //     backgroundColor: "#f56954", //red
        //     borderColor: "#f56954" //red
        //   },
        //   {
        //     title: 'Long Event',
        //     start: new Date(y, m, d - 5),
        //     end: new Date(y, m, d - 2),
        //     backgroundColor: "#f39c12", //yellow
        //     borderColor: "#f39c12" //yellow
        //   },
        //   {
        //     title: 'Meeting',
        //     start: new Date(y, m, d, 10, 30),
        //     allDay: false,
        //     backgroundColor: "#0073b7", //Blue
        //     borderColor: "#0073b7" //Blue
        //   },
        //   {
        //     title: 'Lunch',
        //     start: new Date(y, m, d, 12, 0),
        //     end: new Date(y, m, d, 14, 0),
        //     allDay: false,
        //     backgroundColor: "#00c0ef", //Info (aqua)
        //     borderColor: "#00c0ef" //Info (aqua)
        //   },
        //   {
        //     title: 'Birthday Party',
        //     start: new Date(y, m, d + 1, 19, 0),
        //     end: new Date(y, m, d + 1, 22, 30),
        //     allDay: false,
        //     backgroundColor: "#00a65a", //Success (green)
        //     borderColor: "#00a65a" //Success (green)
        //   },
        //   {
        //     title: 'Click for Google',
        //     start: new Date(y, m, 28),
        //     end: new Date(y, m, 29),
        //     url: 'http://google.com/',
        //     backgroundColor: "#3c8dbc", //Primary (light-blue)
        //     borderColor: "#3c8dbc" //Primary (light-blue)
        //   }
        // ]
        ,
      editable: false,
      droppable: false, // this allows things to be dropped onto the calendar !!!
      drop: function(date, allDay) { // this function is called when something is dropped

        // retrieve the dropped element's stored Event Object
        var originalEventObject = $(this).data('eventObject');

        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject);

        // assign it the date that was reported
        copiedEventObject.start = date;
        copiedEventObject.allDay = allDay;
        copiedEventObject.backgroundColor = $(this).css("background-color");
        copiedEventObject.borderColor = $(this).css("border-color");

        // render the event on the calendar
        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

        // is the "remove after drop" checkbox checked?
        if ($('#drop-remove').is(':checked')) {
          // if so, remove the element from the "Draggable Events" list
          $(this).remove();
        }

      },
      // eventClick: function(event) {
      //   alert(this.calendar.options.id);
      // },
      eventClick: function(info) {
        console.log(info);
        // alert('Event: ' + info.title);
        // alert('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
        // alert('View: ' + info.view.type);

        // change the border color just for fun
        // info.el.style.borderColor = 'red';

        // AJAX request
        $.ajax({
            url: '../ajax/dashboard_request.php',
            type: 'post',
            data: {name:'sch_edit_Modal','event_id':info.id},
            // dataType: 'json',
            success: function(response){
              // console.log(response);
              $('#sch_edit_Modal').html(response);

              // Display Modal
              $('#sch_edit_Modal').modal('show');
            }
        });

      }
    });

    // $('#calendar').fullCalendar('refresh');

    /* ADDING EVENTS */
    var currColor = "#3c8dbc"; //Red by default
    //Color chooser button
    var colorChooser = $("#color-chooser-btn");
    $("#color-chooser > li > a").click(function(e) {
      e.preventDefault();
      //Save color
      currColor = $(this).css("color");
      //Add color effect to button
      $('#add-new-event').css({
        "background-color": currColor,
        "border-color": currColor
      });

      $('#add_event').css({
        "background-color": currColor,
        "border-color": currColor
      });
    });
    
    // $("#add-new-event").click(function(e) {
    //   e.preventDefault();
    //   //Get value and make sure it is not null

    //   title = $("#new-event").val();
    //   start = $("#event_start_date").val();
    //   end = $("#event_end_date").val();
    //   event_color_id = $("#event_color_id").val();

    //   if(title == "" || title == "" || end == ""){
    //     alert('Please fill up all fields to add events');
    //     return false;
    //   }

    //   $.ajax({
    //     url: '../ajax/dashboard_request.php',
    //     type: 'post',
    //     data: {name:'add_event_tm','title':title,'start':start,'end':end,'event_color_id':event_color_id},
    //     // dataType: 'json',
    //     success: function(response){
    //       console.log(response);
    //       if(response){
    //         console.log('111');
    //       }else{
    //         console.log('222');
    //       }
    //       // $(target).html(response);
    //     }
    //   });
      
    // });
    
  });


  // $("#event_start_date").datepicker();
  // $("#event_end_date").datepicker();

    $("#event_start_date").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
	      changeYear: true,
        minDate: 0,
        onSelect: function () {
            var dt2 = $('#event_end_date');
            var startDate = $(this).datepicker('getDate');
            // startDate.setDate(startDate.getDate() + 30);
            var minDate = $(this).datepicker('getDate');
            var dt2Date = dt2.datepicker('getDate');
            dt2.datepicker('option', 'minDate', minDate);
        }
    });

    $('#event_end_date').datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
	      changeYear: true,
        minDate: 0
    });

    $(".color-picker").click(function(){
      id_str = $(this).attr('id');
      id_ary = id_str.split('_');
      $("#event_color_id").val(id_ary[2]);
      // console.log(id_ary);
    });

    $("#event_add_frm").validate({
      rules: {
        "title":"required",
        "start":"required",
        "end":"required",
      },
      errorElement: "em",
      errorPlacement: function ( error, element ) {
          // Add the `help-block` class to the error element
          error.addClass( "help-block" );

          if ( element.prop("type") === "checkbox") {
              // error.insertAfter(element.parent("label"));
              error.insertAfter(element.parents('label').siblings('.checkbox_err'));
          } else {
              error.insertAfter(element);
          }
      },
    });

    if (window.history.replaceState) {
      window.history.replaceState( null, null, window.location.href );
    }
</script>

<?php include('footer.php'); ?>