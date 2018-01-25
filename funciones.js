function compruebaTam50(valor) {
    //Comprueba que el tamaño sea menor que 50

    if (valor.trim() === "") {
        return "El campo no puede estar vacio.";
    } else if (valor.length > 50) {
        return "El campo no puede contener mas de 50 caracteres.";
    } else {
        return true;
    }
}

function compruebaTam25(valor) {
    //Comprueba que el tamaño sea menor que 25

    if (valor.trim() === "") {
        return "El campo no puede estar vacio.";
    } else if (valor.length > 25) {
        return "El campo no puede contener mas de 25 caracteres.";
    } else {
        return true;
    }
}

function compruebaTam1500(valor) {
    //Comprueba que el tamaño sea menor que 1500

    if (valor.trim() === "") {
        return "El campo no puede estar vacio.";
    } else if (valor.length > 1500) {
        return "El campo no puede contener mas de 1500 caracteres.";
    } else {
        return true;
    }
}

function compruebaTam150(valor) {
    //Comprueba que el tamaño sea menor que 150

    if (valor.trim() === "") {
        return "El campo no puede estar vacio.";
    } else if (valor.length > 150) {
        return "El campo no puede contener mas de 150 caracteres.";
    } else {
        return true;
    }
}

function compruebaVacio(valor) {
    //Comprueba que el campo no este vacio

    if (valor.trim() === "") {
        return "El campo no puede estar vacio.";
    } else {
        return true;
    }
}

function compruebaCodigoPostal(valor) {
    //Comprueba que el códico postal contenga 5 números

    var expr = /^\d{5}$/;

    if (valor.trim() === "") {
        return "El campo no puede estar vacio.";
    } else if (!expr.test(valor)) {
        return "El campo tiene que contener 5 n&uacute;meros.";
    } else {
        return true;
    }
}

function compruebaFechaHora(valor) {
    //Comprueba la fecha y hora en formato YYYY-mm-dd 00:00:00

    var expr = /^[0-9][0-9][0-9][0-9]-[0-1][0-9]-[0-3][0-9] [0-2][0-9]:[0-5][0-9]:[0-5][0-9]$/;

    if (valor.trim() === "") {
        return "El campo no puede estar vacio.";
    } else if (!expr.test(valor)) {
        return "El campo tiene que tener el formato YYYY-mm-dd 00:00:00";
    } else {
        return true;
    }
}

function compruebaFecha(valor) {
    //Comprueba la fecha en formato YYYY-mm-dd

    var expr = /^[0-2][0-9][0-9][0-9]-[0-1][0-9]-[0-3][0-9]$/;

    if (valor.trim() === "") {
        return "El campo no puede estar vacio.";
    } else if (!expr.test(valor)) {
        return "El campo tiene que tener el formato dd-mm-yyyy";
    } else {
        return true;
    }
}

//Comprueba la hora en formato hh:mm
function compruebaHora(valor) {
    var expr = /^[0-2][0-9]:[0-5][0-9]$/;

    if (valor.trim() === "") {
        return "El campo no puede estar vacio.";
    } else if (!expr.test(valor)) {
        return "El campo tiene que tener el formato hh:mm";
    } else {
        return true;
    }
}

function compruebaEmail(valor) {
    //Compruebra el email

    var expr = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    if (valor.trim() === "") {
        return "El campo no puede estar vacio.";
    } else if (!expr.test(valor)) {
        return "El formato del email no es correcto";
    } else {
        return true;
    }
}

function deleteUser(elemento) {
    var usuario = elemento.getAttribute("id");
    $.ajax({
        type: 'POST',
        url: "remote_php/delete_user_admin.php",
        data: {usu: usuario},
        success: function (data) {
            if (data) {
                muestraUsuarios();
            }
        },
        dataType: "json"
    });
}
function muestraUsuarios() {
    var usuario = document.getElementById("admin_search_user").value;
    var email = document.getElementById("admin_search_email").value;
    var nombre = document.getElementById("admin_search_name").value;
    var apellidos = document.getElementById("admin_search_surname").value;
    var ciudad = document.getElementById("admin_search_city").value;
    $("#list_users").empty();
    $.ajax({
        type: 'POST',
        url: "remote_php/get_users_admin.php",
        data: {usu: usuario, mail: email, nom: nombre, apell: apellidos, ciu: ciudad},
        success: function (data) {
            if (!data) {
                $("#list_users").append("<h1>No hay coincidencias!</h1>");
            } else {
                var tam = data.length;
                for (var i = 0; i < tam; i++) {
                    $("#list_users").append('<div id="' + data[i]['usuario'] + '" class="elemento_derecha"><img src="' + data[i]['foto'] + '?' + new Date() + '" class="elemento_img_derecha"><h2>' + data[i]['usuario'] + '</h2><a href="perfil.php?usuario=' + data[i]['usuario'] + '"><div class="icon_cont"><img src="images/editar.png" class="icono icono_lapiz"></a><img src="images/remove.png" id="' + data[i]['usuario'] + '" class="icono" onclick="deleteUser(this)"></div></div>');
                }
            }
        },
        dataType: "json"
    });
}

function deleteGroup(elemento) {
    var grupo = elemento.getAttribute("id");
    $.ajax({
        type: 'POST',
        url: "remote_php/delete_group_admin.php",
        data: {grup: grupo},
        success: function (data) {
            if (data) {
                muestraGrupos();
            }
        },
        dataType: "json"
    });
}

function muestraGrupos() {
    var grupo = document.getElementById("admin_search_group").value;
    var categoria = document.getElementById("admin_search_categoria").value;
    var desde = document.getElementById("fecha_desde").value;
    var hasta = document.getElementById("fecha_hasta").value;
    $("#list_groups").empty();
    $.ajax({
        type: 'POST',
        url: "./remote_php/get_groups_admin.php",
        data: {grup: grupo, cat: categoria, from: desde, to: hasta},
        success: function (data) {
            if (!data) {
                $("#list_groups").append("<h1>No hay coincidencias!</h1>");
            } else {
                var tam = data.length;
                for (var i = 0; i < tam; i++) {
                    $("#list_groups").append('<div id="' + data[i]['nombre'] + '" class="elemento_derecha"><img src="' + data[i]['imagen'] + '?time=' + new Date() + '" class="elemento_img_derecha"><h2>' + data[i]['nombre'] + '</h2><div class="icon_cont"><a href="crearGrupoLogeado.php?nombre_admin=' + data[i]['nombre'] + '"><img src="images/editar.png" class="icono_lapiz icono"></a><img src="images/remove.png" class="icono" id="' + data[i]['nombre'] + '" onclick="deleteGroup(this)"></div></div>');
                }
            }
        },
        dataType: "json"
    });
}

function deleteEvent(elemento) {
    var datos = elemento.getAttribute("id").split("-");
    var evento = datos[0];
    var nombre = datos[1];
    $.ajax({
        type: 'POST',
        url: "remote_php/delete_event_admin.php",
        data: {event: evento, nom: nombre},
        success: function (data) {
            if (data) {
                muestraEventos();
            }
        },
        dataType: "json"
    });
}

function muestraEventos() {
    var evento = document.getElementById("admin_search_event").value;
    var categoria = document.getElementById("admin_search_categoria").value;
    var desde = document.getElementById("fecha_desde").value;
    var hasta = document.getElementById("fecha_hasta").value;
    $("#list_events").empty();
    $.ajax({
        type: 'POST',
        url: "./remote_php/get_events_admin.php",
        data: {event: evento, cat: categoria, from: desde, to: hasta},
        success: function (data) {
            if (!data) {
                $("#list_events").append("<h1>No hay coincidencias!</h1>");
            } else {
                var tam = data.length;
                for (var i = 0; i < tam; i++) {
                    $("#list_events").append('<div id="' + data[i]['id'] + '" class="elemento_derecha"><img src="' + data[i]['imagen'] + '?' + new Date() + '" class="elemento_img_derecha"><h2>' + data[i]['nombre'] + '</h2><div class="icon_cont"><a href="crearEventoLogeado.php?evento=' + data[i]['id'] + '"><img src="images/editar.png" class="icono_lapiz icono"></a><img src="images/remove.png" class="icono" id="' + data[i]['id'] + '-' + data[i]['nombre'] + '" onclick="deleteEvent(this)"></div></div>');
                }
            }
        },
        dataType: "json"
    });
}

