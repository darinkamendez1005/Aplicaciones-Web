<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Usuarios (AJAX)</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">

    <div class="container py-4">
        <h2 class="mb-4 text-center">Gestión de Usuarios</h2>

        <!-- FORMULARIO DE USUARIO (INSERT / UPDATE) -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span id="form-titulo">Registrar Nuevo Usuario</span>
                <a href="roll.php" class="btn btn-sm btn-light">⚙️ Administrar Roles</a>
            </div>
            <div class="card-body">
                <form id="formUsuario" class="row g-3">
                    <input type="hidden" id="usu_cve_usuario" name="usu_cve_usuario">

                    <div class="col-md-4">
                        <label for="usu_nombre" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="usu_nombre" name="usu_nombre" required>
                    </div>

                    <div class="col-md-4">
                        <label for="usu_apaterno" class="form-label">Apellido Paterno *</label>
                        <input type="text" class="form-control" id="usu_apaterno" name="usu_apaterno" required>
                    </div>

                    <div class="col-md-4">
                        <label for="usu_amaterno" class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" id="usu_amaterno" name="usu_amaterno">
                    </div>

                    <div class="col-md-4">
                        <label for="usu_correo" class="form-label">Correo Electrónico *</label>
                        <input type="email" class="form-control" id="usu_correo" name="usu_correo" required>
                    </div>

                    <div class="col-md-4">
                        <label for="usu_pass" class="form-label">Contraseña *</label>
                        <input type="password" class="form-control" id="usu_pass" name="usu_pass" required>
                    </div>

                    <div class="col-md-4">
                        <label for="rol_cve_rol" class="form-label">Rol Asignado *</label>
                        <select class="form-select" id="rol_cve_rol" name="rol_cve_rol" required>
                            <option value="">Cargando roles...</option>
                        </select>
                    </div>

                    <!-- RADIO BUTTONS PARA ESTADO ACTIVO / INACTIVO -->
                    <div class="col-md-6">
                        <label class="form-label d-block fw-bold">Estatus del Usuario *</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="usu_activo" id="activo_1" value="1" checked>
                            <label class="form-check-label" for="activo_1">
                                <span class="badge bg-success">Activo</span>
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="usu_activo" id="activo_0" value="0">
                            <label class="form-check-label" for="activo_0">
                                <span class="badge bg-danger">Inactivo</span>
                            </label>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-secondary d-none" id="btnCancelar" onclick="resetearFormulario()">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar Usuario</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TABLA DE USUARIOS (READ) -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">Lista de Usuarios Registrados</div>
            <div class="card-body table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-secondary">
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaUsuarios">
                        <tr>
                            <td colspan="6" class="text-center py-3">Cargando usuarios...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JavaScript con AJAX (Fetch API) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            cargarRoles();
            cargarUsuarios();

            document.getElementById('formUsuario').addEventListener('submit', guardarUsuario);
        });

        // 1. Cargar Roles en el <select>
        async function cargarRoles() {
            try {
                const res = await fetch('api_usuario.php?action=obtener_roles');
                const result = await res.json();
                const selectRol = document.getElementById('rol_cve_rol');
                selectRol.innerHTML = '<option value="">Seleccione un rol...</option>';

                if (result.status === 'success') {
                    result.data.forEach(rol => {
                        selectRol.innerHTML += `<option value="${rol.rol_cve_rol}">${rol.rol_nombre}</option>`;
                    });
                } else {
                    selectRol.innerHTML = '<option value="">Error al cargar roles</option>';
                }
            } catch (error) {
                console.error('Error al cargar roles:', error);
            }
        }

        // 2. Cargar Usuarios en la Tabla
        async function cargarUsuarios() {
            try {
                const res = await fetch('api_usuario.php?action=listar');
                const result = await res.json();
                const tbody = document.getElementById('tablaUsuarios');
                tbody.innerHTML = '';

                if (result.status === 'success' && result.data.length > 0) {
                    result.data.forEach(u => {
                        const amaterno = u.usu_amaterno ? u.usu_amaterno : '';
                        const nombreCompleto = `${u.usu_nombre} ${u.usu_apaterno} ${amaterno}`.trim();
                        const estadoBadge = u.usu_activo == 1 
                            ? '<span class="badge bg-success">Activo</span>' 
                            : '<span class="badge bg-danger">Inactivo</span>';

                        tbody.innerHTML += `
                            <tr>
                                <td>${u.usu_cve_usuario}</td>
                                <td>${nombreCompleto}</td>
                                <td>${u.usu_correo}</td>
                                <td><span class="badge bg-info text-dark">${u.rol_nombre}</span></td>
                                <td>${estadoBadge}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning me-1" onclick="editarUsuario(${u.usu_cve_usuario})">Editar</button>
                                    <button class="btn btn-sm btn-danger" onclick="eliminarUsuario(${u.usu_cve_usuario})">Eliminar</button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No existen usuarios registrados.</td></tr>';
                }
            } catch (error) {
                console.error('Error al cargar usuarios:', error);
            }
        }

        // 3. Guardar / Actualizar Usuario
        async function guardarUsuario(e) {
            e.preventDefault();
            const form = document.getElementById('formUsuario');
            const formData = new FormData(form);

            try {
                const res = await fetch('api_usuario.php?action=guardar', {
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
                    cargarUsuarios();
                } else {
                    Swal.fire('Error', result.message, 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'No se pudo procesar la solicitud', 'error');
            }
        }

        // 4. Editar Usuario
        async function editarUsuario(id) {
            try {
                const res = await fetch(`api_usuario.php?action=obtener&id=${id}`);
                const result = await res.json();

                if (result.status === 'success') {
                    const u = result.data;
                    document.getElementById('usu_cve_usuario').value = u.usu_cve_usuario;
                    document.getElementById('usu_nombre').value = u.usu_nombre;
                    document.getElementById('usu_apaterno').value = u.usu_apaterno;
                    document.getElementById('usu_amaterno').value = u.usu_amaterno || '';
                    document.getElementById('usu_correo').value = u.usu_correo;
                    document.getElementById('usu_pass').value = u.usu_pass;
                    document.getElementById('rol_cve_rol').value = u.rol_cve_rol;

                    // Seleccionar el radio button correspondiente
                    if (u.usu_activo == 1) {
                        document.getElementById('activo_1').checked = true;
                    } else {
                        document.getElementById('activo_0').checked = true;
                    }

                    document.getElementById('form-titulo').innerText = `Editar Usuario #${u.usu_cve_usuario}`;
                    document.getElementById('btnGuardar').innerText = 'Actualizar Usuario';
                    document.getElementById('btnGuardar').className = 'btn btn-warning';
                    document.getElementById('btnCancelar').classList.remove('d-none');

                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            } catch (error) {
                Swal.fire('Error', 'No se pudieron recuperar los datos del usuario', 'error');
            }
        }

        // 5. Eliminar Usuario
        function eliminarUsuario(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmColor: '#d33',
                cancelColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const res = await fetch(`api_usuario.php?action=eliminar&id=${id}`);
                        const resJson = await res.json();

                        if (resJson.status === 'success') {
                            Swal.fire('Eliminado', resJson.message, 'success');
                            cargarUsuarios();
                        } else {
                            Swal.fire('Error', resJson.message, 'error');
                        }
                    } catch (error) {
                        Swal.fire('Error', 'Ocurrió un error al intentar eliminar', 'error');
                    }
                }
            });
        }

        // Resetear Formulario
        function resetearFormulario() {
            document.getElementById('formUsuario').reset();
            document.getElementById('usu_cve_usuario').value = '';
            document.getElementById('activo_1').checked = true; // Por defecto activo
            document.getElementById('form-titulo').innerText = 'Registrar Nuevo Usuario';
            document.getElementById('btnGuardar').innerText = 'Guardar Usuario';
            document.getElementById('btnGuardar').className = 'btn btn-primary';
            document.getElementById('btnCancelar').classList.add('d-none');
        }
    </script>
</body>
</html>