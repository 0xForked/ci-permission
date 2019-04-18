<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view("_partials/head.php") ?>
    </head>
    <body>
        <?php $this->load->view("_partials/navbar.php") ?>

        <div class="container" style="margin-top:30px">
            <h1>Hello,  <?= $this->auth->username() ?>!</h1>
            <h4>Welcome to Admin Page!</h4>
        </div>

        <?php $this->load->view("_partials/footer.php") ?>
    </body>
</html>