function deleteMessage(enlace) {
    var mensaje = enlace.parentElement.getAttribute("id");
    $.ajax({
        type: 'POST',
        url: "remote_php/delete_message_admin.php",
        data: {men: mensaje},
        success: function (data) {
            if (data) {
                muestraMensajes();
            }
        },
        dataType: "json"
    });
}

function muestraMensajes() {
    var remitente = document.getElementById("admin_search_user_rem").value;
    var destinatario = document.getElementById("admin_search_user_dest").value;
    var desde = document.getElementById("fecha_desde").value + " " + document.getElementById("hora_desde").value;
    var hasta = document.getElementById("fecha_hasta").value + " " + document.getElementById("hora_hasta").value;
    $("#list_messages").empty();
    $.ajax({
        type: 'POST',
        url: "./remote_php/get_messages_admin.php",
        data: {rem: remitente, dest: destinatario, from: desde, to: hasta},
        success: function (data) {
            if (!data) {
                $("#list_messages").append("<h1>No hay coincidencias!</h1>");
            } else {
                var tam = data.length;
                for (var i = 0; i < tam; i++) {
                    if (data[i]['censurado'] == 0) {
                        $("#list_messages").append('<div class="elemento_derecha"><h2>Remitente: ' + data[i]['rem'] + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Destinatario:" + data[i]['dest'] + '</h2><p>' + data[i]['texto'] + '</p><p>' + data[i]['fecha_hora'] + '</p><div class="icon_cont"><img id="' + data[i]['rem'] + "_" + data[i]['dest'] + "_" + data[i]['fecha_hora'] + '" src="images/no-forbidden.png" onclick="censurar(this)" class="icono"><a onclick="deleteMessage(this)"><img src="images/remove.png" class="icono"></a></div></div>');
                    } else {
                        $("#list_messages").append('<div class="elemento_derecha"><h2>Remitente: ' + data[i]['rem'] + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Destinatario: " + data[i]['dest'] + '</h2><p>' + data[i]['texto'] + '</p><p>' + data[i]['fecha_hora'] + '</p><div class="icon_cont"><img id="' + data[i]['rem'] + "_" + data[i]['dest'] + "_" + data[i]['fecha_hora'] + '" src="images/forbidden.png" onclick="quitarCensurar(this)" class="icono"><a onclick="deleteMessage(this)"><img src="images/remove.png" class="icono"></a></div></div>');
                    }
                }
            }
        },
        dataType: "json"
    });
}

function censurar(icono) {
    var hijo = icono;
    var mensaje = hijo.getAttribute("id");
    $.ajax({
        type: 'POST',
        url: "remote_php/censure_message_admin.php",
        data: {men: mensaje},
        success: function (data) {
            if (data) {
                hijo.setAttribute("src", "images/forbidden.png");
                hijo.setAttribute("onclick", "quitarCensurar(this)");

            }
        },
        dataType: "json"
    });
}

function quitarCensurar(icono) {
    var hijo = icono;
    var mensaje = hijo.getAttribute("id");
    $.ajax({
        type: 'POST',
        url: "remote_php/uncensored_message_admin.php",
        data: {men: mensaje},
        success: function (data) {
            if (data) {
                hijo.setAttribute("src", "images/no-forbidden.png");
                hijo.setAttribute("onclick", "censurar(this)");
            }
        },
        dataType: "json"
    });
}

function muestraUsuarioBuscador() {
    var texto = document.getElementById("buscador_users").value;
    $.ajax({
        type: 'POST',
        url: "remote_php/getUsuarios.php",
        data: {name: texto},
        success: function (data) {
            var tam = data.length;
            var contenedor = document.getElementById("listado");
            $("#subContenedor").empty();
            var subcontenedor = document.getElementById("subContenedor");
            for (var i = 0; i < tam; i++) {
                var link = document.createElement("a");
                link.setAttribute("href", "conversacion.php?usuario=" + data[i][0] + "");
                subcontenedor.appendChild(link);
                var cont = document.createElement("div");
                cont.setAttribute("id", data[i][0]);
                cont.setAttribute("class", "usuario_listado");
                link.appendChild(cont);
                var foto = document.createElement("img");
                foto.setAttribute("src", data[i][1]);
                foto.setAttribute("class", "foto_user_list");
                cont.appendChild(foto);
                var nombre = document.createElement("p");
                nombre.appendChild(document.createTextNode(data[i][0]));
                cont.appendChild(nombre);
            }
        },
        dataType: "json"
    });
}
function muestraListado() {
    if (!mostrado) {
        var contenedor = document.getElementById("activa");
        var listado = document.createElement("div");
        listado.setAttribute("id", "listado");
        contenedor.appendChild(listado);
        var buscador = document.createElement("input");
        buscador.setAttribute("type", "text");
        buscador.setAttribute("id", "buscador_users");
        buscador.setAttribute("onkeyup", "muestraUsuarioBuscador()");
        listado.appendChild(buscador);
        var subContenedor = document.createElement("div");
        subContenedor.setAttribute("id", "subContenedor");
        listado.appendChild(subContenedor);
        muestraUsuarioBuscador();
        mostrado = true;
    } else {
        document.getElementById("listado").remove();
        mostrado = false;
    }
}
function ocultaListado() {
    document.getElementById("listado").remove();
    mostrado = false;
}

function mostrarMisGrupos() {
    $.ajax({
        type: 'POST',
        url: "remote_php/mostrarMisGruposAjax.php",
        success: function (data) {
            var tam = data.length;
            $("#contenedorGrupos").empty();
            var contenedor = document.getElementById("contenedorGrupos");
            if (tam == 0) {
                $("#contenedorGrupos").append("<h2>No hay ningun grupo</h2>");
            }

            for (var i = 0; i < tam; i++) {
                //Creación div
                var divGrupo = document.createElement("div");
                divGrupo.setAttribute("id", data[i]['nombre']);
                divGrupo.setAttribute("class", "miGrupo");
                contenedor.appendChild(divGrupo);
                //Creacion de enlace
                var enlace = document.createElement("a");
                enlace.setAttribute("href", "grupo.php?grupo=" + data[i]['nombre'] + "");
                divGrupo.appendChild(enlace);
                //Creación imagen del grupo
                var cabecera_oculta = document.createElement("div");
                cabecera_oculta.setAttribute("class", "imagenCabecera");
                cabecera_oculta.setAttribute("style", "background-image:url('" + data[i]['imagen'] + "')");
                enlace.appendChild(cabecera_oculta);

                var imgGrupo = document.createElement("img");
                imgGrupo.setAttribute("src", data[i]['imagen']);
                imgGrupo.setAttribute("alt", data[i]['nombre']);
                imgGrupo.setAttribute("class", "imgCircular");
                enlace.appendChild(imgGrupo);
                //Creación nombre del grupo
                var nombreGrupo = document.createElement("h2");
                nombreGrupo.setAttribute("id", "nombreGrupo");
                enlace.appendChild(nombreGrupo);
                var textoNombre = document.createTextNode(data[i]['nombre']);
                nombreGrupo.appendChild(textoNombre);
                //Creación descripción del grupo
                var descripcionGrupo = document.createElement("p");
                descripcionGrupo.setAttribute("id", "descripcionGrupo");
                enlace.appendChild(descripcionGrupo);
                var textoDesc = document.createTextNode(data[i]['descripcion']);
                descripcionGrupo.appendChild(textoDesc);
                if (data[i]['propietario'] == 1) { //1 = propietario del grupo
                    var papelera = document.createElement("img");
                    papelera.setAttribute("src", "images/papelera.png");
                    papelera.setAttribute("alt", "papelera, borrar grupo");
                    papelera.setAttribute("id", data[i]['nombre']);
                    papelera.setAttribute("class", "icono");
                    papelera.setAttribute("onclick", "borrarGrupo(this)");
                    divGrupo.appendChild(papelera);
                    //Editar grupo
                    var enlace = document.createElement("a");
                    enlace.setAttribute("href", "crearGrupoLogeado.php?nombre=" + data[i]['nombre'] + "");
                    divGrupo.appendChild(enlace);
                    var modificar = document.createElement("img");
                    modificar.setAttribute("src", "images/editar.png");
                    modificar.setAttribute("alt", "lapiz, editar grupo");
                    modificar.setAttribute("id", data[i]['nombre']);
                    modificar.setAttribute("class", "icono icono_lapiz");
                    enlace.appendChild(modificar);
                } else { //0 = inscrito al grupo
                    var dejarGrupo = document.createElement("img");
                    dejarGrupo.setAttribute("src", "images/remove.png");
                    dejarGrupo.setAttribute("alt", "X, dejar grupo");
                    dejarGrupo.setAttribute("id", data[i]['nombre']);
                    dejarGrupo.setAttribute("class", "icono");
                    dejarGrupo.setAttribute("onclick", "dejarGrupo(this)");
                    divGrupo.appendChild(dejarGrupo);
                }
            }
        },
        dataType: "json"
    });
}

function mostrarExplorarGrupos() {
    $.ajax({
        type: 'POST',
        url: "./remote_php/mostrarExplorarGruposAjax.php",
        success: function (data) {
            var tam = data.length;
            $("#contenedorGrupos").empty();
            var contenedor = document.getElementById("contenedorGrupos");
            if (!data) {
                $("#contenedorGrupos").append("<h2>No hay ningun grupo</h2>");
            }

            for (var i = 0; i < tam; i++) {
                //Creación div
                var divGrupo = document.createElement("div");
                divGrupo.setAttribute("id", data[i]['nombre']);
                divGrupo.setAttribute("class", "miGrupo");
                contenedor.appendChild(divGrupo);
                //Creacion de enlace
                var enlace = document.createElement("a");
                enlace.setAttribute("href", "grupo.php?grupo=" + data[i]['nombre'] + "");
                divGrupo.appendChild(enlace);
                //Creación imagen del grupo
                var cabecera_oculta = document.createElement("div");
                cabecera_oculta.setAttribute("class", "imagenCabecera");
                cabecera_oculta.setAttribute("style", "background-image:url('" + data[i]['imagen'] + "')");
                enlace.appendChild(cabecera_oculta);

                var imgGrupo = document.createElement("img");
                imgGrupo.setAttribute("src", data[i]['imagen']);
                imgGrupo.setAttribute("alt", data[i]['nombre']);
                imgGrupo.setAttribute("class", "elemento_img_derecha imgCircular");
                enlace.appendChild(imgGrupo);
                //Creación nombre del grupo
                var nombreGrupo = document.createElement("h2");
                nombreGrupo.setAttribute("id", "nombreGrupo");
                enlace.appendChild(nombreGrupo);
                var textoNombre = document.createTextNode(data[i]['nombre']);
                nombreGrupo.appendChild(textoNombre);
                //Creación descripción del grupo
                var descripcionGrupo = document.createElement("p");
                descripcionGrupo.setAttribute("id", "descripcionGrupo");
                enlace.appendChild(descripcionGrupo);
                var textoDesc = document.createTextNode(data[i]['descripcion']);
                descripcionGrupo.appendChild(textoDesc);
                var anadir = document.createElement("img");
                anadir.setAttribute("src", "images/add.png");
                anadir.setAttribute("alt", "a&ntilde;adir al grupo");
                anadir.setAttribute("id", data[i]['nombre']);
                anadir.setAttribute("class", "icono");
                anadir.setAttribute("onclick", "anadirAlGrupo(this)");
                divGrupo.appendChild(anadir);
            }
        },
        dataType: "json"
    });
}

function busquedaGrupo() {
    var nombre = document.getElementById("busquedaGrupo").value;
    $("#contenedorGrupos").empty();
    $.ajax({
        type: 'POST',
        url: "remote_php/getGruposBusqueda.php",
        data: {nom: nombre},
        success: function (data) {
            var tam = data.length;
            $("#contenedorGrupos").empty();
            var contenedor = document.getElementById("contenedorGrupos");
            if (!data) {
                $("#contenedorGrupos").append("<h2>No hay ningun grupo</h2>");
            }

            for (var i = 0; i < tam; i++) {
                //Creación div
                var divGrupo = document.createElement("div");
                divGrupo.setAttribute("id", data[i]['nombre']);
                divGrupo.setAttribute("class", "miGrupo");
                contenedor.appendChild(divGrupo);
                //Creacion de enlace
                var enlace = document.createElement("a");
                enlace.setAttribute("href", "grupo.php?grupo=" + data[i]['nombre'] + "");
                divGrupo.appendChild(enlace);
                //Creación imagen del grupo
                var cabecera_oculta = document.createElement("div");
                cabecera_oculta.setAttribute("class", "imagenCabecera");
                cabecera_oculta.setAttribute("style", "background-image:url('" + data[i]['imagen'] + "')");
                enlace.appendChild(cabecera_oculta);

                var imgGrupo = document.createElement("img");
                imgGrupo.setAttribute("src", data[i]['imagen']);
                imgGrupo.setAttribute("alt", data[i]['nombre']);
                imgGrupo.setAttribute("class", "elemento_img_derecha imgCircular");
                enlace.appendChild(imgGrupo);

                //Creación nombre del grupo
                var nombreGrupo = document.createElement("h2");
                nombreGrupo.setAttribute("id", "nombreGrupo");
                enlace.appendChild(nombreGrupo);
                var textoNombre = document.createTextNode(data[i]['nombre']);
                nombreGrupo.appendChild(textoNombre);
                //Creación descripción del grupo
                var descripcionGrupo = document.createElement("p");
                descripcionGrupo.setAttribute("id", "descripcionGrupo");
                enlace.appendChild(descripcionGrupo);
                var textoDesc = document.createTextNode(data[i]['descripcion']);
                descripcionGrupo.appendChild(textoDesc);
                if (data[i]['control'] == 1) { //1 = propietario del grupo
                    var papelera = document.createElement("img");
                    papelera.setAttribute("src", "images/papelera.png");
                    papelera.setAttribute("alt", "papelera, borrar grupo");
                    papelera.setAttribute("id", data[i]['nombre']);
                    papelera.setAttribute("class", "icono");
                    papelera.setAttribute("onclick", "borrarGrupo(this)");
                    divGrupo.appendChild(papelera);
                    //Editar grupo
                    var enlace = document.createElement("a");
                    enlace.setAttribute("href", "crearGrupoLogeado.php?nombre=" + data[i]['nombre'] + "");
                    divGrupo.appendChild(enlace);
                    var modificar = document.createElement("img");
                    modificar.setAttribute("src", "images/editar.png");
                    modificar.setAttribute("alt", "lapiz, editar grupo");
                    modificar.setAttribute("id", data[i]['nombre']);
                    modificar.setAttribute("class", "icono icono_lapiz");
                    enlace.appendChild(modificar);
                } else if (data[i]['control'] == 0) { //0 = inscrito al grupo
                    var dejarGrupo = document.createElement("img");
                    dejarGrupo.setAttribute("src", "images/remove.png");
                    dejarGrupo.setAttribute("alt", "X, dejar grupo");
                    dejarGrupo.setAttribute("id", data[i]['nombre']);
                    dejarGrupo.setAttribute("class", "icono");
                    dejarGrupo.setAttribute("onclick", "dejarGrupo(this)");
                    divGrupo.appendChild(dejarGrupo);
                } else { //No estoy en el grupo
                    var anadir = document.createElement("img");
                    anadir.setAttribute("src", "images/add.png");
                    anadir.setAttribute("alt", "a&ntilde;adir al grupo");
                    anadir.setAttribute("id", data[i]['nombre']);
                    anadir.setAttribute("class", "icono");
                    anadir.setAttribute("onclick", "anadirAlGrupo(this)");
                    divGrupo.appendChild(anadir);
                }
            }
        },
        dataType: "json"
    });
}

function deleteCategory(imagen) {
    var categoria = imagen.getAttribute("id");
    $.ajax({
        type: 'POST',
        url: "remote_php/delete_category_admin.php",
        data: {cat: categoria},
        success: function (data) {
            if (data) {
                alert("AAAAAAA");
                muestraCategorias();
            }
        },
        dataType: "json"
    });

}

function muestraCategorias() {
    var categoria = document.getElementById("admin_search_category").value;
    $("#list_categories").empty();
    $.ajax({
        type: 'POST',
        url: "./remote_php/get_categories_admin.php",
        data: {cat: categoria},
        success: function (data) {
            if (!data) {
                $("#list_categories").append("<h1>No hay coincidencias!</h1>");
            } else if (data) {
                var contenedorGlobal = document.getElementById("list_categories");
                var tam = data.length;
                for (var i = 0; i < tam; i++) {

                    var contenedor = document.createElement("div");
                    contenedor.setAttribute("class", "elemento_derecha");
                    contenedor.setAttribute("id", data[i]['nombre']);
                    contenedorGlobal.appendChild(contenedor);

                    var imagen = document.createElement("img");
                    imagen.setAttribute("src", data[i]['imagen'] + '?' + new Date());
                    imagen.setAttribute("class", "elemento_img_derecha");
                    contenedor.appendChild(imagen);

                    var titulo = document.createElement("h2");
                    titulo.appendChild(document.createTextNode("" + data[i]['nombre'] + ""));
                    contenedor.appendChild(titulo);

                    var div_icon = document.createElement("div");
                    div_icon.setAttribute("class", "icon_cont");
                    contenedor.appendChild(div_icon);

                    var remove = document.createElement("img");
                    remove.setAttribute("id", data[i]['nombre']);
                    remove.setAttribute("src", "images/remove.png");
                    remove.setAttribute("class", "icono");
                    remove.setAttribute("onclick", "deleteCategory(this)");
                    div_icon.appendChild(remove);

                    var enlace = document.createElement("a");
                    enlace.setAttribute("href", "crear-categoria.php?nombre=" + data[i]['nombre'] + "");
                    div_icon.appendChild(enlace);

                    var editar = document.createElement("img");
                    editar.setAttribute("src", "images/editar.png");
                    editar.setAttribute("class", "icono_lapiz icono icono");
                    enlace.appendChild(editar);
                }
            }
        },
        dataType: "json"
    });
}

function compruebaErroresCategoria() {
    var nombre = document.getElementById("nombreCategoria");
    var descripcion = document.getElementById("textoDescripcion");
    var error = false;
    if (!compruebaTexto(nombre.value)) {
        nombre.setAttribute("placeholder", "Revise este campo");
        nombre.setAttribute("border", "1px solid red");
        error = true;
    }

    if (existeCategoria(nombre.value)) {
        nombre.setAttribute("placeholder", "Ya existe");
        nombre.setAttribute("border", "1px solid red");
        error = true;
    }

    if (!compruebaTexto(descripcion.value)) {
        descripcion.setAttribute("placeholder", "Revise este campo");
        descripcion.setAttribute("border", "1px solid red");
        error = true;
    }

    return error;
}

function existeCategoria(categoria) {
    $.ajax({
        type: 'POST',
        url: "./remote_php/get_category_admin.php",
        data: {cat: categoria},
        success: function (data) {
            if (data) {
                return true;
            } else {
                return false;
            }
        },
        dataType: "json"
    });
}

function muestraPublicaciones() {
    var usuario = document.getElementById("admin_search_user").value;
    var grupo = document.getElementById("admin_search_group").value;
    var desde = document.getElementById("fecha_desde").value;
    var hasta = document.getElementById("fecha_hasta").value;
    $("#list_publicaciones").empty();
    $.ajax({
        type: 'POST',
        url: "./remote_php/get_publicaciones_admin.php",
        data: {usu: usuario, grup: grupo, from: desde, to: hasta},
        success: function (data) {
            if (!data) {
                $("#list_publicaciones").append("<h1>No hay coincidencias!</h1>");
            } else {
                var contenedorGlobal = document.getElementById("list_publicaciones");
                var tam = data.length;
                for (var i = 0; i < tam; i++) {

                    var contenedor = document.createElement("div");
                    contenedor.setAttribute("class", "elemento_derecha");
                    contenedor.setAttribute("id", data[i]['id']);
                    contenedorGlobal.appendChild(contenedor);

                    var grupo = document.createElement("h2");
                    grupo.appendChild(document.createTextNode(data[i]['grupo']));
                    contenedor.appendChild(grupo);

                    var titulo = document.createElement("p");
                    titulo.setAttribute("id", "titulo");
                    titulo.appendChild(document.createTextNode('' + data[i]['titulo'] + ''));
                    contenedor.appendChild(titulo);

                    var contenido = document.createElement("p");
                    contenido.setAttribute("id", "contenido");
                    contenido.appendChild(document.createTextNode('' + data[i]['contenido'] + ''));
                    contenedor.appendChild(contenido);

                    var fecha = document.createElement("p");
                    fecha.appendChild(document.createTextNode("" + data[i]['fecha_hora'] + ""));
                    contenedor.appendChild(fecha);

                    var autor = document.createElement("p");
                    autor.appendChild(document.createTextNode("" + data[i]['autor'] + ""));
                    contenedor.appendChild(autor);

                    var div_icon = document.createElement("div");
                    div_icon.setAttribute("class", "icon_cont");
                    contenedor.appendChild(div_icon);

                    if (data[i]['censurada'] == 0) {
                        var censura = document.createElement("img");
                        censura.setAttribute("id", "" + data[i]['id'] + "");
                        censura.setAttribute("src", "./images/no-forbidden.png");
                        censura.setAttribute("class", "icono");
                        censura.setAttribute("onclick", "censurarPublicacion(this)");
                        div_icon.appendChild(censura);
                    } else {
                        var censura = document.createElement("img");
                        censura.setAttribute("id", "" + data[i]['id'] + "");
                        censura.setAttribute("src", "./images/forbidden.png");
                        censura.setAttribute("class", "icono");
                        censura.setAttribute("onclick", "quitarCensuraPublicacion(this)");
                        div_icon.appendChild(censura);
                    }
                    var remove = document.createElement("img");
                    remove.setAttribute("id", "" + data[i]['id'] + "");
                    remove.setAttribute("src", "./images/remove.png");
                    remove.setAttribute("class", "icono");
                    remove.setAttribute("onclick", "eliminaPublicacion(this)");
                    div_icon.appendChild(remove);
                }
            }
        },
        dataType: "json"
    });
}

function censurarPublicacion(elemento) {
    var id = elemento.getAttribute("id");
    $.ajax({
        type: 'POST',
        url: "remote_php/censure_post_admin.php",
        data: {id: id},
        success: function (data) {
            if (data) {
                elemento.setAttribute("src", "images/forbidden.png");
                elemento.setAttribute("onclick", "quitarCensuraPublicacion(this)");
            }
        },
        dataType: "json"
    });
}

function quitarCensuraPublicacion(elemento) {
    var id = elemento.getAttribute("id");
    $.ajax({
        type: 'POST',
        url: "remote_php/uncensored_post_admin.php",
        data: {id: id},
        success: function (data) {
            if (data) {
                elemento.setAttribute("src", "images/no-forbidden.png");
                elemento.setAttribute("onclick", "censurarPublicacion(this)");
            }
        },
        dataType: "json"
    });
}

function eliminaPublicacion(elemento) {
    var id = elemento.getAttribute("id");
    $.ajax({
        type: 'POST',
        url: "remote_php/delete_post_admin.php",
        data: {id: id},
        success: function (data) {
            if (data) {
                $("div#" + id + "").remove();
            }
        },
        dataType: "json"
    });
}

function mostrarMisEventos() {
    $.ajax({
        type: 'POST',
        url: "remote_php/mostrarMisEventosAjax.php",
        success: function (data) {
            var tam = data.length;
            $("#contenedorEventos").empty();
            var contenedor = document.getElementById("contenedorEventos");
            if (!data) {
                $("#contenedorEventos").append("<h2>No hay ningun evento</h2>");
            }

            for (var i = 0; i < tam; i++) {

                //Creación div
                var divEvento = document.createElement("div");
                divEvento.setAttribute("id", data[i]['nombre']);
                divEvento.setAttribute("class", "elemento_derecha");
                contenedor.appendChild(divEvento);
                //Creacion del enlace
                var enlace = document.createElement("a");
                enlace.setAttribute("href", "evento.php?id=" + data[i]['id'] + "");
                divEvento.appendChild(enlace);
                //Creación imagen
                var cabecera_oculta = document.createElement("div");
                cabecera_oculta.setAttribute("class", "imagenCabecera");
                cabecera_oculta.setAttribute("style", "background-image:url('" + data[i]['imagen'] + "')");
                enlace.appendChild(cabecera_oculta);

                var imgEvento = document.createElement("img");
                imgEvento.setAttribute("src", data[i]['imagen']);
                imgEvento.setAttribute("alt", data[i]['nombre']);
                imgEvento.setAttribute("class", "imgCircular");
                enlace.appendChild(imgEvento);
                //Creación nombre
                var nombreEvento = document.createElement("h2");
                nombreEvento.setAttribute("id", "nombreEvento");
                enlace.appendChild(nombreEvento);
                var textoNombre = document.createTextNode(data[i]['nombre']);
                nombreEvento.appendChild(textoNombre);
                //Creación descripción
                var descripcionEvento = document.createElement("p");
                descripcionEvento.setAttribute("id", "descripcionEvento");
                enlace.appendChild(descripcionEvento);
                var textoDesc = document.createTextNode(data[i]['descripcion']);
                descripcionEvento.appendChild(textoDesc);
                //Creación fecha y hora
                var fechaHora = document.createElement("p");
                fechaHora.setAttribute("id", "fechaHoraEvento");
                enlace.appendChild(fechaHora);
                var textoFechaHora = document.createTextNode(data[i]['fecha_hora']);
                fechaHora.appendChild(textoFechaHora);
                if (data[i]['propietario'] == 1) { //1 = propietario del evento
                    var papelera = document.createElement("img");
                    papelera.setAttribute("src", "images/papelera.png");
                    papelera.setAttribute("alt", "papelera, borrar evento");
                    papelera.setAttribute("id", data[i]['nombre']);
                    papelera.setAttribute("class", "icono");
                    papelera.setAttribute("onclick", "borrarEvento(this)");
                    divEvento.appendChild(papelera);
                    //Editar evento
                    var enlace = document.createElement("a");
                    enlace.setAttribute("href", "crearEventoLogeado.php?id=" + data[i]['id'] + "");
                    divEvento.appendChild(enlace);
                    var modificar = document.createElement("img");
                    modificar.setAttribute("src", "images/editar.png");
                    modificar.setAttribute("alt", "lapiz, editar evento");
                    modificar.setAttribute("id", data[i]['nombre']);
                    modificar.setAttribute("class", "icono icono_lapiz");
                    enlace.appendChild(modificar);
                } else { //0 = inscrito al evento
                    var dejarEvento = document.createElement("img");
                    dejarEvento.setAttribute("src", "images/remove.png");
                    dejarEvento.setAttribute("alt", "X, dejar evento");
                    dejarEvento.setAttribute("id", data[i]['nombre']);
                    dejarEvento.setAttribute("class", "icono");
                    dejarEvento.setAttribute("onclick", "dejarEvento(this)");
                    divEvento.appendChild(dejarEvento);
                }
            }
        },
        dataType: "json"
    });
}

function mostrarExplorarEventos() {
    $.ajax({
        type: 'POST',
        url: "./remote_php/mostrarExplorarEventosAjax.php",
        success: function (data) {
            var tam = data.length;
            $("#contenedorEventos").empty();
            var contenedor = document.getElementById("contenedorEventos");
            if (!data) {
                $("#contenedorEventos").append("<h2>No hay ningun evento</h2>");
            }

            for (var i = 0; i < tam; i++) {
                //Creación div
                var divEvento = document.createElement("div");
                divEvento.setAttribute("id", data[i]['nombre']);
                divEvento.setAttribute("class", "miGrupo");
                contenedor.appendChild(divEvento);
                //Creación imagen
                var cabecera_oculta = document.createElement("div");
                cabecera_oculta.setAttribute("class", "imagenCabecera");
                cabecera_oculta.setAttribute("style", "background-image:url('" + data[i]['imagen'] + "')");
                divEvento.appendChild(cabecera_oculta);
                var imgEvento = document.createElement("img");
                imgEvento.setAttribute("src", data[i]['imagen']);
                imgEvento.setAttribute("alt", data[i]['nombre']);
                imgEvento.setAttribute("class", "imgCircular");
                divEvento.appendChild(imgEvento);
                //Creación nombre
                var nombreEvento = document.createElement("p");
                nombreEvento.setAttribute("id", "nombreEvento");
                divEvento.appendChild(nombreEvento);
                var textoNombre = document.createTextNode(data[i]['nombre']);
                nombreEvento.appendChild(textoNombre);
                //Creación descripción
                var descripcionEvento = document.createElement("p");
                descripcionEvento.setAttribute("id", "descripcionEvento");
                divEvento.appendChild(descripcionEvento);
                var textoDesc = document.createTextNode(data[i]['descripcion']);
                descripcionEvento.appendChild(textoDesc);
                //Creación fecha y hora
                var fechaHora = document.createElement("p");
                fechaHora.setAttribute("id", "fechaHoraEvento");
                divEvento.appendChild(fechaHora);
                var textoFechaHora = document.createTextNode(data[i]['fecha_hora']);
                fechaHora.appendChild(textoFechaHora);
                var anadir = document.createElement("img");
                anadir.setAttribute("src", "images/add.png");
                anadir.setAttribute("alt", "a&ntilde;adir al grupo");
                anadir.setAttribute("id", data[i]['nombre']);
                anadir.setAttribute("class", "icono");
                anadir.setAttribute("onclick", "anadirAlEvento(this)");
                divEvento.appendChild(anadir);
            }
        },
        dataType: "json"
    });
}

function busquedaEvento() {
    var nombre = document.getElementById("busquedaEvento").value;
    $("#contenedorEventos").empty();
    $.ajax({
        type: 'POST',
        url: "./remote_php/getEventosBusqueda.php",
        data: {nom: nombre},
        success: function (data) {
            var tam = data.length;
            $("#contenedorEventos").empty();
            var contenedor = document.getElementById("contenedorEventos");
            if (!data) {
                $("#contenedorEventos").append("<h2>No hay ningun evento</h2>");
            }

            for (var i = 0; i < tam; i++) {

                //Creación div
                var divEvento = document.createElement("div");
                divEvento.setAttribute("id", data[i]['nombre']);
                divEvento.setAttribute("class", "evento");
                contenedor.appendChild(divEvento);
                if (data[i]['control'] == 0 || data[i]['control'] == 1) {
                    //Creacion del enlace
                    var enlace = document.createElement("a");
                    enlace.setAttribute("href", "evento.php?id=" + data[i]['id'] + "");
                    divEvento.appendChild(enlace);
                    //Creación imagen
                    var imgEvento = document.createElement("img");
                    imgEvento.setAttribute("src", data[i]['imagen']);
                    imgEvento.setAttribute("alt", data[i]['nombre']);
                    imgEvento.setAttribute("class", "imagenEvento");
                    enlace.appendChild(imgEvento);
                    //Creación nombre
                    var nombreEvento = document.createElement("p");
                    nombreEvento.setAttribute("id", "nombreEvento");
                    enlace.appendChild(nombreEvento);
                    var textoNombre = document.createTextNode(data[i]['nombre']);
                    nombreEvento.appendChild(textoNombre);
                    //Creación descripción
                    var descripcionEvento = document.createElement("p");
                    descripcionEvento.setAttribute("id", "descripcionEvento");
                    enlace.appendChild(descripcionEvento);
                    var textoDesc = document.createTextNode(data[i]['descripcion']);
                    descripcionEvento.appendChild(textoDesc);
                    //Creación fecha y hora
                    var fechaHora = document.createElement("p");
                    fechaHora.setAttribute("id", "fechaHoraEvento");
                    enlace.appendChild(fechaHora);
                    var textoFechaHora = document.createTextNode(data[i]['fecha_hora']);
                    fechaHora.appendChild(textoFechaHora);
                    if (data[i]['control'] == 1) { //1 = propietario del evento
                        var papelera = document.createElement("img");
                        papelera.setAttribute("src", "images/papelera.png");
                        papelera.setAttribute("alt", "papelera, borrar evento");
                        papelera.setAttribute("id", data[i]['nombre']);
                        papelera.setAttribute("class", "icono");
                        papelera.setAttribute("onclick", "borrarEvento(this)");
                        divEvento.appendChild(papelera);
                        //Editar evento
                        var enlace = document.createElement("a");
                        enlace.setAttribute("href", "crearEventoLogeado.php?nombre=" + data[i]['nombre'] + "");
                        divEvento.appendChild(enlace);
                        var modificar = document.createElement("img");
                        modificar.setAttribute("src", "images/editar.png");
                        modificar.setAttribute("alt", "lapiz, editar evento");
                        modificar.setAttribute("id", data[i]['nombre']);
                        modificar.setAttribute("class", "icono icono_lapiz");
                        enlace.appendChild(modificar);
                    } else { //0 = inscrito al evento
                        var dejarEvento = document.createElement("img");
                        dejarEvento.setAttribute("src", "images/remove.png");
                        dejarEvento.setAttribute("alt", "X, dejar evento");
                        dejarEvento.setAttribute("id", data[i]['nombre']);
                        dejarEvento.setAttribute("class", "icono");
                        dejarEvento.setAttribute("onclick", "dejarEvento(this)");
                        divEvento.appendChild(dejarEvento);
                    }

                } else {
                    //Creación imagen
                    var imgEvento = document.createElement("img");
                    imgEvento.setAttribute("src", data[i]['imagen']);
                    imgEvento.setAttribute("alt", data[i]['nombre']);
                    imgEvento.setAttribute("class", "imagenEvento");
                    divEvento.appendChild(imgEvento);
                    //Creación nombre
                    var nombreEvento = document.createElement("p");
                    nombreEvento.setAttribute("id", "nombreEvento");
                    divEvento.appendChild(nombreEvento);
                    var textoNombre = document.createTextNode(data[i]['nombre']);
                    nombreEvento.appendChild(textoNombre);
                    //Creación descripción
                    var descripcionEvento = document.createElement("p");
                    descripcionEvento.setAttribute("id", "descripcionEvento");
                    divEvento.appendChild(descripcionEvento);
                    var textoDesc = document.createTextNode(data[i]['descripcion']);
                    descripcionEvento.appendChild(textoDesc);
                    //Creación fecha y hora
                    var fechaHora = document.createElement("p");
                    fechaHora.setAttribute("id", "fechaHoraEvento");
                    divEvento.appendChild(fechaHora);
                    var textoFechaHora = document.createTextNode(data[i]['fecha_hora']);
                    fechaHora.appendChild(textoFechaHora);
                    var anadir = document.createElement("img");
                    anadir.setAttribute("src", "images/add.png");
                    anadir.setAttribute("alt", "a&ntilde;adir al evento");
                    anadir.setAttribute("id", data[i]['nombre']);
                    anadir.setAttribute("class", "icono");
                    anadir.setAttribute("onclick", "anadirAlEvento(this)");
                    divEvento.appendChild(anadir);
                }
            }
        },
        dataType: "json"
    });
}

function compruebaErroresGrupo() {
    var correcto = true;
    //Error nombre del grupo
    var mensajeErrorNombreGrupo = compruebaTam50($("#nombreGrupo").val());
    //Error descripcion del grupo
    var mensajeErrorDescripcionGrupo = compruebaTam1500($("#textoDescripcionGru").val());
    //Error selecCategoria 
    var mensajeErrorSelecCategori = compruebaVacio($("#selectCategoria").val());

    //Borrar errores previos
    $("#errorNombreGrupo").remove();
    $("#errorDescripcionGrupo").remove();
    $("#errorSelectCategoria").remove();

    //Tratamiento de errores del nombre del grupo
    if (mensajeErrorNombreGrupo !== true) {
        correcto = false;
        var errorNombreGrupo = $("<em></em>");
        errorNombreGrupo.attr("id", "errorNombreGrupo");
        errorNombreGrupo.append(mensajeErrorNombreGrupo);
        $("#nombreGrupo").after(errorNombreGrupo);
        errorNombreGrupo.fadeIn(500).delay(250).fadeOut(500).fadeIn(500);
        $("#errorNombreGrupo").css("color", "#F1948A");
    }

    //Tratamiento de errores de la descripcion del grupo
    if (mensajeErrorDescripcionGrupo !== true) {
        correcto = false;
        var errorDescripcionGrupo = $("<em></em>");
        errorDescripcionGrupo.attr("id", "errorDescripcionGrupo");
        errorDescripcionGrupo.append(mensajeErrorDescripcionGrupo);
        $("#textoDescripcionGru").after(errorDescripcionGrupo);
        errorDescripcionGrupo.fadeIn(500).delay(250).fadeOut(500).fadeIn(500);
        $("#errorDescripcionGrupo").css("color", "#F1948A");
    }

    //Tratamiento de errores del selec categoria
    if (mensajeErrorSelecCategori !== true) {
        correcto = false;
        var errorSelectCategoria = $("<em></em>");
        errorSelectCategoria.attr("id", "errorSelectCategori");
        errorSelectCategoria.append(mensajeErrorSelecCategori);
        $("#selectCategoria").after(errorSelectCategoria);
        errorSelectCategoria.fadeIn(500).delay(250).fadeOut(500).fadeIn(500);
        $("#errorSelectCategoria").css("color", "#F1948A");
    }

    return correcto;
}

function compruebaErroresEvento() {
    var correcto = true;

    //Error nombre del evento
    var mensajeErrorNombreEvento = compruebaTam50($("#nombreEvento").val());

    //Error descripcion del evento
    var mensajeErrorDescripcionEvento = compruebaTam1500($("#textoDescripcion").val());

    //Error selecCategoria 
    var mensajeErrorSelecCategori = compruebaVacio($("#selectCategoria").val());

    //Error fecha
    var mensajeErrorFecha = compruebaFecha($("#fecha").val());

    //Error hora
    var mensajeErrorHora = compruebaHora($("#hora").val());

    //Error selectCiudad
    var mensajeErrorSelectCiudad = compruebaVacio($("#selectCiudad").val());

    //Error cp
    var mensajeErrorCp = compruebaCodigoPostal($("#cp").val());

    //Error direccion
    var mensajeErrorDireccion = compruebaTam150($("#direccion").val());

    //Borrar errores previos
    $("#errorNombre").remove();
    $("#errorDescripcion").remove();
    $("#errorSelectCategoria").remove();
    $("#errorSelectCiudad").remove();
    $("#errorFecha").remove();
    $("#errorHora").remove();
    $("#errorCp").remove();
    $("#errorDireccion").remove();

    //Tratamiento de errores del nombre
    if (mensajeErrorNombreEvento !== true) {
        correcto = false;
        var errorNombre = $("<em></em>");
        errorNombre.attr("id", "errorNombre");
        errorNombre.append(mensajeErrorNombreEvento);
        $("#nombreEvento").after(errorNombre);
        errorNombre.fadeIn(500).delay(250).fadeOut(500).fadeIn(500);
        $("#errorNombre").css("color", "#F1948A");
    }

    //Tratamiento de errores de la descripcion
    if (mensajeErrorDescripcionEvento !== true) {
        correcto = false;
        var errorDescripcion = $("<em></em>");
        errorDescripcion.attr("id", "errorDescripcion");
        errorDescripcion.append(mensajeErrorDescripcionEvento);
        $("#textoDescripcion").after(errorDescripcion);
        errorDescripcion.fadeIn(500).delay(250).fadeOut(500).fadeIn(500);
        $("#errorDescripcion").css("color", "#F1948A");
    }

    //Tratamiento de errores del selec categoria
    if (mensajeErrorSelecCategori !== true) {
        correcto = false;
        var errorSelectCategoria = $("<em></em>");
        errorSelectCategoria.attr("id", "errorSelectCategori");
        errorSelectCategoria.append(mensajeErrorSelecCategori);
        $("#selectCategoria").after(errorSelectCategoria);
        errorSelectCategoria.fadeIn(500).delay(250).fadeOut(500).fadeIn(500);
        $("#errorSelectCategoria").css("color", "#F1948A");
    }

    //Tratamiento de errores del selec ciudad
    if (mensajeErrorSelectCiudad !== true) {
        correcto = false;
        var errorSelectCiudad = $("<em></em>");
        errorSelectCiudad.attr("id", "errorSelectCiudad");
        errorSelectCiudad.append(mensajeErrorSelectCiudad);
        $("#selectCiudad").after(errorSelectCiudad);
        errorSelectCiudad.fadeIn(500).delay(250).fadeOut(500).fadeIn(500);
        $("#errorSelectCiudad").css("color", "#F1948A");
    }

    //Tratamiento de errores de fecha
    if (mensajeErrorFecha !== true) {
        correcto = false;
        var errorFechaHora = $("<em></em>");
        errorFechaHora.attr("id", "errorFecha");
        errorFechaHora.append(mensajeErrorFecha);
        $("#hora").after(errorFechaHora);
        errorFechaHora.fadeIn(500).delay(250).fadeOut(500).fadeIn(500);
        $("#errorFecha").css("color", "#F1948A");
    }

    //Tratamiento de errores de hora
    if (mensajeErrorHora !== true) {
        $("#errorFecha").remove();
        correcto = false;
        var errorFechaHora = $("<em></em>");
        errorFechaHora.attr("id", "errorHora");
        errorFechaHora.append(mensajeErrorHora);
        $("#hora").after(errorFechaHora);
        errorFechaHora.fadeIn(500).delay(250).fadeOut(500).fadeIn(500);
        $("#errorHora").css("color", "#F1948A");
    }

    //Tratamiento de errores de codigo postal
    if (mensajeErrorCp !== true) {
        correcto = false;
        var errorCp = $("<em></em>");
        errorCp.attr("id", "errorCp");
        errorCp.append(mensajeErrorCp);
        $("#cp").after(errorCp);
        errorCp.fadeIn(500).delay(250).fadeOut(500).fadeIn(500);
        $("#errorCp").css("color", "#F1948A");
    }

    //Tratamiento de errores de direccion
    if (mensajeErrorDireccion !== true) {
        correcto = false;
        var errorDireccion = $("<em></em>");
        errorDireccion.attr("id", "errorDireccion");
        errorDireccion.append(mensajeErrorDireccion);
        $("#direccion").after(errorDireccion);
        errorDireccion.fadeIn(500).delay(250).fadeOut(500).fadeIn(500);
        $("#errorDireccion").css("color", "#F1948A");
    }

    return correcto;
}

function compruebaErroresCategoria() {
    var correcto = true;

    //Error nombre de la categoía
    var mensajeErrorNombre = compruebaTam50($("#nombreCategoria").val());

    //Error descripcion
    var mensajeErrorDescripcion = compruebaTam1500($("#textoDescripcion").val());

    //Borrar errores previos
    $("#errorNombre").remove();
    $("#errorDescripcion").remove();

    //Tratamiento de errores del nombre
    if (mensajeErrorNombre !== true) {
        correcto = false;
        var errorNombre = $("<em></em>");
        errorNombre.attr("id", "errorNombre");
        errorNombre.append(mensajeErrorNombre);
        $("#nombreCategoria").after(errorNombre);
        errorNombre.fadeIn(500).delay(250).fadeOut(500).fadeIn(500);
        $("#errorNombre").css("color", "#F1948A");
    }

    //Tratamiento de errores de la descripcion
    if (mensajeErrorDescripcion !== true) {
        correcto = false;
        var errorDescripcion = $("<em></em>");
        errorDescripcion.attr("id", "errorDescripcion");
        errorDescripcion.append(mensajeErrorDescripcion);
        $("#textoDescripcion").after(errorDescripcion);
        errorDescripcion.fadeIn(500).delay(250).fadeOut(500).fadeIn(500);
        $("#errorDescripcion").css("color", "#F1948A");
    }

    return correcto;
}

function comprobarErroresRegistro() {

    var correcto = true;

    //Error nombre del usuario
    var mensajeErrorNombreUsuario = compruebaTam50($("#nombre").val());

    //Error apellidos del usuario
    var mensajeErrorApellidosUsuario = compruebaTam150($("#apellidos").val());

    //Error username del usuario
    var mensajeErrorUsernameUsuario = compruebaTam25($("#usuario").val());

    //Error password del usuario
    var mensajeErrorPasswordUsuario = compruebaVacio($("#password").val());

    //Error email
    var mensajeErrorEmail = compruebaEmail($("#email").val());

    //Error fechaNacimiento
    var mensajeErrorFechaNacim = compruebaFecha($("#fecha").val());

    //Error selectCiudad
    var mensajeErrorSelectCiudad = compruebaVacio($("#ciudad").val());

    //Tratamiento de errores del nombre
    if (mensajeErrorNombreUsuario !== true) {
        correcto = false;
        $("#nombre").css("background-color", "#F1948A");
    }

    if (mensajeErrorApellidosUsuario !== true) {
        correcto = false;
        $("#apellidos").css("background-color", "#F1948A");
    }

    if (mensajeErrorUsernameUsuario !== true) {
        correcto = false;
        $("#usuario").css("background-color", "#F1948A");
    }

    if (mensajeErrorPasswordUsuario !== true) {
        correcto = false;
        $("#password").css("background-color", "#F1948A");
    }

    if (mensajeErrorEmail !== true) {
        correcto = false;
        $("#email").css("background-color", "#F1948A");
    }

    if (mensajeErrorFechaNacim !== true) {
        correcto = false;
        $("#fecha").css("background-color", "#F1948A");
    }

    if (mensajeErrorSelectCiudad !== true) {
        correcto = false;
        $("#ciudad").css("background-color", "#F1948A");
    }

    return correcto;
}

function comprobarErroresPerfil() {

    var correcto = true;

    //Error nombre del usuario
    var mensajeErrorNombreUsuario = compruebaTam50($("#nombre").val());

    //Error apellidos del usuario
    var mensajeErrorApellidosUsuario = compruebaTam150($("#apellidos").val());

    //Error password del usuario
    var mensajeErrorPasswordUsuario = compruebaVacio($("#password").val());

    //Error email
    var mensajeErrorEmail = compruebaEmail($("#email").val());

    //Error fechaNacimiento
    var mensajeErrorFechaNacim = compruebaFecha($("#fecha").val());

    //Error selectCiudad
    var mensajeErrorSelectCiudad = compruebaVacio($("#ciudad").val());

    //Tratamiento de errores del nombre
    if (mensajeErrorNombreUsuario !== true) {
        correcto = false;
        $("#nombre").css("background-color", "#F1948A");
    }

    if (mensajeErrorApellidosUsuario !== true) {
        correcto = false;
        $("#apellidos").css("background-color", "#F1948A");
    }

    if (mensajeErrorPasswordUsuario !== true) {
        correcto = false;
        $("#password").css("background-color", "#F1948A");
    }

    if (mensajeErrorEmail !== true) {
        correcto = false;
        $("#email").css("background-color", "#F1948A");
    }

    if (mensajeErrorFechaNacim !== true) {
        correcto = false;
        $("#fecha").css("background-color", "#F1948A");
    }

    if (mensajeErrorSelectCiudad !== true) {
        correcto = false;
        $("#ciudad").css("background-color", "#F1948A");
    }
    return correcto;
}

function comprobarErroresPerfilAdmin() {
    var correcto = true;

    //Error nombre del usuario
    var mensajeErrorNombreUsuario = compruebaTam50($("#nombre").val());

    //Error apellidos del usuario
    var mensajeErrorApellidosUsuario = compruebaTam150($("#apellidos").val());

    //Error email
    var mensajeErrorEmail = compruebaEmail($("#email").val());

    //Error fechaNacimiento
    var mensajeErrorFechaNacim = compruebaFecha($("#fecha").val());

    //Error selectCiudad
    var mensajeErrorSelectCiudad = compruebaVacio($("#ciudad").val());

    //Tratamiento de errores del nombre
    if (mensajeErrorNombreUsuario !== true) {
        correcto = false;
        $("#nombre").css("background-color", "#F1948A");
    }

    if (mensajeErrorApellidosUsuario !== true) {
        correcto = false;
        $("#apellidos").css("background-color", "#F1948A");
    }

    if (mensajeErrorEmail !== true) {
        correcto = false;
        $("#email").css("background-color", "#F1948A");
    }

    if (mensajeErrorFechaNacim !== true) {
        correcto = false;
        $("#fecha").css("background-color", "#F1948A");
    }

    if (mensajeErrorSelectCiudad !== true) {
        correcto = false;
        $("#ciudad").css("background-color", "#F1948A");
    }
    return correcto;
}

function comprobarErroresPasword() {

    var correcto = true;

    //Error password Actual del usuario
    var mensajeErrorPasswordActual = compruebaVacio($("#passwordActual").val());

    //Error nueva password del usuario
    var mensajeErrorNuevaPassword = compruebaVacio($("#passwordNueva").val());

    //Error repetir password del usuario
    var mensajeErrorRepetirPassword = compruebaVacio($("#repetirPassword").val());

    if (mensajeErrorPasswordActual !== true) {
        correcto = false;
        $("#passwordActual").css("background-color", "#F1948A");
    }

    if (mensajeErrorNuevaPassword !== true) {
        correcto = false;
        $("#passwordNueva").css("background-color", "#F1948A");
    }

    if (mensajeErrorRepetirPassword !== true) {
        correcto = false;
        $("#repetirPassword").css("background-color", "#F1948A");
    }

    return correcto;
}

function creaLocalizacion() {
    var nombre_element = document.getElementById("admin_new_location");
    var nombre = nombre_element.value.trim();
    if (nombre != "") {
        $.ajax({
            type: 'POST',
            url: "./remote_php/create_location_admin.php",
            data: {nom: nombre},
            success: function (data) {
                if (data) {
                    muestraLocalizaciones();
                    $(nombre_element).val("");
                    $(nombre_element).attr("style", "background-color:white");
                } else {
                    $(nombre_element).val("");
                    $(nombre_element).attr("placeholder", "Ciudad existente");
                    $(nombre_element).attr("style", "background-color:#F1948A");

                }
            },
            dataType: "json"
        });
    } else {
        $(nombre_element).val("");
        alert("El campo nombre no puede estar vacio");
    }
}

function muestraLocalizaciones() {
    var nombre = document.getElementById("admin_search_location").value;
    $("#list_locations").empty();
    $.ajax({
        type: 'POST',
        url: "./remote_php/get_locations_admin.php",
        data: {nom: nombre},
        success: function (data) {
            if (!data) {
                $("#list_locations").append("<h1>No hay coincidencias!</h1>");
            } else {
                var tam = data.length;
                for (var i = 0; i < tam; i++) {
                    $("#list_locations").append('<div id="' + data[i]['id'] + '" class="localizacion"><input type="text" id="' + data[i]['id'] + '" value="' + data[i]['ciudad'] + '" onchange="updateLocation(this)"><img src="images/remove.png" onclick="deleteLocation(this)" class="icono icono_localizacion"></div>');
                }
            }
        },
        dataType: "json"
    });
}

function deleteLocation(imagen) {
    var localizacion = imagen.parentElement.getAttribute("id");
    $.ajax({
        type: 'POST',
        url: "./remote_php/delete_location_admin.php",
        data: {loc: localizacion},
        success: function (data) {
            if (data) {
                muestraLocalizaciones();
            }
        },
        dataType: "json"
    });
}

function updateLocation(input) {
    var id = input.getAttribute("id");
    var nombre = input.value;
    $.ajax({
        type: 'POST',
        url: "./remote_php/update_location_admin.php",
        data: {id: id, nom: nombre},
        success: function (data) {
            if (data) {
                muestraLocalizaciones();
            } else {
                alert("La ciudad ya existe");
                muestraLocalizaciones();
            }
        },
        dataType: "json"
    });
}

function borrarEvento(evento) {
    var nombreEvento = evento.getAttribute("id");
    $.ajax({
        type: 'POST',
        url: "./remote_php/borrarEventoAjax.php",
        data: {name: nombreEvento},
        success: function (data) {
            var padre = evento.parentElement;
            $(padre).remove();
        },
        dataType: "json"
    });
}

function dejarEvento(evento) {
    var nombreEvento = evento.getAttribute("id");
    $.ajax({
        type: 'POST',
        url: "./remote_php/salirEventoAjax.php",
        data: {name: nombreEvento},
        success: function (data) {
            var padre = evento.parentElement;
            $(padre).remove();
        },
        dataType: "json"
    });
}

function anadirAlEvento(evento) {
    var nombreEvento = evento.getAttribute("id");
    $.ajax({
        type: 'POST',
        url: "./remote_php/anadirAlEventoAjax.php",
        data: {name: nombreEvento},
        success: function (data) {
            var padre = evento.parentElement;
            $(padre).remove();
        },
        dataType: "json"
    });
}

function borrarGrupo(grupo) {
    var nombreGrupo = grupo.getAttribute("id");
    $.ajax({
        type: 'POST',
        url: "./remote_php/grupoAjax.php",
        data: {name: nombreGrupo},
        success: function (data) {
            var padre = grupo.parentElement;
            $(padre).remove();
        },
        dataType: "json"
    });
}

function dejarGrupo(grupo) {
    var nombreGrupo = grupo.getAttribute("id");
    $.ajax({
        type: 'POST',
        url: "./remote_php/salirGrupoAjax.php",
        data: {name: nombreGrupo},
        success: function (data) {
            var padre = grupo.parentElement;
            $(padre).remove();
        },
        dataType: "json"
    });
}

function anadirAlGrupo(grupo) {
    var nombreGrupo = grupo.getAttribute("id");
    $.ajax({
        type: 'POST',
        url: "./remote_php/anadirAlGrupoAjax.php",
        data: {name: nombreGrupo},
        success: function (data) {
            var padre = grupo.parentElement;
            $(padre).remove();
        },
        dataType: "json"
    });
}