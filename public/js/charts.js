document.addEventListener("DOMContentLoaded", function () {
    fetch('/api/projects')
        .then(response => response.json())
        .then(data => {
            if (!data.length) {
                console.error("La API no devolvió datos.");
                return;
            }

            let labels = data.map(proyecto => proyecto.nombre);
            let values = data.map(proyecto => proyecto.porcentaje_cierre);

            let ctxPie = document.getElementById("myPieChart").getContext('2d');

            // Si el gráfico ya existe, solo actualizar datos
            if (window.myPieChart) {
                window.myPieChart.data.labels = labels;
                window.myPieChart.data.datasets[0].data = values;
                window.myPieChart.update();
            } else {
                window.myPieChart = new Chart(ctxPie, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: values,
                            backgroundColor: ["#4e73df", "#1cc88a", "#36b9cc", "#f6c23e", "#e74a3b"]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 0
                        }
                    }
                });
            }
        })
        .catch(error => console.error("Error al obtener datos:", error));
});