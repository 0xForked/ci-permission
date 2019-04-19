<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view("_partials/head.php") ?>
    </head>
    <body>
        <?php $this->load->view("_partials/navbar.php") ?>

        <div class="container">
            <div class="text-center" style="margin-top:50px">
                <h1>Update User</h1>
            </div>
            <div class="card" style="width:50rem;margin: 0 auto;float: none;margin-top:25px">
                <div class="card-body">
                    <?= form_open_multipart('dash/users/'.$user->id); ?>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="first_name">First name</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="first_name"
                                        name="first_name"
                                        value="<?= (set_value('first_name')) ? set_value('first_name') : $user->first_name ?>"
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
                                        value="<?= (set_value('last_name')) ? set_value('last_name') : $user->last_name ?>"
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
                                        value="<?= (set_value('username')) ? set_value('username') : $user->username ?>"
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
                                        placeholder="name@example.com"
                                        value="<?= (set_value('email')) ? set_value('email') : $user->email ?>"
                                        readonly
                                    >
                                    <span style="color:red"><?= form_error('email'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="role">Company</label>
                                    <select class="form-control" id="company" name="company">
                                        <?php if (hasRole(['root', 'vendor'])): ?>
                                        <option value="0" <?= ($user->company_id === null) ? 'selected' : ' '?>>Not Set</option>
                                        <?php endif; ?>
                                        <?php foreach($companies as $company): ?>
                                            <option value="<?= $company->id ?>" <?= ($company->id === $user->company_id) ? 'selected' : ' '?>><?= $company->title ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select class="form-control" id="role" name="role">
                                       <?php foreach($roles as $role): ?>
                                            <option value="<?= $role->id ?>" <?= in_array($role->id, $user_has_role) ? 'selected' : ' ' ?>><?= $role->title ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="1" <?= ((int)$user->active === 1) ? 'selected' : ' ' ?>>Active</option>
                                        <option value="0" <?= ((int)$user->active === 0) ? 'selected' : ' ' ?>>Deactive</option>
                                    </select>
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