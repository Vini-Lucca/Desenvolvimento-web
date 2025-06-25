          <!-- Fechamento da div #content -->
            </div>
    <!-- Fechamento da div .wrapper -->
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script para funcionalidade do menu lateral -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toggle do menu lateral
            const sidebarCollapse = document.getElementById('sidebarCollapse');
            const sidebar = document.getElementById('sidebar');

            if (sidebarCollapse) {
                sidebarCollapse.addEventListener('click', function () {
                    sidebar.classList.toggle('active');
                });
            }
            
            // Ativar confirmação em botões de exclusão
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    if (!confirm('Tem certeza de que deseja excluir este item? Esta ação não pode ser desfeita.')) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>
