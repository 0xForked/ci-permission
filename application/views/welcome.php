<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view("_partials/head.php") ?>
    </head>
    <body>
        <?php $this->load->view("_partials/navbar.php") ?>

        <div class="container">
            <div class="text-center" style="margin-top:50px">
                <h1>Welcome to CI-Permission Example</h1>
                <p>
                    This project is example of user auth and user management with permission and role grant
                    </br><a href="https://github.com/aasumitro/ci-permission-example">Repository</a> &#8226;
                    <a href="https://github.com/aasumitro/ci-permission-example/blob/development/readme.md">Documentation</a> &#8226;
                    <a href="https://aasumitro.id">@author</a>
                    </br>Page rendered in <strong>{elapsed_time}</strong> seconds.
                </p>
            </div>
        </div>

        <?php $this->load->view("_partials/footer.php") ?>
    </body>
</html>