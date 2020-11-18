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
                    <div class="">
                        <div class="bozero">
                            <h4 class="pagetitleh-whitebg"><?= $title; ?></h4>
                            <div class="around10">
                                <form method="post" action="saveAdmission">
                                    <input type="text" name="enrollmentType" id="enrollmentType" value="old" hidden />
                                    <div class="row" style="margin-bottom: 5px;">
                                        <div class="form-group">
                                            <label class="col-md-2 col-xs-2 control-label" for="class_id"><?php echo $this->lang->line('enrolling_for'); ?><small class='req'> *</small></label>
                                            <div class="col-md-6 col-xs-6">
                                                <select  id="class_id" name="class_id" class="form-control all-fields" onchange="SetClassName(this)" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($classlist as $class) { ?>
                                                        <option value="<?php echo $class['id'] ?>"<?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 5px;">
                                        <div class="form-group">
                                            <label class="col-md-2 col-xs-2 control-label" for="payment_scheme"><?php echo $this->lang->line('payment_scheme'); ?><small class='req'> *</small></label>
                                            <div class="col-md-6 col-xs-6">
                                                <select id="payment_scheme" name="payment_scheme" class="form-control all-fields" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($payment_scheme_list as $pscheme) { ?>
                                                        <option value="<?php echo $pscheme['scheme'] ?>"<?php if (set_value('payment_scheme') == $pscheme['scheme']) echo "selected=selected" ?>><?php echo $pscheme['description'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('payment_scheme'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 5px;">
                                        <div class="form-group">
                                            <label class="col-md-2 col-xs-2 control-label" for="mode_of_payment"><?php echo $this->lang->line('mode_of_payment'); ?><small class='req'> *</small></label>
                                            <div class="col-md-6 col-xs-6">
                                                <select id="mode_of_payment" name="mode_of_payment" class="form-control all-fields" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($payment_mode_list as $pmode) { ?>
                                                        <option value="<?php echo $pmode['mode'] ?>"<?php if (set_value('mode_of_payment') == $pmode['mode']) echo "selected=selected" ?>><?php echo $pmode['description'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('mode_of_payment'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 5px;">
                                        <div class="form-group">
                                            <label class="col-md-2 col-xs-2 control-label" for="firstname">First name</label>
                                            <div class="col-md-6 col-xs-6">
                                                <input class="form-control" id="firstname" name="firstname" readonly="readonly" size="30" type="text" value="<?= ucfirst($content[0]['firstname']); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 5px;">
                                        <div class="form-group">
                                            <label class="col-md-2 col-xs-2 control-label" for="middlename">Middle name</label>
                                            <div class="col-md-6 col-xs-6">
                                                <input class="form-control" id="middlename" name="middlename" readonly="readonly" size="30" type="text" value="<?= ucfirst($content[0]['middlename']); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 5px;">
                                        <div class="form-group">
                                            <label class="col-md-2 col-xs-2 control-label" for="lastname">Last name</label>
                                            <div class="col-md-6 col-xs-6">
                                                <input class="form-control" id="lastname" name="lastname" readonly="readonly" size="30" type="text" value="<?= ucfirst($content[0]['lastname']); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 5px;">
                                        <div class="form-group">
                                            <label class="col-md-2 col-xs-2 control-label" for="gender">Gender</label>
                                            <div class="col-md-6 col-xs-6">
                                                <input class="form-control" id="gender" name="gender" readonly="readonly" size="30" type="text" value="<?= ucfirst($content[0]['gender']); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 5px;">
                                        <div class="form-group">
                                            <label class="col-md-2 col-xs-2 control-label" for="dob">Birth Date</label>
                                            <div class="col-md-6 col-xs-6">
                                                <input class="form-control" id="dob" name="dob" readonly="readonly" size="30" type="text" value="<?= date("F d, Y", strtotime($content[0]['dob'])); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 5px;">
                                        <div class="form-group">
                                            <label class="col-md-2 col-xs-2 control-label" for="email">e-Mail</label>
                                            <div class="col-md-6 col-xs-6">
                                                <input class="form-control" id="email" name="email" readonly="readonly" size="30" type="text" value="<?= $content[0]['email']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8 col-xs-8 form-group" style="padding: 1px;">
                                            <button type="button" class="col-md-2 col-xs-2 btn btn-danger pull-right" id="cancel" onclick="btnCancel();">Cancel</button>
                                            <button type="submit" class="col-md-2 col-xs-2 btn btn-success pull-right" id="save">Enroll</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    function btnCancel()
    {
        var base_url = window.location.origin;
        console.log(window.location)
        location.replace(base_url + "/aquila/student/create");
    }
    
    $(document).on('click', 'form button[id=save]', function(e)
    {
        var classId = $('#class_id').val();
        var scheme = $('#payment_scheme').val();
        var mode = $('#mode_of_payment').val();
        if(classId== "" && scheme =="" && mode =="") {
            alert('Please fill in the Required Field(s).');
            e.preventDefault(); //prevent the default action
        }
    });
</script>
