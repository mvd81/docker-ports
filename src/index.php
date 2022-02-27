<?php

set_time_limit(0);
include 'vendor/autoload.php';
include "config.php";
include 'ScanDockerPorts.php';

$dockerPorts = new ScanDockerPorts($config);
$result = $dockerPorts->getPorts();

?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/css/theme.default.min.css" integrity="sha512-wghhOJkjQX0Lh3NSWvNKeZ0ZpNn+SPVXX1Qyc9OCaogADktxrBiBdKGDoqVUOyhStvMBmJQ8ZdMHiR3wuEq8+w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/css/theme.dark.min.css" integrity="sha512-nc1pKg6wCivxMCLNT7Intf8DfGGN34QbjjU/5hLixwYHzAofenG0KxhbCAZS/oYibU37I/OR/FUgyY+Kd7zE1g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        table.tablesorter tr.focus td {
            background: steelblue !important;
        }
    </style>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.combined.min.js" integrity="sha512-xFKr8IKEr6c+C4NJ5Ajbfy/vWg4LFD/jLUtY+hSO8WX1+eNAWEV4Rn9ovme8C+9DY7mD8XMQkO4qYYOjMAJOWA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function () {

            $("#ports").tablesorter({
                theme : 'dark', // use theme.jui.css
                widgets : ['zebra']
            });

            $('#ports td').each(function(){
                if ($(this).text() == '0') {
                    $(this).text('-');
                }
            });
        });
    </script>

    <title>Docker ports</title>
</head>
<body class="bg-dark">
<div class="container">

    <h1 class="text-white d-flex my-2 align-items-center">
        <img preserveAspectRatio="none" src="/assets/DockerLogo.png" height="50" class="inline">
        <span class="pt-1 pl-1">Ports</span>
    </h1>

    <table class="table" id="ports">
        <thead>
            <th>Project</th>
            <?php foreach ($config['services'] as $serviceName => $service) { ?>
                <th><?php print $serviceName; ?></th>
            <?php } ?>
        </thead>
        <tbody>

            <tr class="focus">
                <?php
                    foreach ($dockerPorts->getNextHighestPorts($result) as $port) { ?>
                        <td><?php print $port; ?></td>
                    <?php } ?>
            </tr>

            <?php foreach ($result as $location => $ports) { ?>
                <tr>
                    <td><?php print str_replace($config['rootDir'] . '/', '', $location); ?></td>
                    <?php foreach ($ports as $port) { ?>
                        <td><?php print $port; ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>

        </tbody>
    </table>

</div>


</body>
</html>
