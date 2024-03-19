<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Alasan</title>
    <style>
    table {
        border-collapse: collapse;
        font-family: sans-serif;
        font-size: .8rem;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 3px;
    }
    </style>

    <title>Export Google Chart to PDF using PHP with DomPDF</title>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load('current', {
        'packages': ['corechart']
    });

    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Gender', 'Number'],
            ['Laki-Laki', 50],
            ['Perempuan', 50]

        ]);

        var options = {
            title: 'Percentage of Male and Female Employee',
            pieHole: 0.4,
            chartArea: {
                left: 100,
                top: 70,
                width: '100%',
                height: '80%'
            }
        };
        var chart_area = document.getElementById('piechart');
        var chart = new google.visualization.PieChart(chart_area);

        google.visualization.events.addListener(chart, 'ready', function() {
            chart_area.innerHTML = '<img src="' + chart.getImageURI() + '" class="img-responsive">';
        });
        chart.draw(data, options);
    }
    </script>

    <script type="text/javascript">
    google.charts.load('current', {
        'packages': ['corechart']
    });

    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Gender', 'Number'],
            ['Laki-Laki', 20],
            ['Perempuan', 80]

        ]);

        var options = {
            title: 'Percentage of Male and Female Employee',
            pieHole: 0.4,
            chartArea: {
                left: 100,
                top: 70,
                width: '100%',
                height: '80%'
            }
        };
        var chart_area = document.getElementById('pie');
        var chart = new google.visualization.PieChart(chart_area);

        google.visualization.events.addListener(chart, 'ready', function() {
            chart_area.innerHTML = '<img src="' + chart.getImageURI() + '" class="img-responsive">';
        });
        chart.draw(data, options);
    }
    </script>
</head>

<body>
    <div class="container" id="testing">
        <div id="piechart" style="width: 100%; max-width:900px; height: 500px; "></div>
        <div id="pie" style="width: 100%; max-width:900px; height: 500px; "></div>
    </div>
    <br />
    <div align="center">
        <form method="post" id="make_pdf"
            action="<?php echo base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/laporan-survey/cetak' ?>">
            <input type="hidden" name="hidden_html" id="hidden_html" />
            <button type="button" name="create_pdf" id="create_pdf" class="btn btn-danger btn-xs">Make PDF</button>
        </form>
    </div>
</body>
<script>
$(document).ready(function() {
    $('#create_pdf').click(function() {
        $('#hidden_html').val($('#testing').html());
        $('#make_pdf').submit();
    });
});
</script>

</html>