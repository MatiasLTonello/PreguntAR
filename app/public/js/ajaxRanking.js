function filtrar() {
    let filtro = $("#filtro").val();
    if (filtro) {
        $.ajax({
            url: 'http://localhost/ranking/filtrar',
            method: "GET",
            data: {filtro: filtro},
            success: function (response) {
                console.log(response);

                // Limpia el cuerpo de la tabla antes de agregar nuevas filas
                $("#ranking-table tbody").empty();

                response.users.forEach(function (user) {
                    let fila = `
                          <tr>
                            <td class="font-medium">{{posicion}}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="mask mask-squircle h-12 w-12">
                                            <img src="/path/to/images/{{foto}}"
                                                 onerror="this.onerror=null;this.src='../public/img/default-profile.png';" />
                                        </div>
                                    </div>
                                    <div>
                                        <a href="/profile&idUsuario={{id}}" class="link link-primary">
                                        {{nombre_completo}}
                                        </a>
                                    </div>
                                </div>
                            </td>

                            <td class="font-medium">{{puntaje_total}}</td>
                            <td>{{partidas_jugadas}}</td>
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