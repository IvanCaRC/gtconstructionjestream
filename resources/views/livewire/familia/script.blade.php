<script>
    function confirmDeletion(idFamilia, familiasName) {
        event.preventDefault(); // Prevenir el comportamiento predeterminado del navegador

        // Llamada para obtener las subfamilias activas
        @this.call('obtenerSubfamiliasActivas', idFamilia).then(() => {
            // Una vez obtenidas las subfamilias, mostrar la alerta inicial
            Swal.fire({
                title: `¿Estás seguro de que deseas eliminar a ${familiasName}?`,
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Validar si tiene subfamilias activas
                    if (@this.subfamilias.length > 0) {
                        Swal.fire({
                            title: 'Advertencia!',
                            text: 'Esta familia tiene subfamilias. Estas también serán eliminadas.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                ejecutarEliminacion(idFamilia, familiasName, true);
                            } else {
                                Swal.fire('Cancelado', 'La eliminación ha sido cancelada.', 'info');
                            }
                        });
                    } else {
                        ejecutarEliminacion(idFamilia, familiasName, false);
                    }
                }
            });
        });
    }

    function ejecutarEliminacion(idFamilia, familiasName, eliminarSubfamilias) {
        @this.call('verificarAsignacion', idFamilia).then((asignada) => {
            if (asignada) {
                Swal.fire(
                    'No se puede eliminar',
                    'Esta familia o alguna de sus subfamilias está asignada a proveedores o productos, no se puede eliminar.',
                    'error'
                );
            } else {
                if (eliminarSubfamilias) {
                    @this.call('eliminarFamiliaConSubfamilias', idFamilia).then(() => {
                        Swal.fire(
                            'Eliminado!',
                            `${familiasName} y sus subfamilias han sido eliminadas.`,
                            'success'
                        );
                    });
                } else {
                    @this.call('eliminar', idFamilia).then(() => {
                        Swal.fire(
                            'Eliminado!',
                            `${familiasName} ha sido eliminado.`,
                            'success'
                        );
                    });
                }
            }
        });
    }
</script>
