<button class="bg-yellow-500 text-white px-4 py-2 rounded editUserButton"
        data-user="{{ $user->id }}">Edit</button>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.editUserButton').forEach(function(button) {
            button.addEventListener('click', function () {
                var userId = this.getAttribute('data-user');
                fetch(`/admin/users/${userId}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('modalTitle').textContent = 'Edit User';
                        document.getElementById('name').value = data.name;
                        document.getElementById('email').value = data.email;
                        document.getElementById('role').value = data.roles[0].name;
                        if (data.roles[0].name === 'user') {
                            document.getElementById('office-selection').classList.remove('hidden');
                            document.getElementById('office_id').value = data.office_id;
                        } else {
                            document.getElementById('office-selection').classList.add('hidden');
                        }
                        document.getElementById('userId').value = data.id;
                        document.getElementById('userForm').action = `/admin/users/${userId}`;
                        document.getElementById('userModal').classList.remove('hidden');
                    });
            });
        });
    });
</script>
