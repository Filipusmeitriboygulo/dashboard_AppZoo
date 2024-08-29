document.addEventListener("DOMContentLoaded", function () {
    const seminarData = JSON.parse(
        document.getElementById("seminarData").textContent
    );
    const revenueData = JSON.parse(
        document.getElementById("revenueData").textContent
    );
    const revenueLabels = JSON.parse(
        document.getElementById("revenueLabels").textContent
    );
    const categoryData = JSON.parse(
        document.getElementById("categoryData").textContent
    );
    const cumulativeLabels = JSON.parse(
        document.getElementById("cumulativeLabels").textContent
    );
    const cumulativeData = JSON.parse(
        document.getElementById("cumulativeData").textContent
    );
    const seminarRevenueData = JSON.parse(
        document.getElementById("seminarRevenueData").textContent
    );

    // Diagram Batang (Bar Chart)
    new Chart(document.getElementById("seminarChart").getContext("2d"), {
        type: "bar",
        data: {
            labels: Object.keys(seminarData),
            datasets: [
                {
                    label: "Jumlah Seminar",
                    data: Object.values(seminarData),
                    backgroundColor: "rgba(75, 192, 192, 0.2)",
                    borderColor: "rgba(75, 192, 192, 1)",
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
        },
    });

    // Diagram Garis (Line Chart)
    new Chart(document.getElementById("revenueChart").getContext("2d"), {
        type: "line",
        data: {
            labels: revenueLabels,
            datasets: [
                {
                    label: "Pendapatan Bulanan",
                    data: Object.values(revenueData),
                    backgroundColor: "rgba(153, 102, 255, 0.2)",
                    borderColor: "rgba(153, 102, 255, 1)",
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
        },
    });

    // Diagram Lingkaran (Pie Chart)
    new Chart(document.getElementById("pieChart").getContext("2d"), {
        type: "pie",
        data: {
            labels: Object.keys(categoryData),
            datasets: [
                {
                    label: "Kategori Tiket",
                    data: Object.values(categoryData),
                    backgroundColor: [
                        "rgba(255, 99, 132, 0.2)",
                        "rgba(54, 162, 235, 0.2)",
                        "rgba(255, 206, 86, 0.2)",
                        "rgba(75, 192, 192, 0.2)",
                        "rgba(153, 102, 255, 0.2)",
                        "rgba(255, 159, 64, 0.2)",
                    ],
                    borderColor: [
                        "rgba(255, 99, 132, 1)",
                        "rgba(54, 162, 235, 1)",
                        "rgba(255, 206, 86, 1)",
                        "rgba(75, 192, 192, 1)",
                        "rgba(153, 102, 255, 1)",
                        "rgba(255, 159, 64, 1)",
                    ],
                    borderWidth: 1,
                },
            ],
        },
    });

    // Diagram Area (Area Chart)
    new Chart(document.getElementById("areaChart").getContext("2d"), {
        type: "line",
        data: {
            labels: cumulativeLabels,
            datasets: [
                {
                    label: "Pendapatan Bulanan Kumulatif",
                    data: Object.values(cumulativeData),
                    backgroundColor: "rgba(153, 102, 255, 0.2)",
                    borderColor: "rgba(153, 102, 255, 1)",
                    borderWidth: 1,
                    fill: true,
                },
            ],
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
        },
    });

    // Diagram Donat (Donut Chart)
    new Chart(document.getElementById("donutChart").getContext("2d"), {
        type: "doughnut",
        data: {
            labels: Object.keys(seminarRevenueData),
            datasets: [
                {
                    label: "Pendapatan Seminar",
                    data: Object.values(seminarRevenueData),
                    backgroundColor: [
                        "rgba(255, 99, 132, 0.2)",
                        "rgba(54, 162, 235, 0.2)",
                        "rgba(255, 206, 86, 0.2)",
                        "rgba(75, 192, 192, 0.2)",
                        "rgba(153, 102, 255, 0.2)",
                        "rgba(255, 159, 64, 0.2)",
                    ],
                    borderColor: [
                        "rgba(255, 99, 132, 1)",
                        "rgba(54, 162, 235, 1)",
                        "rgba(255, 206, 86, 1)",
                        "rgba(75, 192, 192, 1)",
                        "rgba(153, 102, 255, 1)",
                        "rgba(255, 159, 64, 1)",
                    ],
                    borderWidth: 1,
                },
            ],
        },
    });
});


// 
// =====================================
    // Profit
    // =====================================

    // Ambil data dari controller
    var categories = {!! json_encode($categories) !!};
    var earnings = {!! json_encode($earnings) !!};

    var chartOptions = {
        series: [
            { 
                name: "Earnings this month:", 
                data: earnings
            }
        ],
        chart: {
            type: "bar",
            height: 345,
            offsetX: -15,
            toolbar: { show: true },
            foreColor: "#adb0bb",
            fontFamily: "inherit",
            sparkline: { enabled: false },
        },
        colors: ["#5D87FF"], // Hanya satu warna karena hanya satu dataset
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "35%",
                borderRadius: [6],
                borderRadiusApplication: "end",
                borderRadiusWhenStacked: "all",
            },
        },
        markers: { size: 0 },
        dataLabels: {
            enabled: false,
        },
        legend: {
            show: false,
        },
        grid: {
            borderColor: "rgba(0,0,0,0.1)",
            strokeDashArray: 3,
            xaxis: {
                lines: {
                    show: false,
                },
            },
        },
        xaxis: {
            type: "category",
            categories: categories, // Menggunakan variabel categories dari controller
            labels: {
                style: { cssClass: "grey--text lighten-2--text fill-color" },
            },
        },
        yaxis: {
            show: true,
            min: 0,
            labels: {
                style: {
                    cssClass: "grey--text lighten-2--text fill-color",
                },
            },
        },
        stroke: {
            show: true,
            width: 3,
            lineCap: "butt",
            colors: ["transparent"],
        },
        tooltip: { theme: "light" },
        responsive: [
            {
                breakpoint: 600,
                options: {
                    plotOptions: {
                        bar: {
                            borderRadius: 3,
                        },
                    },
                },
            },
        ],
    };

    var chart = new ApexCharts(document.querySelector("#chart"), chartOptions);
    chart.render();