<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view("_partials/head.php") ?>
    </head>
    <body>
        <?php $this->load->view("_partials/navbar.php") ?>

        <div class="container">
            <table class="table" style="margin-top:50px">
                <thead class="thead-dark">
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Company</th>
                    <th scope="col">Role</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $number = 1 ?>
                    <?php foreach($users as $user): ?>
                        <tr>
                            <th scope="row"><?= $number ?></th>
                            <td><?= $user->username ?></td>
                            <td><?= $user->email ?></td>
                            <td>
                                <?php if ((String)$user->role->title == 'root'): echo 'seanesia' ?>
                                <?php elseif ((String)$user->role->title == 'vendor'): echo 'pelindo' ?>
                                <?php elseif ((String)$user->role->title == 'member'): echo 'customer' ?>
                                <?php else: echo $user->company->title ?><?php endif ?>
                            </td>
                            <td>
                                <div class="badge
                                    <?php if ((String)$user->role->title == 'root'): echo 'badge-danger' ?>
                                    <?php elseif ((String)$user->role->title == 'vendor'): echo 'badge-warning' ?>
                                    <?php elseif ((String)$user->role->title == 'admin'): echo 'badge-primary' ?>
                                    <?php else: echo 'badge-secondary' ?><?php endif ?>
                                ">
                                   <?= $user->role->title ?>
                                </div>
                            </td>
                            <td>
                                <?= (isActive($user->active)) ? 'active' : 'inactive' ?>
                            </td>
                            <td>
                                <?php if(isActive($user->active)): ?>
                                    <!-- deactive user -->
                                    <a href="<?= base_url() ?>dash/users/<?= $user->id ?>/deactive"><i class="fas fa-user-alt-slash"></i></a>
                                <?php endif; ?>
                                <a href="<?= base_url() ?>dash/users/<?= $user->id ?>/edit"><i class="fas fa-edit"></i></a>
                                <a href="<?= base_url() ?>dash/users/<?= $user->id ?>"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                        <?php $number++ ?>
                    <?php endforeach; ?>
                </tbody>
            </table>

            </table>
        </div>

        <?php $this->load->view("_partials/footer.php") ?>
    </body>
</html>