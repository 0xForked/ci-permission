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
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $number = 1 ?>
                    <?php foreach($companies as $company): ?>
                        <tr>
                            <th scope="row"><?= $number ?></th>
                            <td><?= $company->title ?></td>
                            <td><?= $company->description ?></td>
                            <td>
                                <a href="<?= base_url() ?>dash/companies/<?= $company->id ?>/edit"><i class="fas fa-edit"></i></a>
                                <a href="<?= base_url() ?>dash/companies/<?= $company->id ?>"><i class="fas fa-trash-alt"></i></a>
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