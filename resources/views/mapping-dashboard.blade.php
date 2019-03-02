
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
  <link href="{!! asset('css/dataTables.bootstrap4.min.css') !!}" rel="stylesheet">
  <style>
    table td.nowrap {
      white-space: nowrap;
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

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <div class="navbar-brand">Configuration</div>
        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
          @if(Session::has("error-message"))
            <div class="alert alert-danger">{{ Session::get("error-message")  }}</div>
          @endif
          <!-- Page Heading -->
           <!-- DataTales Example start -->
          <div class="card shadow mb-4" style="font-size: 13px">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="mappintTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>SlNo</th>
                      <th>Category</th>
                      <th>Sub Category</th>
                      <th>API</th>
                      <th>Edit</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>SlNo</th>
                      <th>Category</th>
                      <th>Sub Category</th>
                      <th>API</th>
                      <th>Edit</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php
                    if(!empty($data)) {
                      $i = 1;
                      foreach($data as $key => $val) {
                     ?>
                    <tr>
                      <td class="nowrap"><?php echo $i; ?></td>
                      <td class="nowrap"><?php echo $val->Category; ?></td>
                      <td class="nowrap"><?php echo $val->SubCat; ?></td>
                      <td>
                        <code><?php echo $val->api_endpoint; ?></code>
                      </td>
                      </td>
                      <td><a href="/create-mapping?id=<?php echo $val->id; ?>">Edit</a></td>
                    </tr>
                    <?php $i++; } } ?>

                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- DataTales Example End -->

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
  <script src="{!! asset('js/jquery.dataTables.min.js') !!}"></script>
  <script src="{!! asset('js/dataTables.bootstrap4.min.js') !!}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#mappintTable').DataTable({
      "scrollX": true,
      "paging":   false,
      "info":     false,
      "ordering": false,
    });
});
</script>  
</body>
</html>
