
function initialiseFiltresMenus() {
    const form = document.getElementById('filterForm');
    if (!form) return;

    const prixMax = document.getElementById('prixMax');
    const prixMin = document.getElementById('prixMin');
    const prixRangeMax = document.getElementById('prixRangeMax');
    const theme = document.getElementById('theme');
    const regime = document.getElementById('regime');
    const personnes = document.getElementById('personnes');
    const reset = document.getElementById('resetFilters');
    const count = document.getElementById('resultCount');
    const noResult = document.getElementById('noFilterResult');
    const menus = document.querySelectorAll('.menu-item');

    const normalize = value => String(value || '').toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .trim();

    function numericValue(input) {
        const value = Number.parseFloat(input.value);
        return Number.isFinite(value) ? value : null;
    }

    function filterMenus() {
        const maximums = [numericValue(prixMax), numericValue(prixRangeMax)]
            .filter(value => value !== null);
        const maximum = maximums.length ? Math.min(...maximums) : null;
        const minimum = numericValue(prixMin);
        const minimumPersonnes = numericValue(personnes);
        const selectedTheme = normalize(theme.value);
        const selectedRegime = normalize(regime.value);
        let visible = 0;

        menus.forEach(menu => {
            const price = Number.parseFloat(menu.dataset.price);
            const people = Number.parseInt(menu.dataset.personnes, 10);
            const matches =
                (minimum === null || price >= minimum) &&
                (maximum === null || price <= maximum) &&
                (!selectedTheme || normalize(menu.dataset.theme) === selectedTheme) &&
                (!selectedRegime || normalize(menu.dataset.regime) === selectedRegime) &&
                (minimumPersonnes === null || people <= minimumPersonnes);

            menu.hidden = !matches;
            if (matches) visible += 1;
        });

        count.textContent = String(visible);
        noResult.hidden = visible !== 0;
    }

    form.addEventListener('submit', event => event.preventDefault());
    [prixMax, prixMin, prixRangeMax, personnes].forEach(input =>
        input.addEventListener('input', filterMenus)
    );
    [theme, regime].forEach(input => input.addEventListener('change', filterMenus));
    reset.addEventListener('click', () => {
        form.reset();
        filterMenus();
    });

    filterMenus();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initialiseFiltresMenus);
} else {
    initialiseFiltresMenus();
}

