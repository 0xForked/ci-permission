<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view("_partials/head.php") ?>
    </head>
    <body>
        <?php $this->load->view("_partials/navbar.php") ?>

        <div class="container">
            <div class="text-center" style="margin-top:50px">
                <h1>Forgot Password!</h1>
            </div>
            <div class="card" style="width:21rem;margin: 0 auto;float: none;margin-top:25px">
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="identity">Email</label>
                                    <input type="email" class="form-control" id="identity" value="example@mail.com">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="float:right">Send</button>
                    </form>
                </div>
            </div>

        </div>

        <?php $this->load->view("_partials/footer.php") ?>
    </body>
</html>