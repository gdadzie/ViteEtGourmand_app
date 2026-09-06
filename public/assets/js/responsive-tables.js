document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.table-responsive table:not(.footer-table)').forEach((table) => {
    const labels = Array.from(table.querySelectorAll('thead th')).map((cell) => cell.textContent.trim());
    table.querySelectorAll('tbody tr').forEach((row) => {
      Array.from(row.children).forEach((cell, index) => {
        if (cell.tagName === 'TD' && !cell.hasAttribute('colspan')) cell.dataset.label = labels[index] || 'Information';
      });
    });
  });
});
