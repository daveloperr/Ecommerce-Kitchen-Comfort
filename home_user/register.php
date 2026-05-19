<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registration</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<style>
  body{
    background-color: #f0f2f5;
  }
</style>
</head>

<body class="center">

    <form action="../includes/upload.inc.php" method="post" enctype="multipart/form-data">
    <section class="p-3 p-md-4 p-xl-5">
    <div class="container" style="width: 500px">
      <div class="card border-light-subtle shadow-sm">
            <div class="card-body p-3 p-md-4 p-xl-5" >
              <div class="row">
                <div class="col-12">
                  <div class="mb-5">
                    <h2 class="h3">Registration</h2>
                    <h3 class="fs-6 fw-normal text-secondary m-0">Enter your details to register</h3>
                  </div>
                </div>
              </div>
              <form action="#!">
                <div class="row gy-3 gy-md-4 overflow-hidden">
                  <div class="col-12">
                    <label for="uname" class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="uname" id="uname" placeholder="Username"  required>
                  </div>
                  <div class="col-12">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
                  </div>
                  <div class="col-12">
                    <label for="pword" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="pword" id="pword" value="" required>
                  </div>
                  <div class="col-12">
                    <label for="cpword" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="cpword" id="cpword" value="" required>
                  </div>
                  <label for="fileupload">Select Image:</label>
                  <input type="file" name="fileupload" id="fileupload">
                  <div class="col-12">
                    <div class="d-grid">
                      <button class="btn bsb-btn-xl btn-success" type="submit" name="registerbtn" value="Register">Sign up</button>
                    </div>
                  </div>
                </div>
              <div class="row">
                <div class="col-12">
                  <hr class="mt-5 mb-4 border-secondary-subtle">
                  <p class="m-0 text-secondary text-center">Already have an account? <a href="../home_user/login.php" class="link-primary text-decoration-none">Sign in</a></p>
                </div>
              </div>
                </div>
      </div>
    </div>
  </section>
  </form>
 
</body>
</html>