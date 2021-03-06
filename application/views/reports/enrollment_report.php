<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style type="text/css">
    /*REQUIRED*/
    .carousel-row {
        margin-bottom: 10px;
    }
    .slide-row {
        padding: 0;
        background-color: #ffffff;
        min-height: 150px;
        border: 1px solid #e7e7e7;
        overflow: hidden;
        height: auto;
        position: relative;
    }
    .slide-carousel {
        width: 20%;
        float: left;
        display: inline-block;
    }
    .slide-carousel .carousel-indicators {
        margin-bottom: 0;
        bottom: 0;
        background: rgba(0, 0, 0, .5);
    }
    .slide-carousel .carousel-indicators li {
        border-radius: 0;
        width: 20px;
        height: 6px;
    }
    .slide-carousel .carousel-indicators .active {
        margin: 1px;
    }
    .slide-content {
        position: absolute;
        top: 0;
        left: 20%;
        display: block;
        float: left;
        width: 80%;
        max-height: 76%;
        padding: 1.5% 2% 2% 2%;
        overflow-y: auto;
    }
    .slide-content h4 {
        margin-bottom: 3px;
        margin-top: 0;
    }
    .slide-footer {
        position: absolute;
        bottom: 0;
        left: 20%;
        width: 78%;
        height: 20%;
        margin: 1%;
    }
    /* Scrollbars */
    .slide-content::-webkit-scrollbar {
        width: 5px;
    }
    .slide-content::-webkit-scrollbar-thumb:vertical {
        margin: 5px;
        background-color: #999;
        -webkit-border-radius: 5px;
    }
    .slide-content::-webkit-scrollbar-button:start:decrement,
    .slide-content::-webkit-scrollbar-button:end:increment {
        height: 5px;
        display: block;
    }
</style>

