document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('recordForm');
    const inputs = form.querySelectorAll('input, select');
    const errorMessages = form.querySelectorAll('.error-message');

    // Real-time validation
    inputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            if (!input.validity.valid) {
                errorMessages[index].textContent = 'This field is required.';
            } else {
                errorMessages[index].textContent = '';
            }
        });
    });

    // Handle Edit
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            fetch(`get_record.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    form.querySelector('input[name="action"]').value = 'edit';
                    form.querySelector('input[name="id"]').value = data.id;
                    form.querySelector('input[name="name"]').value = data.name;
                    form.querySelector('input[name="email"]').value = data.email;
                    form.querySelector('input[name="phone"]').value = data.phone;
                    form.querySelector('select[name="gender"]').value = data.gender;
                });
        });
    });
});
