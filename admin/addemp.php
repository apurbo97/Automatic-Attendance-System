<?php 
include 'includes/header.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Add User</h1>
          </div>

          <!-- Content Row -->
          <div class="row">
            <div id="divAlert"  class="alert alert-success" role="alert" style="display: none; position: fixed;top: 100px; /*width: 100%;*/ right: 2%;z-index: 99;">
              <span class="msg"></span>
            </div><!-- alert -->
                <div class="col-md-12" style="background-color: #ffffff;">
                  <form id="frmid" action="ajax/ajax_addemp.php" method="post" enctype="multipart/form-data">
                    <div class="row">
                      <div class="col-md-4">
                        <label>Enter Name</label>
                        <input type="text" class="form-control" name="name" required="">
                      </div>
                      <div class="col-md-4">
                        <label>Upload Image</label>
                        <input type="file" name="fileToUpload">
                        <br>
                        <span style="color: red;">*Only the face of the user.</span>
                      </div>
                      <div class="col-md-4">
                        <label>&nbsp;</label><br>
                        <button id="btnSave" class="btn btn-primary">Add</button>
                        <input type="reset" class="btn btn-primary" id="btnReset">
                      </div>
                    </div>
                  </form>
                  <br>
                </div>
          </div>
        </div>

        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->
<?php 
include 'includes/footer.php';
 ?>

 <script type="text/javascript">
      $('#frmid').on('submit', function(e){
        e.preventDefault();
        $('#btnSave').html('Please wait...');
          $.ajax({
          type: 'post',
          url:$(this).attr('action'),
          data: new FormData(this),
          contentType: false,
          cache: false,
          processData:false,
          success: function(data){
            // alert(data)
            console.log(data);
            $('#btnSave').html('Add');
            var jsonData = $.parseJSON(data);
            if(jsonData.status === "success"){
              console.log(jsonData);
              $('.alert').removeClass('alert-danger');
              $('.alert').addClass('alert-success');
              $('.alert .msg').text(jsonData.message);
              $('#divAlert').show();
              $('#divAlert').delay(5000).fadeOut(2000);
              setTimeout(window.location.reload(), 3000);
            } 
            else{
              $('.alert').removeClass('alert-success');
              $('.alert').addClass('alert-danger');
              $('.alert .msg').text(jsonData.message);
              console.log(jsonData.err);
              $('#divAlert').show();
              $('#divAlert').delay(5000).fadeOut(2000);
            }
          },
          error: function(data){
            console.log(data);
          }
        });
      });
    </script>
    