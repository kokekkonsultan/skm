<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <link href="<?= base_url('assets/') ?>survey/opening/assets/vendor/bootstrap/css/bootstrap.min.css"
        rel="stylesheet">
    <link href="<?= base_url('assets/') ?>survey/opening/assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style type="text/css">
    body {
        background: url('<?= base_url('assets/') ?>survey/opening/assets/img/hero-bg.png') no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        background-size: cover;
        -o-background-size: cover;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-md-center header">
            <div class="col col-lg-8 mt-5">

                <div class="card shadow animate__animated animate__bounce" style="border-radius: 25px; padding: 20px; ">
                    <div class="row">
                        <div class="container d-flex flex-column align-items-center justify-content-center">
                            <img src="<?php echo base_url('assets/') ?>survey/img/survey-end.png"
                                style="weight:300; height:300px;">
                            <br>
                            <br>
                            <div class="text-center">
                                <div class="text-secondary" style="font-size: 20px;">Mohon maaf, Survei
                                    <b><?php echo $judul->survey_name ?></b> sudah berakhir.
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                </br>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
</body>

</html>