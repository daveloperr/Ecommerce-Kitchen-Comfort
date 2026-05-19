<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>

  <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <style>
      body{
        background-color: #f0f2f5;
      }
</style>
</head>

<body>
  <br><br>
    <form action="../includes/login.inc.php" method="post">

    <section class="p-3 p-md-4 p-xl-5">
    <div class="container" style="width: 500px">
      <div class="card border-light-subtle shadow-sm">
            <div class="card-body p-3 p-md-4 p-xl-5" >
              <div class="row">
                <div class="col-12">
                  <div class="mb-5">
                    <h2 class="h3">Login</h2>
                  </div>
                </div>
              </div>
              <form action="#!">
                <div class="row gy-3 gy-md-4 overflow-hidden">
                  <div class="col-12">
                    <label for="uname" class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Username"  required>
                  </div>
                  <div class="col-12">
                    <label for="pword" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="password" id="password" value="" required>
                  </div>
                  <div class="col-12">
                    <div class="d-grid">
                      <button class="btn bsb-btn-xl btn-success" type="submit" name="loginbtn" value="Login">Login In</button>
                    </div>
                  </div>
                </div>
              <div class="row">
                <div class="col-12">
                  <hr class="mt-5 mb-4 border-secondary-subtle">
                  <p class="m-0 text-secondary text-center">Don't have an account? <a href="register.php" class="link-primary text-decoration-none">Sign Up</a></p>
                </div>
              </div>
                </div>
      </div>
    </div>
  </section>

  </form>

  
 
</body>
</html>