document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('menu-stats-chart');
    if (!canvas || typeof Chart === 'undefined') return;

    let stats = [];
    try {
        stats = JSON.parse(canvas.dataset.menuStats || '[]');
    } catch (_) {
        return;
    }

    if (!Array.isArray(stats) || stats.length === 0) return;

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: stats.map((item) => item.menu_titre),
            datasets: [{
                label: 'Nombre de commandes',
                data: stats.map((item) => item.nombre_commandes),
                backgroundColor: '#b9782b',
                borderColor: '#8c5418',
                borderWidth: 1,
                borderRadius: 6,
            }],
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
        },
    });
});
