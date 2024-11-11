function filtrar() {
    let filtro = $("#filtro").val();
    if (filtro) {
        $.ajax({
            url: 'http://localhost/editor/filtrar',
            method: "GET",
            data: {filtro: filtro},
            success: function (response) {

                $("#editor-table tbody").empty();

                response.preguntas.forEach(function (pregunta) {
                    let botonAprobar = '';
                    // Solo muestra el botón "Aprobar" si el estado es "sugerida" o "desaprobada"
                    if (pregunta.estado === 'sugerida' || pregunta.estado === 'desaprobada' || pregunta.estado === 'reportada') {
                        botonAprobar = `<a href="/editor/aprobar&id_pregunta=${pregunta.id}">
                                            <button type="submit" class="btn-gral">Aprobar</button>
                                        </a>`;
                    }

                    let fila = `
                        <tr>
                            <td><a href="#">${pregunta.pregunta}</a></td>
                            <td><p>${pregunta.estado}</p></td>
                            <td>
                                ${botonAprobar}
                                <a href="/editor/eliminar&id_pregunta=${pregunta.id}">
                                    <button type="submit" class="btn-gral">Eliminar</button>
                                </a>
                            </td>
                        </tr>`;
                    $("#editor-table tbody").append(fila);
                });
            },
            error: function (xhr, status, error) {
                console.error("Error en la solicitud:", error);
            }
        });
    }
}