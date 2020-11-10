<script type="text/javascript" src="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-download"></i> <?php echo $this->lang->line('download_center'); ?></h1>

    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('upload_content', 'can_add')) {
                echo "12";
            } else {
                echo "12";
            }
            ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">

                        <h3 class="box-title titlefix"><?php echo $heading ?></h3>

                        <div class="box-tools pull-right">

                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <div class="pull-right">

                            </div><!-- /.pull-right -->
                        </div>

                        <!-- this is for the filter based on subjects -->
                        <form method="post" >
                          <select id="selectedSubject" name="selectedSubject" class="form-control" onchange="this.form.submit();">
                              <?php
                              $ctr = 0;
                                foreach ($subjects as $subject) {
                                  if(!isset($_POST['selectedSubject'])){
                                    if($ctr == 0){
                                      // sets the first element of subjects list as the initial subject_name filter
                                      $_POST['selectedSubject'] = $subject['name'];
                                      $ctr++;
                                    }
                                  }
                              ?>
                                <option value="<?php echo $subject['name'] ?>"
                                  <?php echo (isset($_POST['selectedSubject']) && $_POST['selectedSubject'] == $subject['name'])?'selected="selected"':''; ?>
                                ><?php echo $subject['name'] ?></option>
                              <?php
                                }

                              ?>
                          </select>
                        </form>

                        <div class="mailbox-messages table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('content_list'); ?></div>

                            <table class="table table-striped table-bordered table-hover example nowrap">
                                <thead>
                                    <tr>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                        <th>Title</th>
                                        <th>Subject</th>
                                        <th>Teacher</th>
                                        <th>Duration (Start Date - End Date)</th>
                                        <th><?php echo $this->lang->line('type'); ?></th>
                                        <th>Term</th>
                                        <?php if($role!="student"): ?>
                                            <th>Grade</th>
                                        <?php endif; ?>
                                        <!-- Remove by JSS -->
                                        <!-- <th>Education Level</th> -->
                                        <!-- Remove by JSS -->
                                        <?php if($role!="student"): ?>
                                            <th>Shared</th>
                                        <?php endif; ?>
                                        <!-- <th>Status</th> -->

                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    foreach ($list as $list_key => $list_data):
                                      if(isset($_POST['selectedSubject'])){
                                        $selectedSubject = $_POST['selectedSubject'];
                                        if($list_data['subject_name'] == $selectedSubject){
                                    ?>
                                          <tr>
                                              <td class="mailbox-date pull-right">
                                                  <?php if($role=="admin"): ?>

                                                      <?php if($list_data['lesson_type'] == "virtual"||$list_data['lesson_type'] == "zoom"): ?>
                                                          <?php if($lesson_query=="today"): ?>
                                                              <a data-placement="left" href="<?php echo site_url('lms/lesson/create/'.$list_data['id']);?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="Start Class" >
                                                                      <i class="fa fa-sign-in"></i> Start Class
                                                              </a>
                                                          <?php endif; ?>
                                                          <?php if($real_role=="7"||$real_role=="1"): ?>
                                                              <?php if($list_data['lesson_type'] == "virtual"): ?>
                                                                  <a data-placement="left" href="<?php echo $list_data['teacher_google_meet'];?>" target="_blank" class="btn btn-default btn-xs"  data-toggle="tooltip" title="Join Meet" >
                                                                          <i class="fa fa-sign-in"></i> Join Meet
                                                                  </a>
                                                              <?php endif; ?>
                                                              <?php if($list_data['lesson_type'] == "zoom"): ?>
                                                                  <a data-placement="left" href="<?php echo base_url('lms/lesson/zoom_checker') ;?>" target="_blank" class="btn btn-default btn-xs" data-toggle="tooltip" title="Join Zoom" >
                                                                          <i class="fa fa-sign-in"></i> Join Zoom
                                                                  </a>
                                                              <?php endif; ?>
                                                          <?php endif; ?>
                                                      <?php endif; ?>
                                                      <a data-placement="right" href="#" onclick="attendance('<?php echo $list_data['id'] ?>','<?php echo addslashes($list_data['lesson_name']) ?>')" class="btn btn-default btn-xs"  data-toggle="tooltip" title="Lesson Student Logs (See students who accessed the lessons)" >
                                                                      <i class="fa fa-users"></i>
                                                      </a>
                                                      <a data-placement="right" href="#" class="btn btn-default btn-xs"  data-toggle="tooltip" onclick="email_logs('<?php echo $list_data['id'] ?>','<?php echo addslashes($list_data['lesson_name']) ?>')" title="Email Logs(See if the notification was sent to parents)" >
                                                                      <i class="fa fa-envelope"></i>
                                                      </a>
                                                      <a data-placement="left" href="<?php echo site_url('lms/lesson/create/'.$list_data['id']);?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>" >
                                                              <i class="fa fa-edit"></i>
                                                      </a>
                                                      <a data-placement="left" href="<?php echo site_url('lms/lesson/delete/'.$list_data['id']);?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                          <i class="fa fa-remove"></i>
                                                      </a>

                                                  <?php elseif($role=="student"): ?>
                                                      <?php if($lesson_sched!="upcoming"): ?>
                                                          <a data-placement="left" id="student_view" href="#" class="btn btn-default btn-xs"  data-toggle="tooltip" onclick="check_class('<?php echo $list_data['lesson_id'] ?>')" title="<?php echo $this->lang->line('view'); ?>" >
                                                                      <i class="fa fa-eye"  ></i>
                                                                      <?php if($lesson_sched == "past"): ?>
                                                                          View Lesson
                                                                      <?php else: ?>

                                                                          Enter Class
                                                                      <?php endif; ?>
                                                          </a>
                                                      <?php endif; ?>

                                                  <?php endif; ?>

                                              </td>
                                              <td class="mailbox-name">
                                                  <?php echo $list_data['lesson_name']?>
                                              </td>
                                              <td class="mailbox-name">
                                                  <?php echo $list_data['subject_name']; ?>
                                              </td>
                                              <td class="mailbox-name">
                                                  <?php echo $list_data['name'] ?> <?php echo $list_data['surname'] ?>
                                              </td>

                                              <td class="mailbox-name">
                                                 <?php echo date("M d, h:i A", strtotime($list_data['start_date'])); ?> - <?php echo date("M d, h:i A", strtotime($list_data['end_date'])); ?>
                                              </td>
                                              <td class="mailbox-name">
                                                  <?php echo ($list_data['lesson_type']=="virtual")?"Google Meet":$list_data['lesson_type']; ?>
                                              </td>

                                              <td class="mailbox-name">
                                                  <center><?php echo $list_data['term']; ?></center>
                                              </td>

                                              <?php if($role!="student"): ?>
                                                  <td class="mailbox-name">
                                                      <?php echo $list_data['class']; ?>
                                                  </td>
                                              <?php endif; ?>

                                              <!-- <td class="mailbox-name">
                                                  <?php echo str_replace("_", " ", strtoupper($list_data['education_level'])); ?>
                                              </td> -->
                                              <?php if($role!="student"): ?>
                                                  <td>
                                                      <?php echo ($list_data['shared'] == 1)?"Yes":"No" ; ?>
                                                  </td>
                                              <?php endif; ?>
                                              <!-- <td>
                                                  <select class="lesson_status" lesson_id="<?php echo $list_data['id'] ?>" <?php if($role=="student"): ?> readonly="" <?php endif; ?> >
                                                      <option value="awaiting">Awaiting</option>
                                                      <option value="cancelled">Cancelled</option>
                                                      <option value="completed">Completed</option>
                                                  </select>
                                              </td> -->

                                          </tr>

                                    <?php
                                        }
                                      }
                                      endforeach;
                                    ?>



                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->

                    </div><!-- /.box-body -->

                </div>
            </div><!--/.col (left) -->


            <!-- right column -->

        </div>
        <div class="row">
            <!-- left column -->

            <!-- right column -->
            <div class="col-md-12">

                <!-- Horizontal Form -->

                <!-- general form elements disabled -->

            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<div class="modal fade" id="initial" tabindex="-1" role="dialog" aria-labelledby="initial" style="padding-left: 0 !important">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title">Enter Class.</h4>
            </div>
            <div class="modal-body pt0 pb0" id="">
                <!-- <div class="container"> -->

                        <table class="table table-responsive">
                            <tr>

                                <th colspan="2"><center><h4 class="note"> Please click which will you open</center></h4></th>
                            </tr>
                            <tr>
                                <td><center><a href="" id="view_lesson" target="_blank"><button class="btn btn-success">View Lesson</button></a></center></td>
                                <td><center><a href="" id="enter_video" target="_blank"><button class="btn btn-primary">Enter Video Conference</button></a></center></td>
                            </tr>
                        </table>
                <!-- </div> -->

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="email_logs" tabindex="-1" role="dialog" aria-labelledby="email_logs" style="padding-left: 0 !important">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title">Email Logs for <span class="lesson_title_email_log"><span></h4>
            </div>
            <div class="modal-body" id="">

                <table class="table table-responsive" id="myTable">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Receiver</th>
                            <th>Status</th>
                            <th>Username Sent</th>
                            <th>Password Sent</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>Joeven Cerveza</td>
                            <td>cervezajoeven@gmail.com</td>
                            <td>Sent</td>
                            <td>student</td>
                            <td>student</td>
                            <td>August 28, 2020 2:00 AM</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Student Name</th>
                            <th>Receiver</th>
                            <th>Status</th>
                            <th>Username Sent</th>
                            <th>Password Sent</th>
                            <th>Timestamp</th>
                        </tr>
                    </tfoot>

                </table>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="attendance" tabindex="-1" role="dialog" aria-labelledby="email_logs" style="padding-left: 0 !important">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title">Attendance for <span class="lesson_title_attendance"><span></h4>
            </div>
            <div class="modal-body" id="">

                <table class="table table-responsive" id="attendance_table">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>Joeven Cerveza</td>
                            <td>August 28, 2020 2:00 AM</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Student Name</th>
                            <th>Timestamp</th>
                        </tr>
                    </tfoot>

                </table>

            </div>
        </div>
    </div>
