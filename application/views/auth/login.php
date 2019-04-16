<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view("_partials/head.php") ?>
    </head>
    <body>
        <?php $this->load->view("_partials/navbar.php") ?>

        <div class="container">
            <div class="text-center" style="margin-top:50px">
                <h1>Sign In!</h1>
            </div>
            <div class="card" style="width:21rem;margin: 0 auto;float: none;margin-top:25px">
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="identity">Identity</label>
                                    <input type="text" class="form-control" id="identity" value="email/username">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password">
                                </div>
                            </div>
                        </div>
                        <a href="/auth/password/forgot">Forgot password?</a>
                        <button type="submit" class="btn btn-primary" style="float:right">Sign In</button>
                    </form>
                </div>
            </div>

        </div>

        <?php $this->load->view("_partials/footer.php") ?>
    </body>
</html>