<div class="content-wrapper" style="min-height: 946px;">

    <section class="content-header">
        <h1>
            <i class="fa fa-bus"></i> <?php echo $this->lang->line('transport'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('reports/_studentinformation');?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                      <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>

                     <form role="form" action="<?php echo site_url('report/enrollment_report') ?>" method="post" class="">
                        <div class="box-body row">

                            <?php echo $this->customlib->getCSRF(); ?>

                            <div class="col-sm-6 col-md-3" >
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('search') . " " . $this->lang->line('type'); ?></label>
                                    <select class="form-control" name="search_type" onchange="showdate(this.value)">
                                       
                                        <?php foreach ($searchlist as $key => $search) {
                                            ?>
                                            <option value="<?php echo $key ?>" <?php
                                            if ((isset($search_type)) && ($search_type == $key)) {

                                                echo "selected";

                                                }
                                            ?>><?php echo $search ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('search_type'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3" >
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select class="form-control" name="gender">
                                       <option value="all" <?php if($other_variables['gender']=='all'){ echo "selected"; } ?>>All</option>
                                       <option value="male" <?php if($other_variables['gender']=='male'){ echo "selected"; } ?>>Male</option>
                                       <option value="female" <?php if($other_variables['gender']=='female'){ echo "selected"; } ?>>Female</option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('search_type'); ?></span>
                                </div>
                            </div>
                            <!-- <div class="col-sm-6 col-md-3" >
                                <div class="form-group">
                                    <label>Enrollment Payment Status</label>
                                    <select class="form-control" name="enrollment_payment_status">
                                       <option value="all" <?php if($other_variables['enrollment_payment_status']=='all'){ echo "selected"; } ?> >All</option>
                                       <option value="paid" <?php if($other_variables['enrollment_payment_status']=='paid'){ echo "selected"; } ?> >Paid</option>
                                       <option value="unpaid" <?php if($other_variables['enrollment_payment_status']=='unpaid'){ echo "selected"; } ?> >Unpaid</option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('search_type'); ?></span>
                                </div>
                            </div> -->

                            <div class="col-sm-6 col-md-3" >
                                <div class="form-group">
                                    <label>Class</label>
                                    <select class="form-control" name="class">
                                        <option value="all">All</option>
                                        <?php foreach ($classes as $key => $value): ?>
                                            <option value="<?php echo $value['id'] ?>" <?php if($other_variables['class']== $value['id']){ echo "selected"; } ?> ><?php echo $value['class'] ?></option>
                                        <?php endforeach; ?>
                                        
                                        <option value="unpaid">Unpaid</option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('search_type'); ?></span>
                                </div>
                            </div>
                               
                            <div id='date_result'>
                                
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
             

            <div class="">
                <div class="box-header ptbnull"></div>
                <div class="box-header ptbnull">
                    <h3 class="box-title titlefix"><i class="fa fa-money"></i> Enrollment Report</h3>
                </div>
                <div class="box-body table-responsive">
                 <div class="download_label"><?php echo  $this->lang->line('admission')." ".$this->lang->line('report')."<br>";$this->customlib->get_postmessage();; ?></div>
                    <table class="table table-striped table-bordered table-hover example nowrap">
                       <thead>
                                        <tr>
											
                                            <!-- <th><?php echo $this->lang->line('admission_no'); ?></th> -->
											
                                            <th><?php echo $this->lang->line('student_name'); ?></th>
                                            <th><?php echo $this->lang->line('class'); ?></th>
											<!-- <?php if ($sch_setting->father_name) {  ?>
                                            <th><?php echo $this->lang->line('father_name'); ?></th>
											<?php } ?> -->
											<th>Enrollment Type</th>
                                            <!-- <th><?php echo $this->lang->line('date_of_birth'); ?></th> -->
											<?php if ($sch_setting->admission_date) {  ?>
                                            <th>Enrollment Payment Date</th><?php } ?>
                                            
                                            <!-- <th>Enrollment Status</th> -->
                                            <th><?php echo $this->lang->line('gender'); ?></th>
											<!-- <?php if ($sch_setting->category) {  ?>
                                            <th><?php echo $this->lang->line('category'); ?></th>
											<?php } if ($sch_setting->mobile_no) {  ?> -->
                                            <!-- <th>Enrollment Payment Status</th> -->
                                            <!-- <th><?php echo $this->lang->line('mobile_no'); ?></th> -->
											<?php } ?>
                                        </tr>
                                    </thead>
                           <tbody>
                                        <?php
                                        $count = 0;
                                        if (empty($resultlist)) {
                                            ?>

                                            <?php
                                        } else {
                                            $count = 0;
                                            foreach ($resultlist as $student) {
                                                ?>
                                                <tr>
													
                                                    <!-- <td><?php echo $student['admission_no']; ?></td> -->
													
                                                    <td>
                                                        <a href="<?php echo base_url(); ?>student/view/<?php echo $student['id']; ?>"><?php echo $student['lastname'] . ", " . $student['firstname']; ?>
                                                        </a>
                                                    </td>
                                                    <!-- <td><?php echo $student['class'] . " (" . $student['section'] . ")" ?></td> -->
                                                    <td><?php echo $student['class']?></td>
													<!-- <?php if ($sch_setting->father_name) {  ?>
                                                    <td><?php echo $student['father_name']; ?></td>
													<?php } ?> -->
                                                    <td><?php echo $student['enrollment_type'] ?></td>
                                                     <!-- <td><?php
                                                        if (!empty($student['dob'])) {
                                                            echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob']));
                                                        }
                                                        ?></td>
														<?php if ($sch_setting->admission_date) {  ?> -->
                                                    <td><?php
                                                        if (!empty($student['admission_date'])) {
                                                            echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['admission_date']));
                                                        }
                                                        ?></td><?php } ?>
                                                    <!-- <td><?php echo ($student['is_enroll']==1)?"Enrolled":"Not Enrolled"; ?></td> -->
                                                    <td><?php echo $student['gender']; ?></td>
													<!-- <?php if ($sch_setting->category) {  ?>
                                                    <td><?php echo $student['category']; ?></td>
													<?php } if ($sch_setting->mobile_no) {  ?> -->
                                                    <!-- <td><?php echo $student['mobileno']; ?></td> -->
                                                    <!-- <td><?php echo strtoupper($student['enrollment_payment_status']); ?></td> -->
													<?php } ?>

                                                </tr>
                                                <?php
                                                $count++;
                                            }
                                        }
                                        ?>
                                        <tr><td></td><td></td><td>Total Enrollment in this duration :</td><td > <?php echo $filter_label; ?></td><td><?php echo $count; ?></td><td></td><td></td><td></td><td ></td></tr>
                                    </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
    </div>   
</div>  
</section>
</div>

<script>
    <?php  
    if($search_type=='period'){
        ?>

          $(document).ready(function () {
            showdate('period');
          });

        <?php
    }
    ?>
   
    </script>