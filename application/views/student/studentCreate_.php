@@ -0,0 +1,291 @@
<style type="text/css">
    .ui-autocomplete { max-height: 300px; overflow-y: scroll; overflow-x: hidden;}
    /*label { color: #357ebd; }*/
    label, table thead tr th { color: #3a495e !important; }
    .note { background-color: #d9edf7; color: #31708f; padding: 5px; border-color: #bce8f1; }
    .req { color: red; }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <!--<div class="hidden pull-right box-tools impbtntitle" id="importFile">
                        <?php if ($this->rbac->hasPrivilege('import_student', 'can_view')) {   ?>
                            <a href="<?php echo site_url('student/import') ?>">
                                <button class="btn btn-primary btn-sm"><i class="fa fa-upload"></i> <?php echo $this->lang->line('import_student'); ?></button>
                            </a>
                        <?php } ?>
                    </div>-->
                    <form id="form1" action="<?php echo site_url('student/create') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <div class="">
                            <div class="bozero">
                                <h4 class="pagetitleh-whitebg"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('admission'); ?> </h4>
                                <div class="around10">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-6 form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <label for="enrollment_type" class="control-label small"><?php echo $this->lang->line('enrollment_type'); ?></label><small class='req'> *</small>
                                                </span>
                                                <select id="enrollment_type" name="enrollment_type" class="form-control" onchange="DoOnChange(this)">
                                                    <option style="color: lightgrey; font-style: italic;" value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($enrollment_type_list as $etype) { ?>
                                                        <option value="<?php echo $etype['e_type'] ?>"<?php if (set_value('enrollment_type') == $etype['e_type']) echo "selected=selected" ?>><?php echo $etype['description'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('enrollment_type'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hidden" id="eType_new">
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <div class="note">
                                                  <p style="font-family: Arial, sans-serif;"><strong>Note:</strong> All fields with <strong style="color: red !important;">*</strong> must be filled up.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4 form-group">
                                                <input type="hidden" id="classname" name="classname" value="">
                                                <label for="class_id" class="control-label  small"><?php echo $this->lang->line('enrolling_for'); ?></label><small class="req"> *</small>
                                                <select  id="class_id" name="class_id" class="form-control all-fields" onchange="SetClassName(this)">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($classlist as $class) { ?>
                                                        <option value="<?php echo $class['id'] ?>"<?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                            </div>
                                            <div class="col-md-4 col-xs-4 form-group">
                                                <label for="payment_scheme" class="control-label small"><?php echo $this->lang->line('payment_scheme'); ?></label><small class='req'> *</small>
                                                <select id="payment_scheme" name="payment_scheme" class="form-control all-fields">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($payment_scheme_list as $pscheme) { ?>
                                                        <option value="<?php echo $pscheme['scheme'] ?>"<?php if (set_value('payment_scheme') == $pscheme['scheme']) echo "selected=selected" ?>><?php echo $pscheme['description'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('payment_scheme'); ?></span>
                                            </div>
                                            <div class="col-md-4 col-xs-4 form-group">
                                                <label for="mode_of_payment" class="control-label small"><?php echo $this->lang->line('mode_of_payment'); ?></label><small class='req'> *</small>
                                                <select id="mode_of_payment" name="mode_of_payment" class="form-control all-fields">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($payment_mode_list as $pmode) { ?>
                                                        <option value="<?php echo $pmode['mode'] ?>"<?php if (set_value('mode_of_payment') == $pmode['mode']) echo "selected=selected" ?>><?php echo $pmode['description'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('mode_of_payment'); ?></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4 form-group">
                                                <label for="firstname" class="control-label small"><?php echo $this->lang->line('first_name'); ?></label><small class="req"> *</small> 
                                                <input id="firstname" name="firstname" placeholder="Firstname" type="text" class="form-control all-fields"  value="<?php echo set_value('firstname'); ?>" autocomplete="off"/>
                                                <span class="text-danger"><?php echo form_error('firstname'); ?></span> 
                                            </div>
                                            <div class="col-md-4 col-xs-4 form-group">
                                                <label for="middlename" class="control-label small"><?php echo $this->lang->line('middle_name'); ?></label><small class="req"> *</small> 
                                                <input id="middlename" name="middlename" placeholder="Middlename" type="text" class="form-control all-fields"  value="<?php echo set_value('middlename'); ?>" autocomplete="off"/>
                                                <span class="text-danger"><?php echo form_error('middlename'); ?></span>
                                            </div>
                                            <div class="col-md-4 col-xs-4 form-group">
                                                <label for="lastname" class="control-label small"><?php echo $this->lang->line('last_name'); ?></label><small class="req"> *</small> 
                                                <input id="lastname" name="lastname" placeholder="Lastname" type="text" class="form-control all-fields"  value="<?php echo set_value('lastname'); ?>" autocomplete="off"/>
                                                <span class="text-danger"><?php echo form_error('lastname'); ?></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4 form-group">
                                                <label for="gender" class="control-label small"> <?php echo $this->lang->line('gender'); ?></label><small class="req"> *</small> 
                                                <select class="form-control all-fields" name="gender" id="gender">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($genderList as $key => $value) { ?>
                                                        <option value="<?php echo strtolower($key); ?>" <?php if (strtolower(set_value('gender')) == strtolower($key)) echo "selected"; ?>><?php echo $value; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('gender'); ?></span>
                                            </div>
                                            <div class="col-md-4 col-xs-4 form-group">
                                                <label for="dob" class="control-label small"><?php echo $this->lang->line('date_of_birth'); ?></label><small class="req"> *</small> 
                                                <input  type="date" class="form-control all-fields"  value="<?php echo set_value('dob'); ?>" id="dob" name="dob" autocomplete="off"/>
                                                <span class="text-danger"><?php echo form_error('dob'); ?></span>
                                            </div>
                                            <div class="col-md-4 col-xs-4 form-group">
                                                <label for="email" class="control-label small"><?php echo $this->lang->line('email'); ?></label><small class="req"> *</small>
                                                <input id="email" name="email" placeholder="juandelacruz@email.com" type="text" class="form-control all-fields"  value="<?php echo set_value('email'); ?>" autocomplete="off"/>
                                                <span class="text-danger"><?php echo form_error('email'); ?></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4 form-group">
                                                <label for="document" class="control-label small"> <?php echo $this->lang->line('upload')." ".$this->lang->line('documents');?></label>
                                                <input id="document" name="document[]"  type="file" multiple class="form-control all-fields"  value="<?php echo set_value('document'); ?>" />
                                                <span class="text-danger"><?php echo form_error('document'); ?></span>
                                            </div>
                                            <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1"> <?php echo $this->lang->line('upload')." ".$this->lang->line('documents');?></label>
                <input id="document" name="document[]"  type="file" multiple class="form-control all-fields"  value="<?php echo set_value('document'); ?>" />
                <span class="text-danger"><?php echo form_error('document'); ?></span>
            </div>
        </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12 form-group">
                                                <button type="submit" class="col-md-3 col-xs-3 btn btn-info pull-right" id="save">Enroll</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hidden" id="eType_old">
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <div class="note">
                                                  <p style="font-family: Arial, sans-serif;"><strong>Note:</strong> Formar for Student Name: <i>"First Name &lt;&#39;space&#39;&gt; Last Name"</i></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-xs-6 form-group">
                                                <label for="search" class="control-label small">Student ID / Student Name:</label>
                                                <div class="input-group">
                                                    <input id="studentidnumber" name="studentidnumber" placeholder="Enter ID / Name" type="text" class="form-control all-fields" autocomplete="off"/>
                                                    <span class="text-danger"><?php echo form_error('studentidnumber'); ?></span>
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-default" type="button" onclick="searchStudent();"><i class="glyphicon glyphicon-search"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row table-responsive">
                                            <div class="col-md-12 col-xs-12">
                                                <table class="table table-condensed">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Grade Level</th>
                                                            <th>Birth Date</th>
                                                            <th>Enlistment Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="result">
                                                        <tr class="note" id="NoResult">
                                                            <td colspan="5" style="text-align: center"  >No Record(s) Found</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- The Modal -->
<div id="myModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <span class="close">&times;</span>
        <p>Some text in the Modal..</p>
    </div>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->

<script type="text/javascript">
    function DoOnChange(sel) {
        $('.text-danger').html('');
        $('.alert').alert('close');

        //ClearEntries();

        if (sel.value == "old_new" || sel.value == "returnee") {
            $('#importFile').addClass('hidden');
            $('#eType_old').removeClass('hidden');
            $('#eType_new').addClass('hidden');
            $('#studentidnumber').prop('disabled', false);
        } else if (sel.value == "new" || sel.value == "transferee") {
            $('#importFile').removeClass('hidden');
            $('#eType_old').addClass('hidden');
            $('#eType_new').removeClass('hidden');
            $('#studentidnumber').prop('disabled', true);
        } else {
            $('#importFile').addClass('hidden');
        }
    }
    
    
    $('.close').onclick = function(){
        $('#myModal').style.display = "none";
    }
    
    function searchStudent() {
        var search = $('#studentidnumber').val();
        
        $.ajax({
            url: '<?php echo base_url()."welcome/searchStudentDetailsByFullnameId"; ?>',
            type: 'post',
            dataType: "json",
            data: {search},
            success: function(data) {
                console.log(data);
                if(data){
                    var fname = data[0]['firstname'].charAt(0).toUpperCase() + data[0]['firstname'].slice(1);
                    var mname = data[0]['middlename'].slice(0, 1).toUpperCase();
                    var lname = data[0]['lastname'].charAt(0).toUpperCase() + data[0]['lastname'].slice(1);

                    $('#NoResult').addClass('hidden');
                    $('#result').append('<tr>'
                        +'<td>' + lname+', '+fname+' '+ mname+ '.' + '</td>'
                        +'<td>' + data[0]['class'] + '</td>'
                        +'<td>' + data[0]['dob'] + '</td>'
                        +'<td><label style="color: red !important; "><span class="glyphicon glyphicon-remove"> </span> Not Enlisted</label></td>'
                        +'<td>'
                            //+'<button type="button" class="col-md-3 col-xs-3 btn btn-info" id="view">View</button>'
                            +'<div class="btn btn-default"><a href="http://localhost/aquila/student/viewDetails?id='+ data[0]['id'] + '" >View</a></div>'
                        +'</td>'
                     +'</tr>');
                } else {
                    $('#NoResult').removeClass('hidden');
                }
                
            }
        });
    }
    function viewBtn(){
        $('#myModal').style.display = "block";
    }
</script>