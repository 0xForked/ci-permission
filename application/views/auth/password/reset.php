<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view("_partials/head.php") ?>
    </head>
    <body>
        <?php $this->load->view("_partials/navbar.php") ?>

        <div class="container">
            <?php if($this->session->flashdata('message')) echo $this->session->flashdata('message');?>
            <div class="text-center" style="margin-top:50px">
                <h1>Reset Password!</h1>
            </div>
            <div class="card" style="width:21rem;margin: 0 auto;float: none;margin-top:25px">
                <div class="card-body">
                    <?= form_open_multipart('auth/password/reset/'.$code); ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="password">New password</label>
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="password"
                                        name="password"
                                    >
                                    <span style="color:red"><?= form_error('password'); ?></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="confirm_password">Confirm password</label>
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="confirm_password"
                                        name="confirm_password"
                                    >
                                    <span style="color:red"><?= form_error('confirm_password'); ?></span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="float:right">Send</button>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>

        <?php $this->load->view("_partials/footer.php") ?>
    </body>
</html>