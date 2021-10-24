<div class="row" id="section-to-print">
    <div class="col-md-4">
        <canvas id="myChart" width="100" height="100"></canvas>
    </div>
</div>
<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [
                'Anggota',
                'Buku',
            ],
            datasets: [{
                label: 'Perpus',
                data: [0, 0],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                ],
                hoverOffset: 4
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    setInterval(() => {
        fetch("<?= url('api/chart') ?>").then(res => res.json()).then(response => {
            myChart.data.datasets[0].data = response.data;
            myChart.update();
        })
    }, 1000);
</script>