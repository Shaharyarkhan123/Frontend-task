document.addEventListener('DOMContentLoaded', () => {
    const editButtons = document.querySelectorAll('.edit-btn');
    const form = document.getElementById('recordForm');

    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            const row = button.closest('tr');
            const cells = row.querySelectorAll('td');

            // Populate form fields
            form.name.value = cells[1].innerText.trim();
            form.email.value = cells[2].innerText.trim();
            form.phone.value = cells[3].innerText.trim();
            form.gender.value = cells[4].innerText.trim();

            // Set hidden fields
            form.action.value = 'edit';
            form.id.value = button.getAttribute('data-id');

            // Scroll to form or focus
            form.scrollIntoView({ behavior: 'smooth' });
        });
    });
});
