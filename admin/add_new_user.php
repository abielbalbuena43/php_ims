<?php
include "header.php";
include "../user/connection.php";
?>
<!--main-container-part-->
<div id="content">
    <!--breadcrumbs-->
    <div id="content-header">
        <div id="breadcrumb"><a href="index.html" class="tip-bottom"><i class="icon-home"></i>
            Add New User</a></div>
    </div>
    <!--End-breadcrumbs-->

    <!--Action boxes-->
    <div class="container-fluid">

        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
        <div class="span12">
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Add New User</h5>
        </div>
        <div class="widget-content nopadding">
          <form name="form1" action="" method="post" class="form-horizontal">
            <div class="control-group">
              <label class="control-label">First Name :</label>
              <div class="controls">
                <input type="text" class="span11" placeholder="First name" name="firstname" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Last Name :</label>
              <div class="controls">
                <input type="text" class="span11" placeholder="Last name" name="lastname"/>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Username :</label>
              <div class="controls">
                <input type="text" class="span11" placeholder="User name" name="username"/>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Password</label>
              <div class="controls">
                <input type="password"  class="span11" placeholder="Enter Password" name="password" />
              </div>
            </div>
            
            <div class="control-group">
              <label class="control-label">Select Role</label>
              <div class="controls">
                <select name ="role" class="span11">
                    <option>user</option>
                    <option>admin</option>
                </select>
              </div>
            </div>

            <div class="alert alert-danger" id="error" style="display:none">
                Username already exists!
              </div>

              <div class="alert alert-success" id="success" style="display:none">
                Record inserted successfully!
              </div> 

            <div class="form-actions">
              <button type="submit" name="submit1" class="btn btn-success" >Save</button>
            </div>

              

          </form>
        </div>
      </div>
      
    </div>
        </div>

    </div>
</div>

<?php
if (isset($_POST["submit1"]))
{
  $count=0;
  $res=mysqli_query($link,"SELECT * FROM user_registration WHERE username='$_POST[username]'");
  $count=mysqli_num_rows($res);

  if($count>0)
  {
    ?>
        <script type="text/javascript">
          document.getElementById("success").style.display="none";
          document.getElementById("error").style.display="block";
        </script>
    <?php
  } 
  else {
    mysqli_query($link, "insert into user_registration values(NULL,'$_POST[firstname]','$_POST[lastname]','$_POST[username]','$_POST[password]',
    '$_POST[role]','active')");

    ?>
        <script type="text/javascript">
          document.getElementById("error").style.display="none";
          document.getElementById("success").style.display="none";
        </script>
    <?php
}

} 
?>


<!--end-main-container-part-->

<?php
include "footer.php"
?>