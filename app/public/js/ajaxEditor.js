function filtrar() {
    let filtro = $("#filtro").val();

    console.log({filtro})

    window.location.href = `http://localhost/editor/?filtro=${filtro}`;
}