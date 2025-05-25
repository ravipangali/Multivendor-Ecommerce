document.addEventListener('DOMContentLoaded', function() {
    // Get all delete buttons
    const deleteButtons = document.querySelectorAll('.delete-confirm');

    // Add click event listener to each delete button
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const form = this.closest('form');

            if (!form) {
                console.error('No form found for delete button');
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Also add event listeners to forms with delete buttons in case the button event doesn't fire
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        if (form.querySelector('.delete-confirm')) {
            form.addEventListener('submit', function(e) {
                // Only intercept if not already confirmed
                if (!e.target.dataset.confirmed) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Mark as confirmed and submit
                            e.target.dataset.confirmed = 'true';
                            e.target.submit();
                        }
                    });
                }
            });
        }
    });
});
