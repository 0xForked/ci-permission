<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view("_partials/head.php") ?>
    </head>
    <body>
        <?php $this->load->view("_partials/navbar.php") ?>

        <div class="container">
            <div class="text-center" style="margin-top:50px">
                <h1>Edit Role</h1>
            </div>
            <div class="card" style="width:30rem;margin: 0 auto;float: none;margin-top:25px">
                <div class="card-body">
                    <?= form_open_multipart('dash/roles/'.$role->id); ?>
                        <div class="row">
                            <div class="col-12">
                            <div class="form-group">
                                    <label for="title">Title</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="title"
                                        name="title"
                                        value="<?= (set_value('title')) ? set_value('title') : $role->title ?>"
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
                                        value="<?= (set_value('description')) ? set_value('description') : $role->description ?>"
                                    >
                                    <span style="color:red"><?= form_error('description'); ?></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Permission</label>
                                    <div class="row" style="margin:5px">
                                        <?php foreach($permissions as $permission): ?>
                                            <div class="col-sm-12 col-md-4 col-lg-4">
                                                <div class="custom-control custom-checkbox" style="margin-bottom:15px">
                                                    <input
                                                        type="checkbox"
                                                        class="custom-control-input"
                                                        id="permissions<?= $permission->id ?>"
                                                        value="<?= $permission->id ?>"
                                                        name="permissions[]"
                                                        <?= in_array($permission->id, $role_has_permission) ? 'checked' : '' ?>
                                                    >
                                                    <label
                                                        class="custom-control-label"
                                                        for="permissions<?= $permission->id ?>"
                                                    >
                                                        <?= $permission->title ?>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach;?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="float:right">Update</button>
                    <?= form_close(); ?>
                </div>
            </div>

        </div>

        <?php $this->load->view("_partials/footer.php") ?>
    </body>
</html>