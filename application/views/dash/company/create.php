<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view("_partials/head.php") ?>
    </head>
    <body>
        <?php $this->load->view("_partials/navbar.php") ?>

        <div class="container">
            <div class="text-center" style="margin-top:50px">
                <h1>Create new Company</h1>
            </div>
            <div class="card" style="width:30rem;margin: 0 auto;float: none;margin-top:25px">
                <div class="card-body">
                    <?= form_open_multipart('dash/companies'); ?>
                        <div class="row">
                            <div class="col-12">
                            <div class="form-group">
                                    <label for="title">Title</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="title"
                                        name="title"
                                        value="<?= set_value('title'); ?>"
                                    >
                                    <span style="color:red"><?= form_error('title'); ?></span>
                                </div>
                            </div>
                            <div class="col-12">
                            <div class="form-group">
                                    <label for="description">Description</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="description"
                                        name="description"
                                        value="<?= set_value('description'); ?>"
                                    >
                                    <span style="color:red"><?= form_error('description'); ?></span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="float:right">Create</button>
                    <?= form_close(); ?>
                </div>
            </div>

        </div>

        <?php $this->load->view("_partials/footer.php") ?>
    </body>
</html>