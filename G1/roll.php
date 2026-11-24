<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Tabla Roll (AJAX)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
    <div class="container py-4">
        <h2 class="mb-4 text-center">Gestión de Catálogo: Roles</h2>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span id="form-titulo">Agregar Nuevo Rol</span>
                <a href="index.php" class="btn btn-sm btn-light">⬅️ Regresar a Usuarios</a>
            </div>
            <div class="card-body">
                <form id="formRol" class="row g-3">
                    <input type="hidden" id="rol_cve_rol" name="rol_cve_rol">

                    <div class="col-md-8">
                        <label for="rol_nombre" class="form-label">Nombre del Rol *</label>
                        <input type="text" class="form-control" id="rol_nombre" name="rol_nombre" placeholder="Ej. Administrador, Vendedor, Supervisor" required>
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" class="btn btn-secondary me-2 d-none" id="btnCancelar" onclick="resetearFormulario()">Cancelar</button>
                        <button type="submit" class="btn btn-success" id="btnGuardar">Guardar Rol</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">Lista de Roles Registrados</div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-secondary">
                        <tr>
                            <th style="width: 15%;">ID</th>
                            <th>Nombre del Rol</th>
                            <th style="width: 25%;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaRoles">
                        <tr>
                            <td colspan="3" class="text-center py-3">Cargando roles...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            cargarRoles();
            document.getElementById('formRol').addEventListener('submit', guardarRol);
        });

        async function cargarRoles() {
            try {
                const res = await fetch('api_roll.php?action=listar');
                const result = await res.json();
                const tbody = document.getElementById('tablaRoles');
                tbody.innerHTML = '';

                if (result.status === 'success' && result.data.length > 0) {
                    result.data.forEach(r => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${r.rol_cve_rol}</td>
                                <td>${r.rol_nombre}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning me-1" onclick="editarRol(${r.rol_cve_rol}, '${r.rol_nombre}')">Editar</button>
                                    <button class="btn btn-sm btn-danger" onclick="eliminarRol(${r.rol_cve_rol})">Eliminar</button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">No existen roles registrados.</td></tr>';
                }
            } catch (error) {
                console.error('Error al cargar roles:', error);
            }
        }

        async function guardarRol(e) {
            e.preventDefault();
            const form = document.getElementById('formRol');
            const formData = new FormData(form);

            try {
                const res = await fetch('api_roll.php?action=guardar', {
                    method: 'POST',
                    body: formData
                });
                const result = await res.json();

                if (result.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: result.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    resetearFormulario();
                    cargarRoles();
                } else {
                    Swal.fire('Error', result.message, 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'No se pudo procesar la solicitud', 'error');
            }
        }

        function editarRol(id, nombre) {
            document.getElementById('rol_cve_rol').value = id;
            document.getElementById('rol_nombre').value = nombre;
            document.getElementById('form-titulo').innerText = `Editar Rol #${id}`;
            document.getElementById('btnGuardar').innerText = 'Actualizar Rol';
            document.getElementById('btnGuardar').className = 'btn btn-warning';
            document.getElementById('btnCancelar').classList.remove('d-none');
        }

        function eliminarRol(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Se eliminará el rol seleccionado',
                icon: 'warning',
                showCancelButton: true,
                confirmColor: '#d33',
                cancelColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const res = await fetch(`api_roll.php?action=eliminar&id=${id}`);
                        const resJson = await res.json();

                        if (resJson.status === 'success') {
                            Swal.fire('Eliminado', resJson.message, 'success');
                            cargarRoles();
                        } else {
                            Swal.fire('Error', resJson.message, 'error');
                        }
                    } catch (error) {
                        Swal.fire('Error', 'Ocurrió un error al intentar eliminar', 'error');
                    }
                }
            });
        }

        function resetearFormulario() {
            document.getElementById('formRol').reset();
            document.getElementById('rol_cve_rol').value = '';
            document.getElementById('form-titulo').innerText = 'Agregar Nuevo Rol';
            document.getElementById('btnGuardar').innerText = 'Guardar Rol';
            document.getElementById('btnGuardar').className = 'btn btn-success';
            document.getElementById('btnCancelar').classList.add('d-none');
        }
    </script>
</body>
</html>