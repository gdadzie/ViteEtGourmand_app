
document.querySelectorAll('#filterForm input, #filterForm select')
    .forEach(element => {

        element.addEventListener('change', () => {

            const form = document.getElementById('filterForm');

            const formData = new FormData(form);

            fetch('index.php?page=filtrer_menu', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(html => {

                document.querySelector('.menu-grid').innerHTML = html;

            });

        });

    });

