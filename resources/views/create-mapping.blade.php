<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Quik Compare</title>

    <!-- Custom fonts for this template-->
    <link href="{!! asset('css/all.min.css') !!}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{!! asset('css/sb-admin-2.min.css') !!}" rel="stylesheet">
    <style>
        form, input, select, textarea {
            font-size: 13px !important;
        }
    </style>
</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
            <div class="sidebar-brand-text mx-3">Quik Compare <sup></sup></div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="dashboard">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Configuration
        </div>

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-cog"></i>
                <span>Mapping</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Actions</h6>
                    <a class="collapse-item" href="create-mapping">Add</a>
                    <a class="collapse-item" href="mapping-dashboard">Dashboard</a>
                </div>
            </div>
        </li>

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                <div class="navbar-brand">Add Configuration</div>
            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">
                <!-- Page Heading -->

                <form action="add-mapping" method="post">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="inputPassword">Category</label>
                        <select name="category" id="category" class="form-control" onchange="getSubCategory();">
                            <option value="">---Select Vertical---</option>
                            <?php foreach ($vertical as $key => $value): ?>
                                <option value="<?php echo $value->cat_id; ?>"><?php echo $value->category_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword">Sub Category</label>
                        <select name="sub_category" id="sub_category" class="form-control">
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputPassword">API Endpoint</label>
                                <input type="text" name="api_endpoint" id="api_endpoint" class="form-control" placeholder="API End Point">
                            </div>
                            <div class="form-group">
                                <label for="inputPassword">Test ID</label>
                                <input type="text" name="test_id" id="test_id" class="form-control" placeholder="Test ID">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputPassword">API Headers</label>
                                <textarea name="api_headers" id="api_headers" class="form-control" placeholder="Api Headers" style="height: 110px"></textarea>
                            </div>
                        </div>
                    </div>

                    <?php if(!empty($_GET['id'])): ?>
                        <hr/>
                        <h4 class="h4 mt-4">Mapping</h4>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="inputCity">Field Label</label>
                                <input type="text" class="form-control configLabel" name="configLabel[]" readonly value="Title">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputState">Field Type</label>
                                <select class="form-control configType" name="configType[]">
                                    <option value="attribute" selected>Attribute</option>
                                    <option value="computed">Computed</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4 type-wrapper">
                                <label for="inputState">Field Value</label>
                                <select class="form-control configValue" data-type="attribute" name="configValue[]"></select>
                                <textarea class="form-control" data-type="computed" hidden disabled name="configValue[]" placeholder="Expression"></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="inputCity">Label</label>
                                <input type="text" class="form-control configLabel" name="configLabel[]" readonly value="Banner Image">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputState">Value</label>
                                <select class="form-control configType" name="configType[]">
                                    <option value="attribute">Attribute</option>
                                    <option value="computed">Computed</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4 type-wrapper">
                                <label for="inputState">Value</label>
                                <select class="form-control configValue" data-type="attribute" name="configValue[]"></select>
                                <textarea class="form-control" data-type="computed" hidden disabled name="configValue[]" placeholder="Expression"></textarea>
                            </div>
                        </div>
                        <br>
                        <div id="appendConfig"></div>
                        <div class="form-row">
                            <div class="form-group col-md-2 float-right">
                                <input type="button" class="btn btn-block btn-success" style="margin-top: 19%" value="Add" id="addNew">
                            </div>
                        </div>
                    <?php endif; ?>

                    <input type="hidden" name="mapping_old_id" value="<?php echo !empty($_GET['id']) ? $_GET['id'] : ''; ?>">
                    <input type="submit" class="btn btn-primary" value="submit">
                </form>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Hackathon 2019</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Bootstrap core JavaScript-->
<script src="{!! asset('js/jquery.min.js') !!}"></script>
<script src="{!! asset('js/bootstrap.bundle.min.js') !!}"></script>

<!-- Core plugin JavaScript-->
<script src="{!! asset('js/jquery.easing.min.js') !!}"></script>

<!-- Custom scripts for all pages-->
<script src="{!! asset('js/sb-admin-2.min.js') !!}"></script>


