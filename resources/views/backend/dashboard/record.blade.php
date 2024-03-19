<div class="row">
    <div class="col-lg-6 col-12">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $total_transactions }}</h3>
                <p>Total Transaction</p>
            </div>
            <div class="icon">
                <i class="fas fa-tags"></i>
            </div>
            <a href="{{ route('transactions.index') }}" class="small-box-footer">Detail <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-6 col-12">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $total_users }}</h3>
                <p>Total Kuli</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('kuli.index') }}" class="small-box-footer">Detail <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-12">
        <div class="loading-overlay" id="loadingBarCount">
            <div class="loading-spinner"></div>
        </div>
        <canvas id="bar-chart-total-count" width="1000" height="450"></canvas>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-12">
        <div class="loading-overlay" id="loadingBarAmount">
            <div class="loading-spinner"></div>
        </div>
        <canvas id="bar-chart-total-amount" width="1000" height="450"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type="text/javascript">
    var labels = {{ Js::from($labels) }};
    var bulans = {{ Js::from($bulans) }};
    window.onload = function() {
        // showLoading('loadingBarCount');
        var charttrx = {{ Js::from($charttrx) }}
        var colorPalette = ['red', 'blue', 'green', 'yellow', 'purple', 'orange'];
        var Data = processDataset("Transaksi", charttrx, colorPalette);

        var Chart_bar = new Chart(document.getElementById("bar-chart-total-count"), {
            type: 'line',
            data: Data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Transaksi Kuli 30 Hari Terakhir'
                    }
                }
            }
        });

        var charttrxbulan = {{ Js::from($charttrxbulan) }}
        var Chart_bar_amount = new Chart(document.getElementById("bar-chart-total-amount"), {
            type: 'bar',
            data: {
                labels: bulans,
                datasets: [{
                    label: "Transaksi",
                    backgroundColor: "#00a950",
                    data: charttrxbulan,
                    borderRadius: 5,
                }, ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Total Transaksi 12 Bulan Terakhir'
                    }
                }
            }
        });
    };

    function processDataset(dataset, data, colors) {
        var color = -1;
        var result = [];
        $.each(data, function(index, value) {
            var innerArray = [];
            $.each(labels, function(index2, value2) {
                const val = value[value2];
                innerArray.push(val || 0);
            });
            result.push({
                label: dataset + " " + index,
                data: innerArray,
                borderColor: colors[color += 1 % colors.length]
            });
        });
        return {
            labels: labels,
            datasets: result
        };
    }
</script>
