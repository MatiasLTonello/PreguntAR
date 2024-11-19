function filtrar() {
    let filtro = $("#filtro").val();
    if (filtro) {
        $.ajax({
            url: 'http://localhost/ranking/filtrar',
            method: "GET",
            data: {filtro: filtro},
            success: function (response) {

                // Limpia el cuerpo de la tabla antes de agregar nuevas filas
                $("#ranking-table tbody").empty();

                response.users.forEach(function (user) {
                    
                    const {
                        posicion,
                        id,
                        nombre_completo,
                        puntaje_total,
                        partidas_jugadas
                    } = user
                    
                    let fila = `
                        <tr>
                            <td class="font-medium">${posicion}</td>
                            <td>
                                <div class="flex items-center gap-3 ">
                                    <div>
                                        <a href="/profile&idUsuario=${id}" >
                                        ${nombre_completo}
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="font-medium">${puntaje_total}</td>
                            <td>${partidas_jugadas}</td>
                        </tr>`;
                    $("#ranking-table tbody").append(fila);
                });
            },
            error: function () {
                alert("Error");
            }
        });
    }
}