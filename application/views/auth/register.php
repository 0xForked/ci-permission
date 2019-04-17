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
                <h1>Sign Up</h1>
            </div>
            <div class="card" style="width:50rem;margin: 0 auto;float: none;margin-top:25px">
                <div class="card-body">
                    <?= form_open_multipart('auth/register'); ?>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="first_name">First name</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="first_name"
                                        name="first_name"
                                        required
                                        value="<?= set_value('first_name'); ?>"
                                    >
                                    <span style="color:red"><?= form_error('first_name'); ?></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="last_name">Last name</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="last_name"
                                        name="last_name"
                                        required
                                        value="<?= set_value('last_name'); ?>"
                                    >
                                    <span style="color:red"><?= form_error('last_name'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="username"
                                        name="username"
                                        required
                                        value="<?= set_value('username'); ?>"
                                    >
                                    <span style="color:red"><?= form_error('username'); ?></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="email">Email address</label>
                                    <input
                                        type="email"
                                        class="form-control"
                                        id="email"
                                        name="email"
                                        required
                                        placeholder="name@example.com"
                                        value="<?= set_value('email'); ?>"
                                    >
                                    <span style="color:red"><?= form_error('email'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="password"
                                        name="password"
                                    >
                                    <span style="color:red"><?= form_error('password'); ?></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="confirm_password">Confirm Password</label>
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

                        <button type="submit" class="btn btn-primary" style="float:right">Sign Up</button>
                    <?= form_close(); ?>
                </div>
            </div>

        </div>

        <?php $this->load->view("_partials/footer.php") ?>
    </body>
</html>