<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1><i class="fa fa-line-chart"></i> <?php echo $this->lang->line('reports'); ?> <small> <?php echo $this->lang->line('filter_by_name1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content" >
        <?php $this->load->view('reports/_studentinformation'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>

                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>

                    <div class="box-body">    
                        <form role="form" action="<?php echo site_url('report/class_record_quarterly') ?>" method="post" class="">
                            <div class="row">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-sm-6 col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('current_session'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="session_id" name="session_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($session_list as $session) {
                                                ?>
                                                <option value="<?php echo $session['id'] ?>" <?php if ($session['id'] == $sch_setting->session_id) echo "selected=selected" ?>><?php echo $session['session'] ?></option>
                                                <?php
                                                //$count++;
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('session_id'); ?></span>
                                    </div>
                                </div>                                 

                                <div class="col-sm-6 col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $class) {
                                                ?>
                                                <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                                <?php
                                                //$count++;
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div> 

                                <div class="col-sm-6 col-md-2">
                                    <div class="form-group">  
                                        <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>  
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('subject'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="subject_id" name="subject_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('subject_id'); ?></span>
                                    </div>
                                </div> 

                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('teacher'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="teacher_id" name="teacher_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($teacher_list as $teacher) {
                                                ?>
                                                <option value="<?php echo $teacher['id'] ?>" <?php if (set_value('teacher_id') == $teacher['id']) echo "selected=selected" ?>><?php echo $teacher['teacher'] ?></option>
                                                <?php
                                                //$count++;
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('teacher_id'); ?></span>
                                    </div>
                                </div>                                 
                                
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div><!--./row-->     
                        </form>
                    </div><!--./box-body-->    
            
                    <div class="">
                        <div class="box-header ptbnull"></div> 
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo form_error('class_record_quarterly'); ?> Summary of Quarterly Grades<?php //echo $this->lang->line('class_record_summary') ; ?></h3>
                        </div>
                        <div class="box-body table-responsive">
                            <?php if (isset($resultlist)) {?>
                            <div class="download_label"><?php echo $this->lang->line('class_record_summary')."<br>";$this->customlib->get_postmessage();?></div>
                                <table class="table table-striped table-bordered table-hover example nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th rowspan=2>Learner Names</th>
                                            <th rowspan=2>Gender</th>
                                            <?php
                                                foreach($quarter_list as $row) {
                                                    echo "<th class=\"text-center\">".$subject_name."</th>\r\n";
                                                }
                                            ?>
                                        </tr>
                                        <tr>
                                            <?php
                                                foreach($quarter_list as $row) {
                                                    echo "<th class=\"text-center\">".$row->description."</th>\r\n";
                                                }
                                            ?>
                                            <th class="text-center">Average</th>
                                            <th class="text-center">Final Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php                                 
                                        foreach($resultlist as $row) {
                                            $ctr=0;
                                            echo "<tr>\r\n";
                                            foreach($row as $val) {
                                                if ($ctr<=1)
                                                    echo "<td class='text-left'>".$val."</td>\r\n";
                                                else 
                                                    echo "<td class='text-center'>".$val."</td>\r\n";
                                                $ctr++;
                                            }
                                            echo "</tr>\r\n";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php } ?>
                        </div>
                    </div><!--./box box-primary -->     
                </div><!-- ./col-md-12 -->  
            </div>       
        </div>  
    </section>
</div>

<script type="text/javascript">
var class_id;
var base_url = '<?php echo base_url() ?>';

    function getSectionByClass(class_id, section_id) {
        if (class_id != "" && section_id != "") {
            $('#section_id').html("");
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                beforeSend: function () {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function () {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        }
    }

    function getSubjectsByClass(class_id, subject_id) {
        if (class_id != "") {
            $('#subject_id').html("");
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "admin/subject/get_subject_list",
                data: {'class_id': class_id },
                dataType: "json",
                beforeSend: function () {
                    $('#subject_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (subject_id == obj.subject_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.subject_id + " " + sel + ">" + obj.subject + "</option>";
                    });
                    $('#subject_id').append(div_data);
                },
                complete: function () {
                    $('#subject_id').removeClass('dropdownloading');
                }
            });
        }
    }

    $(document).ready(function () {
        class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        var subject_id = '<?php echo set_value('subject_id') ?>';
        getSectionByClass(class_id, section_id);
        getSubjectsByClass(class_id, subject_id);

        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            class_id = $(this).val();
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                beforeSend: function () {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function () {
                    $('#section_id').removeClass('dropdownloading');
                }
            });

            $('#subject_id').html("");
            var div_data2 = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "admin/subject/get_subject_list",
                data: {'class_id': class_id },
                dataType: "json",
                beforeSend: function () {
                    $('#subject_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {                        
                        div_data2 += "<option value=" + obj.subject_id + ">" + obj.subject + "</option>";
                    });
                    $('#subject_id').append(div_data2);
                },
                complete: function () {
                    $('#subject_id').removeClass('dropdownloading');
                }
            });
        });
    });
</script>