<script type="text/javascript">
    window.data = {};

    function getCategory() {
        var vertical = document.getElementById("vertical");
        var verticalId = vertical.options[vertical.selectedIndex].value;
        let vertical_id =
            $.ajax({
                url : '/api/get-category?vertical_id='+verticalId,
                type: "get",
                dataType: "json",
                success: function(resp) {
                    var genTableData="";
                    if(resp) {
                        var options = "";
                        $.each(resp, function(key_1,value) {
                            options+= '<option value="'+value.id+'">'+value.name+'</option>';
                        });
                        $('#category').html(options);
                    }
                }
            });
    }

    function getSubCategory(callback) {
        var category = document.getElementById("category");
        var categoryId = category.options[category.selectedIndex].value;
        $.ajax({
            url : '/api/get-sub-category?category_id='+categoryId,
            type: "get",
            dataType: "json",
            success: function(resp) {
                var genTableData="";
                if(resp) {
                    var options = "";
                    $.each(resp, function(key_1,value) {
                        options+= '<option value="'+value.subcat_id+'">'+value.subcat_name+'</option>';
                    });
                    $('#sub_category').html(options);

                    if (callback && 'function' === typeof callback) {
                        callback();
                    }
                }
            }
        });
    }

    $(document).ready(function(){
        var url = new URL(window.location.href);
        var id = url.searchParams.get("id");

        if(id != '' && id != null) {
            $.ajax({
                url : '/api/get-mapping-data?id='+id,
                type: "get",
                dataType: "json",
                success: function(resp) {
                    window.data = resp;
                    if(resp) {
                        $('#category option[value='+resp[0].category_id+']').attr('selected','selected');
                        $('#api_endpoint').val(resp[0].api_endpoint);
                        $('#test_id').val(resp[0].test_id);
                        $('#api_headers').val(resp[0].api_headers);

                        getSubCategory(function() {
                            $('#sub_category option[value='+resp[0].sub_category_id+']').attr('selected','selected');
                        });

                        getApiResponseKeys(resp[0].sub_category_id, function() {
                            if(resp[0].config != '') {
                                var config = JSON.parse(resp[0].config);

                                if (config.length > 2) {
                                    var filteredConfigs = config.filter((obj) => {
                                        return obj.displayName.toLowerCase() !== 'banner image' && obj.displayName.toLowerCase() !== 'title';
                                    });

                                    $.each(filteredConfigs, function(key,value) {
                                        loadApiKeys();
                                    });
                                }

                                $(".configLabel").each(function(index) {
                                    $(this).val(config[index].displayName);
                                    // console.log( index + ": ");
                                });

                                $('.configType').each(function (index) {
                                    $(this).find('option').filter(function() {
                                        return $(this).val() === 'computed' && config[index].expression;
                                    }).attr('selected', true);

                                    $(this).find('option').filter(function() {
                                        return $(this).val() === 'attribute' && config[index].attribute;
                                    }).attr('selected', true);
                                });

                                $(".configValue").each(function(index) {
                                    $(this).find('option').filter(function() {
                                        //may want to use $.trim in here
                                        return $(this).val() == config[index].attribute;
                                    }).attr('selected', true);
                                });

                                $(".type-wrapper").each(function(index) {
                                    //$(this).val(config[index].attribute);
                                    var elAttributeFormField = $(this).find('[data-type="attribute"]');
                                    var elExpressionFormField = $(this).find('[data-type="computed"]');

                                    console.log(index);

                                    if (config[index].hasOwnProperty('attribute')) {
                                        elExpressionFormField.attr('hidden', 'hidden');
                                        elExpressionFormField.attr('disabled', 'disabled');

                                        elAttributeFormField.removeAttr('hidden');
                                        elAttributeFormField.removeAttr('disabled');
                                        elAttributeFormField.val(config[index].attribute);
                                    } else if (config[index].hasOwnProperty('expression')) {
                                        elAttributeFormField.attr('hidden', 'hidden');
                                        elAttributeFormField.attr('disabled', 'disabled');

                                        elExpressionFormField.removeAttr('hidden');
                                        elExpressionFormField.removeAttr('disabled');
                                        elExpressionFormField.val(config[index].expression);
                                    }
                                });

                                $(document).on('change', '.configType', (ev) => {
                                    var target = ev.currentTarget;
                                    var elType = $(target).parent().parent().find('[data-type="' + target.value + '"]');
                                    elType.removeAttr('hidden');
                                    elType.removeAttr('disabled');

                                    elType.siblings('.form-control').attr('hidden', 'hidden');
                                    elType.siblings('.form-control').attr('disabled', 'disabled');
                                });
                            }
                        });

                    }
                }
            });
        }



        $(document).on("click","#addNew",function() {
            loadApiKeys();
        });

        $(document).on("click","#delete",function() {
            $(this).parent().parent().remove();
        });

    });

    function loadApiKeys() {
        var html = `
        <div class="form-row">
          <div class="form-group col-md-3">
            <label for="inputCity">Label</label>
            <input type="text" class="form-control configLabel" name="configLabel[]" placeholder="Your Label">
          </div>
            <div class="form-group col-md-3">
                <label for="inputState">Value</label>
                <select class="form-control configType" name="configType[]">
                    <option value="attribute">Attribute</option>
                    <option value="computed">Computed</option>
                </select>
            </div>
          <div class="form-group col-md-4 type-wrapper">
            <label for="inputState">Value</label>
            <select class="form-control configValue" data-type="attribute" name="configValue[]">${window.data.options}</select>
            <textarea class="form-control" data-type="computed" hidden disabled name="configValue[]" placeholder="Expression"></textarea>
          </div>
          <div class="form-group col-md-2 text-right">
            <input type="button" class="btn btn-danger" style="margin-top: 19%" value="Delete" id="delete">
          </div>
        </div>
        <br>
        `;
        $('#appendConfig').append(html);
    }

    function getApiResponseKeys(subCatId, callback) {
        $.ajax({
            url : '/api/get-api-key-pairs?subCatId=' + subCatId,
            type: "get",
            dataType: "json",
            success: function(resp) {
                var options = "";
                if(resp) {
                    $.each(resp, function(key_1,value) {
                        options+= '<option value="'+value+'">'+value+'</option>';
                    });
                    window.data.options = options;
                    $('.configValue').html(options);

                    if (callback && 'function' === typeof callback) {
                        callback();
                    }
                }
            }
        });
    }

</script>

</body>
</html>
