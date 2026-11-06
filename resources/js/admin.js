import "./admin/checkAllPermissions";
import ApexCharts from "apexcharts";
import flatpickr from "flatpickr";

window.ApexCharts = ApexCharts;
window.flatpickr = flatpickr;

// Auto-initialize chart on pages like dashboard where window.options is defined
function initDashboardChart() {
    const chartEl = document.querySelector("#chart");
    if (chartEl && typeof window.options !== "undefined" && !chartEl.hasChildNodes()) {
        const chart = new ApexCharts(chartEl, window.options);
        chart.render();
    }
}

window.initDashboardChart = initDashboardChart;

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initDashboardChart);
} else {
    initDashboardChart();
}