</div>

<script>
    var table = $('#myTable').DataTable();
    var attendance_table = $('#attendance_table').DataTable();
    var user_id = '<?php echo $user_id ?>';
    function check_class(lesson_id){
        var url = "<?php echo base_url('lms/lesson/check_class/');?>"+lesson_id+'/'+user_id;
        console.log(url);
        $.ajax({
            url: url,
            method:"POST",
        }).done(function(data) {
            console.log(data);
            var parsed_data = JSON.parse(data);
            $('#initial').modal('show');
            if(parsed_data.video!=""){

                $("#enter_video").show();
                $("#enter_video").attr("href",parsed_data.video);
            }else{
                $("#enter_video").hide();
                $(".note").text("The teacher have not started the zoom class yet.");
            }
            if(parsed_data.lms!=""){

                $("#view_lesson").show();
                $("#view_lesson").attr("href",parsed_data.lms);
                if(parsed_data.video==""){
                    if(parsed_data.lesson_type=="others"){
                        $(".note").text("You are only allowed to view lesson. Since its not a zoom or google meet class.");
                    }else{
                        $(".note").text("The teacher haven't started the class yet. But you are allowed to view the lesson");
                    }

                }else{
                    $(".note").text("Please select an action.");
                }


            }else{
                $("#view_lesson").hide();
                $(".note").text("The teacher have not allowed the viewing of lesson yet. Only the video conference.");
            }

            if(parsed_data.lms==""&&parsed_data.video==""){
                $(".note").text("The teacher may have not allowed viewing of lesson and haven't started the class yet. Please wait for the teacher to start.");
            }

        });
    }
    function email_logs(lesson_id,lesson_name){
        var url = "<?php echo base_url('lms/lesson/emails/');?>"+lesson_id;
        $.ajax({
            url: url,
            method:"POST",
        }).done(function(data) {
            var parsed_data = JSON.parse(data);
            $('#email_logs').modal('show');
            table.clear().draw();
            $(".lesson_title_email_log").text(lesson_name);
            $.each(parsed_data,function(key,value){
                table.row.add([value.firstname+" "+value.lastname,value.receiver,value.email_status,value.username_sent,value.password_sent,value.date_created]).draw().node();
            });

        });
    }

    function attendance(lesson_id,lesson_name){
        var url = "<?php echo base_url('lms/lesson/attendance/');?>"+lesson_id;
        $.ajax({
            url: url,
            method:"POST",
        }).done(function(data) {
            var parsed_data = JSON.parse(data);
            $('#attendance').modal('show');
            attendance_table.clear().draw();
            $(".lesson_title_attendance").text(lesson_name);
            $.each(parsed_data,function(key,value){
                attendance_table.row.add([value.firstname+" "+value.lastname,value.timestamp]).draw().node();
            });

        });
    }
    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });

        $(".lesson_status").change(function(){
            var lesson_status_val = $(this).val();

            alert($(this).val());
        });


    });
</script>
