<?php 
include 'includes/header.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
          <div class="row">
            <div class="col-xl-12 col-md-12 mb-4">
              <h1 class="h5 mb-0 text-gray-800">Attendance</h1>
            </div>
            <div class="col-md-12">
            <div id="divAlert"  class="alert alert-success" role="alert" style="display: none; position: fixed;top: 100px; /*width: 100%;*/ right: 2%;z-index: 99;">
                <span class="msg"></span>
            </div>
               <div id="names"></div> 
            </div>

            

            

            
          </div>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->

      <script>
           setInterval(function set(){
                $.ajax({
                  type: 'post',
                  url:'../helpers/ajax_get.php',
                  success: function(data){
                    // console.log(data);
                    $('#names').html(data)
                    console.log("geting data");
                  },
                  error: function(data){
                    console.log(data);
                  }
                });
            },5000);
        </script>
<?php 
include 'includes/footer.php';
 ?>