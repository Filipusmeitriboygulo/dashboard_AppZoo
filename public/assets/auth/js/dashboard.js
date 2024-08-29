document.addEventListener("DOMContentLoaded", function () {
    var earnings = document
        .getElementById("earnings")
        .dataset.json.split(", ")
        .map(Number);
    var categories = document
        .getElementById("categories")
        .dataset.json.split(", ");

    var options = {
        series: [
            {
                name: "Pendapatan Hari ini",
                data: earnings,
            },
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
        colors: ["#5D87FF", "#49BEFF"],
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
        dataLabels: { enabled: false },
        legend: { show: false },
        grid: {
            borderColor: "rgba(0,0,0,0.1)",
            strokeDashArray: 3,
            xaxis: { lines: { show: false } },
        },
        xaxis: {
            type: "category",
            categories: categories,
            labels: {
                style: { cssClass: "grey--text lighten-2--text fill-color" },
            },
        },
        yaxis: {
            show: true,
            min: 0,
            max: 4000000,
            tickAmount: 4,
            labels: {
                formatter: function (value) {
                    // Format value to Rupiah currency
                    return "Rp. " + value.toLocaleString("id-ID");
                },
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

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
});
