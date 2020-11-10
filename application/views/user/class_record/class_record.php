<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1><i class="fa fa-calendar-times-o"></i> <?php echo $this->lang->line('class_record'); ?> </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-warning">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('class_record'); ?></h3>
                        <div class="box-tools pull-right"></div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('class_ticlass_recordmetable'); ?></div>
                            <?php //if (!empty($resultlist)) { ?>
                                <table id="class_record" class="table table-stripped display nowrap">
                                    <thead>
                                        <tr>
                                            <th class="text-left">Subjects</th>
                                            <?php
                                                foreach($quarter_list as $row) {
                                                    echo "<th class=\"text-center\">".$row->description."</th>\r\n";
                                                }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php                                 
                                        foreach($resultlist as $row) {
                                            $ctr=0;
                                            echo "<tr>\r\n";
                                            foreach($row as $val) {
                                                if ($ctr==0)
                                                    echo "<td class='text-left'>".$val."</td>\r\n";
                                                else 
                                                    echo "<td class='text-center'>".$val."</td>\r\n";
                                                $ctr++;
                                            }
                                            echo "</tr>\r\n";
                                        } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Average</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            <?php //} ?>                            
                        </div>                     
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $("#class_record").DataTable({
            "paging" : false,
            "scrollX": true,
            "fixedHeader": true,
            "dom": 'Bfrtip',
            buttons: [
                {
                    extend: 'copyHtml5',
                    footer: 'true',
                    text: '<i class="fa fa-files-o"></i>',
                    titleAttr: 'Copy',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    footer: 'true',
                    text : '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'XLS',
                    customize: function (xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    
                        $('c[r=A1] t', sheet).text('Class Record');
                    },
                    filename: function() {
                        var d = new Date();
                        var n = d.getTime();
                        return 'Class Record' + '-' + n;
                    }, 
                },

                {
                    extend: 'csvHtml5',
                    footer: 'true',
                    text: '<i class="fa fa-file-text-o"></i>',
                    titleAttr: 'CSV',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'
                    },
                    filename: function() {
                        var d = new Date();
                        var n = d.getTime();
                        return 'Class Record' + '-' + n;
                    },
                },

                {
                    extend: 'pdfHtml5',
                    footer: 'true',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'PDF',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'
                    },
                    filename: function() {
                        var d = new Date();
                        var n = d.getTime();
                        return 'Class Record' + '-' + n;
                    },
                },

                {
                    extend: 'print',
                    footer: 'true',
                    text: '<i class="fa fa-print"></i>',
                    titleAttr: 'Print',
                    title: $('.download_label').html(),
                    customize: function (win) {
                        $(win.document.body)
                                .css('font-size', '10pt');

                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                    },
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'colvis',
                    footer: 'true',
                    text: '<i class="fa fa-columns"></i>',
                    titleAttr: 'Columns',
                    title: $('.download_label').html(),
                    postfixButtons: ['colvisRestore']
                },                
            ],
            "footerCallback": function (settings, json) {
                var api = this.api(), data;
    
                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                };

                // // computing column Total the complete result 
                var colCount = $('#class_record').DataTable().columns().header().length;
                var quarters = [];

                for(let i=1; i<colCount; i++) {
                    var quarter = api
                    .column(i)
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    quarters.push(quarter);
                }

                for(let ii=1; ii<colCount; ii++) {
                    let rows = $('#class_record').DataTable().data().count()/colCount;
                    $(api.column(ii).footer()).html((quarters[ii-1]/rows).toFixed(2));
                }
            }
        });
    });

</script>