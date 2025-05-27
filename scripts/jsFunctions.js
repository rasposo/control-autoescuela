//funciones de validación

function esDniValido(valor) {
    valor = valor.toUpperCase().trim();

    const letras = 'TRWAGMYFPDXBNJZSQVHLCKE';

    // DNI: 8 dígitos + letra
    if (/^[0-9]{8}[A-Z]$/.test(valor)) {
        const numero = valor.slice(0, 8);
        const letra = valor.slice(-1);
        return letras[numero % 23] === letra;
    }

    // NIE: X/Y/Z + 7 dígitos + letra
    if (/^[XYZ][0-9]{7}[A-Z]$/.test(valor)) {
        const letraInicial = valor.charAt(0);
        const numero = valor.slice(1, 8);
        const letraFinal = valor.slice(-1);

        const prefijoNumerico = { 'X': '0', 'Y': '1', 'Z': '2' }[letraInicial];
        const numeroCompleto = prefijoNumerico + numero;

        return letras[numeroCompleto % 23] === letraFinal;
    }

    return false;
}

function esEmailValido(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email.trim());
}

function esTelefonoValido(telefono) {
    const regex = /^[6789][0-9]{8}$/;
    return regex.test(telefono.trim());
}

function llamarAPIbuscarProfesor() {

    //borramos búsquedas anteriores
    $('#cuerpo_tabla').empty();
    
    //recuperamos elemento a buscar
    let nom = document.getElementById("prof-name-find").value;

    //formulario JS
    let fd = new FormData();
    fd.append('nombre', nom);

    // Llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/buscarProfe.php");
    xhr.onreadystatechange = function() {
            
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;		
            var obj = JSON.parse(string);
            for ( let profesor of obj['search']) {
                $('#cuerpo_tabla').append('<tr><td><a href="profesor.php?profesor_id='+profesor['profesor_id']+'"> \
                '+profesor['nombre']+' '+profesor['apellido1']+' '+profesor['apellido2']+'</a></td>')
            }
        }
    }
    xhr.send(fd);
}

function llamarAPIguardarProfesor() {

    //recuperamos elementos a actualizar
    let profesor_id =  document.getElementById("profesor_id").value;
    let nombre =       document.getElementById("nombre").value;
    if ( nombre.length < 2 ) {
        alert ("Por favor, introduzca un nombre válido");
        return;
    }
    let apellido1 =  document.getElementById("apellido1").value;
    let apellido2=   document.getElementById("apellido2").value;
    if ( apellido1.length < 2 || apellido2.length < 2 ) {
        alert ("Por favor, introduzca dos apellidos");
        return;
    }
    let DNI= document.getElementById("DNI").value;
    if ( !esDniValido(DNI) ) {
        alert ("El formato de DNI no es correcto");
        return;
    }
    let caducidad_dni = document.getElementById("caducidad_dni").value;
    if ( caducidad_dni.length < 7 ) {
        alert ("Por favor, introduzca una fecha de caducidad correcta");
        return;
    }
    let telefono = document.getElementById("telefono").value.trim();
    if ( !esTelefonoValido(telefono) ) {
        alert ("El formato del número de teléfono no es correcto. Introduzca 9 cifras");
        return;
    }
    let email = document.getElementById("email").value;
    if ( !esEmailValido(email) ) {
        alert ("El formato del email no es correcto");
        return;
    }
    let direccion = document.getElementById("direccion").value;
    if ( direccion.length < 5 ) {
        alert ("Por favor, introduzca una dirección correcta");
        return;
    }
    let codigo_postal = document.getElementById("codigo_postal").value.trim();
    if ( codigo_postal.length != 5 ) {
        alert ("Por favor, introduzca un código postal correcto");
        return;
    }
    let localidad = document.getElementById("localidad").value;
    if ( localidad.length < 3 ) {
        alert ("Por favor, introduzca una localidad correcta");
        return;
    }
    let provincia = document.getElementById("provincia").value;
    if ( provincia.length < 3 ) {
        alert ("Por favor, introduzca una provincia correcta");
        return;
    }
    let fecha_nacimiento = document.getElementById("fecha_nacimiento").value;
    if ( fecha_nacimiento.length < 7 ) {
        alert ("Por favor, introduzca una fecha de nacimiento correcta");
        return;
    }
    let fecha_ingreso = document.getElementById("fecha_ingreso").value;
    if ( fecha_ingreso.length < 7 ) {
        alert ("Por favor, introduzca una fecha de ingreso correcta");
        return;
    }
    let numero_ss = document.getElementById("numero_ss").value;
    if ( numero_ss.length < 7 ) {
        alert ("Por favor, introduzca un número de seguridad social correcto");
        return;
    }
    let contraseña = document.getElementById("contraseña").value;
    if ( contraseña.length < 5 ) {
        alert ("Por favor, introduzca una contraseña correcta");
        return;
    }

    //cargamos los perfiles
    let perfiles = [];
    $('#perfiles_form input[type=checkbox]').each(function() {
        if(this.checked) {
            perfiles.push($(this).val());
        };
    });

    //seleccionamos los perfiles si existen
    let perfil_administrador = 0;
    if (perfiles.includes('administrador')) {
        perfil_administrador = 1;
    }
    let perfil_director = 0;
    if (perfiles.includes('director')) {
        perfil_director = 1;
    };


    //formulario JS
    let fd = new FormData();
    fd.append('profesor_id', profesor_id);
    fd.append('nombre', nombre);
    fd.append('apellido1', apellido1);
    fd.append('apellido2', apellido2);
    fd.append('DNI', DNI);
    fd.append('caducidad_dni', caducidad_dni);
    fd.append('telefono', telefono);
    fd.append('email', email);
    fd.append('direccion', direccion);
    fd.append('codigo_postal', codigo_postal);
    fd.append('localidad', localidad);
    fd.append('provincia', provincia);
    fd.append('fecha_nacimiento', fecha_nacimiento);
    fd.append('fecha_ingreso', fecha_ingreso);
    fd.append('numero_ss', numero_ss);
    fd.append('contraseña', contraseña);
    fd.append('perfil_administrador', perfil_administrador);
    fd.append('perfil_director', perfil_director);

    //cargamos los permisos y los añadimos al formulario"
    $('#permisos_form input[type=checkbox]').each(function() {
        if(this.checked) {
            let permiso = $(this).val();
            let fecha = document.getElementById("fecha"+$(this).val()).value;
            fd.append(permiso, fecha);
        }
    });

    // Llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/guardarProfesor.php");
    xhr.onreadystatechange = function() {
        
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;
            var obj = JSON.parse(string);
            if (obj["id"] == 0) {
                alert ("Profesor guardado");
                window.location.href = "profesor.php?profesor_id="+obj['profesor_id'];
			} else {
				alert (obj["texto"]);
			}
        }
    }
    xhr.send(fd);
}

function llamarAPIguardarPermisosProfesor() {

    //recuperamos elementos a actualizar
    let profesor_id = document.getElementById("profesor_id").value;


    //formulario JS
    let fd = new FormData();
    fd.append('profesor_id', profesor_id);

    //cargamos los permisos y los añadimos al formulario"
    let permisos = [];
    $('#permisos_form input[type=checkbox]').each(function() {
        if(this.checked) {
            let p = "";
            let fecha = document.getElementById("fecha"+$(this).val()).value;
            if ( fecha-length < 7 ) { 
                window.location.href = "profesor.php?profesor_id="+profesor_id; 
                alert ("fecha incorrecta");
            };
            p = $(this).val() + ":"+ fecha;
            permisos.push(p);
        }
    });
    fd.append('permisos', permisos);

    // Llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/guardarPermisoProfesor.php");
    xhr.onreadystatechange = function() {
        
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;
            var obj = JSON.parse(string);
            if (obj["id"] == 0) {
				alert ("Permisos guardados");
                window.location.href = "profesor.php?profesor_id="+profesor_id;
			} else {
				alert (obj["texto"]);
			}
        }
    }
    xhr.send(fd);
}

function llamarAPIbuscarAlumno() {

    //borramos búsquedas anteriores
    $('#cuerpo_tabla').empty();
    
    //recuperamos elemento a buscar
    let nom = document.getElementById("prof-alumn-find").value;
    if ( nom.length < 2  ) {
        window.location.href = "alumnos.php";
        alert ( "Introduzca al menos 2 letras o números" );
    };

    //formulario JS
    let fd = new FormData();
    fd.append('nombre', nom);

    // Llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/buscarAlumno.php");
    xhr.onreadystatechange = function() {
            
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;		
            var res = JSON.parse(string);
            let obj = res['search'];

            //ordeno empezando por el último y creo la tabla
            let listado = obj.reverse();
            for ( let alumno of listado) {
                let cur = alumno['cursos'];
                let cursos = "";
                for (let c of cur ){
                    if ( c['finalizado'] !== "1" ) {
                        cursos += '<a href="curso.php?curso_id='+c['curso_id']+'">Nº '+c['numero_curso']+' - Permiso '+c['tipo']+'  </a>';
                    };
                };
                $('#cuerpo_tabla').append('<tr><td><a href="alumno.php?alumno_id='+alumno['alumno_id']+'"> \
                '+alumno['nombre']+' '+alumno['apellido1']+' '+alumno['apellido2']+'</a></td><td>'+cursos+'</td>');
            };
        };
    };
    xhr.send(fd);
};


function llamarAPIbuscarAlumnoPago() {

    //borramos búsquedas anteriores
    $('#cuerpo_tabla').empty();
    
    //recuperamos elemento a buscar
    let nom = document.getElementById("prof-alumn-find").value;
    if ( nom.length < 2  ) {
        window.location.href = "pagos.php";
        alert ( "Introduzca al menos 2 letras o números" );
    };

    //formulario JS
    let fd = new FormData();
    fd.append('nombre', nom);

    // Llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/buscarAlumno.php");
    xhr.onreadystatechange = function() {
            
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;		
            var res = JSON.parse(string);
            let obj = res['search'];

            //ordeno empezando por el último y recorro el listado
            let listado = obj.reverse();
            for ( let alumno of listado) {
                let cur = alumno['cursos'];
                let cursos = "";
                //filtro cursos en activo
                for (let c of cur ){
                    if ( c['finalizado'] !== "1" ) {
                        cursos += 'Seccion '+c['seccion']+' - <a href="pago.php?curso_id='+c['curso_id']+'">Nº '+c['numero_curso']+' - Permiso '+c['tipo']+' </a>';
                        $('#cuerpo_tabla').append('<tr><td><a href="alumno.php?alumno_id='+alumno['alumno_id']+'"> \
                        '+alumno['nombre']+' '+alumno['apellido1']+' '+alumno['apellido2']+'</a></td><td>'+cursos+'</td>');
                    };
                };
            };
        }
    }
    xhr.send(fd);
}

function llamarAPIguardarAlumno() {

    //recuperamos elementos a actualizar
    let alumno_id = document.getElementById("alumno_id").value;
    let nombre =    document.getElementById("nombre").value;
    if ( nombre.length < 2 ) {
        alert ("Por favor, introduzca un nombre");
        return; 
    }
    let apellido1 = document.getElementById("apellido1").value;
    let apellido2=  document.getElementById("apellido2").value;
    if ( apellido1.length < 2 || apellido2.length < 2 ) {
        alert ("Por favor, introduzca dos apellidos");
        return;
    }
    let dni = document.getElementById("dni").value;
    if ( !esDniValido(dni) ) {
        alert ("Por favor, introduzca un DNI correcto");
        return;
    }
    let caducidad_dni = document.getElementById("caducidad_dni").value;
    if ( caducidad_dni.length < 7 ) {
        alert ("Por favor, introduzca una fecha de caducidad correcta");
        return;
    }
    let telefono = document.getElementById("telefono").value;
    if ( !esTelefonoValido(telefono) ) {
        alert ("El formato del número de teléfono no es correcto. Introduzca 9 cifras");
        return;
    }
    let email = document.getElementById("email").value;
    if ( !esEmailValido(email) ) {
        alert ("El formato del emaail no es correcto");
        return;
    }
    let direccion = document.getElementById("direccion").value;
    if ( direccion.length < 5 ) {
        alert ("Por favor, introduzca una dirección correcta");
        return;
    }
    let codigo_postal = document.getElementById("codigo_postal").value;
    if ( codigo_postal.length < 5 ) {
        alert ("Por favor, introduzca un código postal correcto");
        return;
    }
    let localidad = document.getElementById("localidad").value;
    if ( localidad.length < 5 ) {
        alert ("Por favor, introduzca una localidad correcta");
        return;
    }
    let provincia = document.getElementById("provincia").value;
    if ( provincia.length < 5 ) {
        alert ("Por favor, introduzca una provincia correcta");
        return;
    }
    let nacionalidad = document.getElementById("nacionalidad").value;
    if ( nacionalidad.length < 5 ) {
        alert ("Por favor, introduzca una nacionalidad correcta");
        return;
    }
    let fecha_nacimiento = document.getElementById("fecha_nacimiento").value;
    if ( fecha_nacimiento.length < 7 ) {
        alert ("Por favor, introduzca una fecha de nacimiento correcta");
        return;
    }
    let fecha_ingreso = document.getElementById("fecha_ingreso").value;
    if ( fecha_ingreso.length < 7 ) {
        alert ("Por favor, introduzca una fecha de ingreso correcta");
        return;
    }
    let estudios = document.getElementById("estudios").value;
    if ( estudios.length < 5 ) {
        alert ("Por favor, introduzca unos estudios correctos");
        return;
    }
    let contraseña = document.getElementById("contraseña").value;
    if ( contraseña.length < 5 ) {
        alert ("Por favor, introduzca una contraseña correcta");
        return;
    }

    //formulario JS
    let fd = new FormData();
    fd.append('alumno_id', alumno_id);
    fd.append('nombre', nombre);
    fd.append('apellido1', apellido1);
    fd.append('apellido2', apellido2);
    fd.append('dni', dni);
    fd.append('caducidad_dni', caducidad_dni);
    fd.append('telefono', telefono);
    fd.append('email', email);
    fd.append('direccion', direccion);
    fd.append('codigo_postal', codigo_postal);
    fd.append('localidad', localidad);
    fd.append('provincia', provincia);
    fd.append('nacionalidad', nacionalidad);
    fd.append('fecha_nacimiento', fecha_nacimiento);
    fd.append('fecha_ingreso', fecha_ingreso);
    fd.append('estudios', estudios);
    fd.append('contraseña', contraseña);

    // Llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/guardarAlumno.php");
    xhr.onreadystatechange = function() {
        
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;
            var obj = JSON.parse(string);
            if (obj["id"] == 0) {
				alert (obj["texto"]);
                window.location.href = "alumno.php?alumno_id=" + obj["alumno_id"];
			} else {
				alert (obj["texto"]);
			}
        }
    }
    xhr.send(fd);
}

function llamarAPIbuscarCurso() {

    //borramos búsquedas anteriores
    $('#cuerpo_tabla').empty();
    
    //recuperamos elemento a buscar
    let nom = document.getElementById("curso-find").value;
    if ( nom.length < 2  ) {
        window.location.href = "cursos.php";
        alert ( "Introduzca al menos 2 letras o números" );
    };

    //formulario JS
    let fd = new FormData();
    fd.append('nombre', nom);

    // Llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/buscarAlumno.php");
    xhr.onreadystatechange = function() {
            
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;		
            var res = JSON.parse(string);
            let obj = res['search'];

            //ordenamos la lista desde el más nuevo
            let listado = obj.reverse();
            for ( let alumno of listado) {
                for (let curso of alumno['cursos']) {
                    if ( curso['finalizado'] !== "1" ) {
                    $('#cuerpo_tabla').append('<tr><td>'+curso['seccion']+'</a></td> \
                    <td><a href="curso.php?curso_id='+curso['curso_id']+'">'+curso['numero_curso']+' Permiso '+curso['tipo']+'</a></td> \
                    <td><a href="alumno.php?alumno_id='+alumno['alumno_id']+'"> \
                    '+alumno['nombre']+' '+alumno['apellido1']+' '+alumno['apellido2']+'</a></td>')
                    }
                }
            }
        }
    }
    xhr.send(fd);
}

function llamarAPIhistoricoCursos() {

    //borramos búsquedas anteriores
    $('#cuerpo_tabla').empty();

    //recuperamos las fechas para mostrar
    let inicio = document.getElementById("fecha-inicial").value;
    let final = document.getElementById("fecha-final").value;

    //formulario JS
    let fd = new FormData();

    fd.append('inicio', inicio);
    fd.append('final', final);

    //llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/historicoCursos.php");
    xhr.onreadystatechange = function () {

        //el status 200 y 4 significa q se ha ejecutado
         if (xhr.readyState == 4 && xhr.status == 200) {	
             // recupero la respuesta del API
             var string = xhr.response;		
             var obj = JSON.parse(string);
             for ( let curso of obj )
                $('#cuerpo_tabla').append('<tr><td><a href="../paginas/alumno.php?alumno_id='+curso['alumno_id']+'"> \
                    '+curso['nombre_alumno']+' '+curso['apellido1_alumno']+' '+curso['apellido2_alumno']+'</a></td> \
                    <td>'+curso['seccion']+'</td> \
                    <td><a href="../paginas/curso-historico.php?curso_id='+curso['curso_id']+'">Nº '+curso['numero_curso']+' - Curso '+curso['permiso']+'</a> \
                    </td><td>'+curso['fecha_inicio']+'</td><td>'+curso['fecha_finalizacion']+'</td></tr>');
        }
    }
    xhr.send(fd);
}

function llamarAPIguardarCurso() {

    //recuperamos elementos a actualizar
    let curso_id =                  document.getElementById("curso_id").value;
    let alumno_id =                 document.getElementById("alumno_id").value;
    let permiso_id =                document.getElementById("permiso_id").value;
    let fecha_inicio =              document.getElementById("inicio").value;
    let fecha_finalizacion =        document.getElementById("fecha_finalizacion").value;

    //cargamos finalizado y pagado y los añadimos al formulario"
    let finalizar = []
    let finalizado = 0;
    let pagado = 0;
    $('#finalizar input[type=checkbox]').each(function() {
        if(this.checked) {
            finalizar.push($(this).val());
        };
    });

    if (finalizar.includes('finalizado')) {
        finalizado = 1;
    };
    if (finalizar.includes('pagado')) {
        pagado = 1;
    };


    //formulario JS
    let fd = new FormData();
    fd.append('curso_id', curso_id);
    fd.append('alumno_id', alumno_id);
    fd.append('permiso_id', permiso_id);
    fd.append('inicio', fecha_inicio);
    fd.append('finalizado', finalizado);
    fd.append('fecha_finalizacion', fecha_finalizacion);
    fd.append('pagado', pagado);

    // Llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/guardarCurso.php");
    xhr.onreadystatechange = function() {
        
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;
            var obj = JSON.parse(string);
            if (obj["id"] == 0) {
				alert ("Curso guardado");
                window.location.href = "curso.php?curso_id="+curso_id;
			} else {
				alert (obj["texto"]);
			}
        }
    }
    xhr.send(fd);

}

function nuevoCurso() {

    //cogemos los elemntos a incluir
    let alumno_id = document.getElementById("alumno_id").value;
    const tipo_curso = document.querySelector('input[name="permiso-curso"]:checked').value;
    const seccion = document.querySelector('input[name="seccion"]:checked').value;

    //hacemos el formulario JS
    let fd = new FormData();
    fd.append('curso', tipo_curso);
    fd.append('seccion', seccion);
    fd.append('alumno_id', alumno_id);
    

    // Llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/nuevoCurso.php");

    xhr.onreadystatechange = function() {
        
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;
            var obj = JSON.parse(string);
            if (obj["id"] == 0) {
				alert ("Curso creado");
                window.location.href = "curso.php?curso_id="+obj['curso_id'];
			} else {
				alert (obj["texto"]);
			}
        }
    }
    xhr.send(fd);
}

function cambiarProfesor() {

    //recupero el id del curso

    const curso_id = document.getElementById("curso_id").value;


    //formulario JS
    let fd = new FormData();
    fd.append('', '');

     // Llamada HTTP
     var xhr = new XMLHttpRequest();
     xhr.open("POST", "../scripts/buscarProfe.php");
     xhr.onreadystatechange = function() {
             
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;		
            var obj = JSON.parse(string);
            if (obj["id"] == 0) {
                $('#lista-profes').empty();
                for ( let profesor of obj['search']) {
                    $('#lista-profes').append('<p><input type="checkbox" name="profe[]" id="" value="'+profesor['profesor_id']+'"><span> \
                    '+profesor['nombre']+' '+profesor['apellido1']+' '+profesor['apellido2']+'</span></p>')
                }
            } else {
                alert (obj["texto"]);
            }
        }
    }
    xhr.send(fd);
}

function adjudicarProfesor() {

    //recupero el id del curso
    const curso_id = document.getElementById("curso_id").value;

    //cargamos los cargos
    let profes = [];
    $('#lista-profes input[type=checkbox]').each(function() {
        if(this.checked) {
        profes.push($(this).val());
        };
    });

    //formulario JS
    let fd = new FormData();
    fd.append('profes', profes);
    fd.append('curso_id', curso_id);

     // Llamada HTTP
     var xhr = new XMLHttpRequest();
     xhr.open("POST", "../scripts/adjudicarProfe.php");
     xhr.onreadystatechange = function() {
             
         //el status 200 y 4 significa q se ha ejecutado
         if (xhr.readyState == 4 && xhr.status == 200) {	
             // recupero la respuesta del API
             var string = xhr.response;		
             var obj = JSON.parse(string);
             if (obj["id"] == 0) {
				alert (obj['texto']);
                window.location.href = "curso.php?curso_id=" + curso_id;
			} else {
				alert (obj["texto"]);
			}
         }
     }
     xhr.send(fd);
}


async function generarReciboPago (recibo, alumno, curso_id) {

    const { PDFDocument, rgb, StandardFonts } = PDFLib

    const numeroRecibo = recibo['numero'];
    const fecha = String(recibo['fecha']);
    const importe = String(recibo['importe'] + " €");
    const concepto = recibo['concepto'];

    const curso = alumno['curso'];
    const nombre = alumno['nombre'];
    const direccion = alumno['direccion'];
    const localidad = alumno['localidad'];
    const provincia = alumno['provincia'];


    const url = '../forms/recibo_pago.pdf';
    const existingPdfBytes = await fetch(url).then(res => res.arrayBuffer());
    console.log("documento cargado")

    const pdfDoc = await PDFDocument.load(existingPdfBytes);
    const timesRomanFont = await pdfDoc.embedFont(StandardFonts.TimesRoman);

    const pages = pdfDoc.getPages();
    const firstPage = pages[0];
    
    //primer recibo
    firstPage.drawText(numeroRecibo, {
        x: 140,
        y: 672,
        size: 11,
        font: timesRomanFont,
    })
    firstPage.drawText(fecha, {
        x: 270,
        y: 672,
        size: 11,
        font: timesRomanFont
    })
    firstPage.drawText(importe, {
        x: 444,
        y: 672,
        size: 11,
        font: timesRomanFont
    })
    firstPage.drawText(concepto, {
        x: 125,
        y: 648,
        size: 11,
        font: timesRomanFont
    })
    firstPage.drawText("Curso: " + curso, {
        x: 75,
        y: 600,
        size: 11,
        font: timesRomanFont
    })
    firstPage.drawText(nombre, {
        x: 75,
        y: 585,
        size: 11,
        font: timesRomanFont
    })
    firstPage.drawText(direccion, {
        x: 75,
        y: 570,
        size: 11,
        font: timesRomanFont
    })
    firstPage.drawText(localidad, {
        x: 75,
        y: 555,
        size: 11,
        font: timesRomanFont
    })
    firstPage.drawText(provincia, {
        x: 75,
        y: 540,
        size: 11,
        font: timesRomanFont
    })

    //segundo recibo
    firstPage.drawText(numeroRecibo, {
        x: 140,
        y: 672 - 388,
        size: 11,
        font: timesRomanFont,
    })
    firstPage.drawText(fecha, {
        x: 270,
        y: 672 - 388,
        size: 11,
        font: timesRomanFont
    })
    firstPage.drawText(importe, {
        x: 444,
        y: 672 - 388,
        size: 11,
        font: timesRomanFont
    })
    firstPage.drawText(concepto, {
        x: 125,
        y: 648 - 388,
        size: 11,
        font: timesRomanFont
    })
    firstPage.drawText('Curso' + curso, {
        x: 75,
        y: 600 - 388,
        size: 11,
        font: timesRomanFont
    })
    firstPage.drawText(nombre, {
        x: 75,
        y: 585 - 388,
        size: 11,
        font: timesRomanFont
    })
    firstPage.drawText(direccion, {
        x: 75,
        y: 570 - 388,
        size: 11,
        font: timesRomanFont
    })
    firstPage.drawText(localidad, {
        x: 75,
        y: 555- 388,
        size: 11,
        font: timesRomanFont
    })
    firstPage.drawText(provincia, {
        x: 75,
        y: 540 - 388,
        size: 11,
        font: timesRomanFont
    })

    console.log("documento creado");
        
    const pdfBytes = await pdfDoc.save();
    download(pdfBytes, "recibo-pago.pdf", "application/pdf");
    alert ("Pago realizado");
    window.location.href = "curso.php?curso_id="+curso_id;
}

function introducirPago() {

    //recuperamos valores
    let curso_id = document.getElementById( "curso_id" ).value;
    let concepto = document.getElementById( "confirmacion-concepto" ).value;
    let importe = document.getElementById( "confirmacion-importe" ).value;
    let recibo = document.getElementById( "recibo-pago" );

    if ( importe == "" ) {
        alert ("Por favor, introduzca un importe");
        return;
    };

    if ( typeof concepto !== 'string' ) {
        concepto = "";
    }

    //formulario JS
    let fd = new FormData();
    fd.append( 'curso_id', curso_id );
    fd.append( 'concepto', concepto );
    fd.append( 'importe', importe );
    
    //llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/realizarPago.php");
    xhr.onreadystatechange = function () {

        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;
            var obj = JSON.parse(string);
            if (obj["id"] == 0) {
                if ( recibo.checked ) {
                    generarReciboPago( obj["recibo"], obj["alumno"], curso_id );
                } else {
                    alert ("Pago realizado");
                    window.location.href = "curso.php?curso_id="+curso_id;
                }   
            } else {
                alert (obj["texto"]);
            }
        }
    }
    xhr.send(fd);
};

function nuevoCargo() {

    //recuperamos información

    const curso_id = document.getElementById("curso_id").value;
    let fecha = document.getElementById("fecha-cargo").value;
    if ( fecha == "" ) {
        let fechaPorDefecto = new Date();
        let año = fechaPorDefecto.getFullYear();
        let mes = fechaPorDefecto.getMonth() + 1;
        let dia = fechaPorDefecto.getDate();
        fecha = año + "/" + mes + "/" + dia;
    }

    //cargamos los cargos
    let cargos = [];
    $('#formulario-cargo input[type=checkbox]').each(function() {
        if(this.checked) {
            cargos.push($(this).val());
        };
    });

    //formulario JS
    let fd = new FormData();
    fd.append( 'fecha', fecha );
    fd.append( 'curso_id', curso_id );
    fd.append( 'cargos', cargos );

     // Llamada HTTP
     var xhr = new XMLHttpRequest();
     xhr.open("POST", "../scripts/guardarCargo.php");
     xhr.onreadystatechange = function() {
             
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
             // recupero la respuesta del API
             var string = xhr.response;		
             var obj = JSON.parse(string);
             if (obj["id"] == 0) {  
                alert ("Cargo guardado");
                window.location.href = "curso.php?curso_id="+curso_id;
             } else {
                alert (obj["texto"]);
             }
        }
    }
    xhr.send(fd);
};

function nuevaClase() {

    //recuperamos información

    const curso_id = document.getElementById("curso_id").value;
    let profesor_id = document.querySelector('input[name="id-profe-clase"]:checked').value;
    const enseñanza = document.querySelector('input[name="tipo-clase"]:checked').value;
    const hora = document.querySelector('input[name="franja-hora"]:checked').value;
    let fecha = document.getElementById("fecha-clase").value;

    if ( fecha == "" ) {
        let fechaPorDefecto = new Date();
        let año = fechaPorDefecto.getFullYear();
        let mes = fechaPorDefecto.getMonth() + 1;
        let dia = fechaPorDefecto.getDate();
        fecha = año + "/" + mes + "/" + dia;
    }

    if ( profesor_id == null ) {
        profesor_id = 1;
    };

    //formulario JS
    let fd = new FormData();
    fd.append( 'enseñanza', enseñanza );
    fd.append( 'fecha', fecha );
    fd.append( 'hora', hora );
    fd.append( 'curso_id', curso_id );
    fd.append( 'profesor_id', profesor_id );

     // Llamada HTTP
     var xhr = new XMLHttpRequest();
     xhr.open("POST", "../scripts/guardarClase.php");
     xhr.onreadystatechange = function() {
             
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
             // recupero la respuesta del API
             var string = xhr.response;		
             var obj = JSON.parse(string);
             if (obj["id"] == 0) {  
                alert ("Clase guardada");
                window.location.href = "curso.php?curso_id="+curso_id;
             } else {
                alert (obj["texto"]);
             }
        }
    }
    xhr.send(fd);
};


function llamarAPIhistoricoPagos() {

    //borramos búsquedas anteriores
    $('#cuerpo_tabla-pagos').empty();

    //recuperamos las fechas para mostrar
    let inicio = document.getElementById("fecha-inicial-pago").value;
    let final = document.getElementById("fecha-final-pago").value;

    //formulario JS
    let fd = new FormData();

    fd.append('inicio', inicio);
    fd.append('final', final);

    //llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/historicoPagos.php");
    xhr.onreadystatechange = function () {

        //el status 200 y 4 significa q se ha ejecutado
         if (xhr.readyState == 4 && xhr.status == 200) {	
             // recupero la respuesta del API
             var string = xhr.response;		
             var obj = JSON.parse(string);

             //creamos y añadimos la tabla
             let montante = 0;
             let listado = obj.reverse();
             for ( let pago of listado ) {
                //filtramos pagos anulados y montamos la tabla
                if ( pago['anulado'] !== "1" ) {
                
                    montante += parseInt(pago['importe']);
                    $('#cuerpo_tabla-pagos').append('<tr><td>'+pago['numero_recibo']+'</td><td><a href="../paginas/alumno.php?alumno_id='+pago['alumno_id']+'"> \
                    '+pago['nombre_alumno']+' '+pago['apellido1_alumno']+' '+pago['apellido2_alumno']+'</a></td> \
                    <td><a href="../paginas/curso.php?curso_id='+pago['curso_id']+'">Nº '+pago['numero_curso']+' - '+pago['permiso']+'</a></td> \
                    <td>'+pago['fecha']+'</td><td>'+pago['concepto']+'</td><td>'+pago['importe']+' €</td></tr>');
                }
             }

             $('#montante').empty();
             $('#montante').append(montante+" €");
        }
    }
    xhr.send(fd);
}

function llamarAPIhistoricoPagosAnulados() {

    //borramos búsquedas anteriores
    $('#cuerpo_tabla-pagos-anulados').empty();

    //recuperamos las fechas para mostrar
    let inicio = document.getElementById("fecha-inicial-pago-anulado").value;
    let final = document.getElementById("fecha-final-pago-anulado").value;

    //formulario JS
    let fd = new FormData();

    fd.append('inicio', inicio);
    fd.append('final', final);

    //llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/historicoPagos.php");
    xhr.onreadystatechange = function () {

        //el status 200 y 4 significa q se ha ejecutado
         if (xhr.readyState == 4 && xhr.status == 200) {	
             // recupero la respuesta del API
             var string = xhr.response;		
             var obj = JSON.parse(string);

             //creamos y añadimos la tabla
             let listado = obj.reverse();
             for ( let pago of listado ) {
                //filtramos pagos anulados y montamos la tabla
                if ( pago['anulado'] == "1" ) {
                
                    $('#cuerpo_tabla-pagos-anulados').append('<tr><td>'+pago['numero_recibo']+'</td><td><a href="../paginas/alumno.php?alumno_id='+pago['alumno_id']+'"> \
                    '+pago['nombre_alumno']+' '+pago['apellido1_alumno']+' '+pago['apellido2_alumno']+'</a></td> \
                    <td><a href="../paginas/curso.php?curso_id='+pago['curso_id']+'">Nº '+pago['numero_curso']+' - '+pago['permiso']+'</a></td> \
                    <td>'+pago['fecha']+'</td><td>'+pago['motivo_anulado']+'</td><td>'+pago['importe']+' €</td></tr>');
                }
             }
        }
    }
    xhr.send(fd);
}

function nuevoPermiso() {

    //recuperamos información

    const tipo = document.getElementById("tipo-nuevo-permiso").value;

    if ( tipo.length > 1 ) {

                //formulario JS
                let fd = new FormData();
                fd.append( 'tipo', tipo );
        
                // Llamada HTTP
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "../scripts/guardarPermiso.php");
                xhr.onreadystatechange = function() {
                        
                    //el status 200 y 4 significa q se ha ejecutado
                    if (xhr.readyState == 4 && xhr.status == 200) {	
                        // recupero la respuesta del API
                        var string = xhr.response;		
                        var obj = JSON.parse(string);
                        if (obj["id"] == 0) {  
                            alert ("Nuevo permiso/curso creado");
                            window.location.href = "configuracion.php"
                        } else {
                            alert (obj["texto"]);
                        }
                    }
                }
                xhr.send(fd);

    } else {
        alert('Por favor, introduzca un nombre y precio válidos.');
    };
};

function nuevaEnseñanza(permiso_id) {

    //recuperamos información

    const tipo = document.getElementById("nueva-ense-tipo-"+permiso_id).value;
    const precio = Number(document.getElementById("nueva-ense-precio-"+permiso_id).value);

    if ( tipo.length > 1 && precio > 1 ) {

                //formulario JS
                let fd = new FormData();
                fd.append( 'tipo', tipo );
                fd.append( 'precio', precio );
                fd.append( 'permiso_id', permiso_id );
        
                // Llamada HTTP
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "../scripts/guardarEnseñanza.php");
                xhr.onreadystatechange = function() {
                        
                    //el status 200 y 4 significa q se ha ejecutado
                    if (xhr.readyState == 4 && xhr.status == 200) {	
                        // recupero la respuesta del API
                        var string = xhr.response;		
                        var obj = JSON.parse(string);
                        if (obj["id"] == 0) {  
                            alert ("Enseñanza/clase guardada");
                            window.location.href = "configuracion.php"
                        } else {
                            alert (obj["texto"]);
                        }
                    }
                }
                xhr.send(fd);

    } else {
        alert('Por favor, introduzca un nombre y precio válidos.');
        window.location.href = "configuracion.php";

    };
};

function modificarEnseñanza(enseñanza_id) {
    //modificamos la celda para introducir los datos

    document.getElementById("nombre-enseñanza-"+enseñanza_id).innerHTML = 
        '<input type="text" id="modificar-ense-tipo-'+enseñanza_id+'" size="8">';

    document.getElementById("precio-enseñanza-"+enseñanza_id).innerHTML = 
        '<input type="text" id="modificar-ense-precio-'+enseñanza_id+'" size="3">';

    document.getElementById("modificar-enseñanza-"+enseñanza_id).innerHTML = 
        '<a href="#" onclick="introducirModificarEnseñanza('+enseñanza_id+')">Introducir</a>';

    $("#modificar-enseñanza-"+enseñanza_id).append('<a href="configuracion.php"> / Cancelar</a>');
}

function introducirModificarEnseñanza(enseñanza_id) {

    const tipo = document.getElementById("modificar-ense-tipo-"+enseñanza_id).value;
    const precio = Number(document.getElementById("modificar-ense-precio-"+enseñanza_id).value);

    if ( tipo.length > 1 && precio > 1 ) {

                //formulario JS
                let fd = new FormData();
                fd.append( 'tipo', tipo );
                fd.append( 'precio', precio );
                fd.append( 'enseñanza_id', enseñanza_id);
        
                // Llamada HTTP
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "../scripts/guardarEnseñanza.php");
                xhr.onreadystatechange = function() {
                        
                    //el status 200 y 4 significa q se ha ejecutado
                    if (xhr.readyState == 4 && xhr.status == 200) {	
                        // recupero la respuesta del API
                        var string = xhr.response;		
                        var obj = JSON.parse(string);
                        if (obj["id"] == 0) {  
                            alert ("Enseñanza/clase guardada");
                            window.location.href = "configuracion.php"
                        } else {
                            alert (obj["texto"]);
                        }
                    }
                }
                xhr.send(fd);

    } else {
        alert('Por favor, introduzca un nombre y precio válidos.');

    };
}

function llamarAPIguardarAutoescuela() {

    //recuperamos elementos a actualizar
    let autoescuela_id =document.getElementById("autoescuela_id").value;
    let nombre =        document.getElementById("nombre").value;
    if ( nombre.length < 3 ) {
        alert ("Por favor, introduzca un nombre correcto");
        return;
    }
    let razonSocial =   document.getElementById("razon-social").value;
    if ( razonSocial.length < 5 ) {
        alert ("Por favor, introduzca una razón social correcta");
        return;
    }
    let numeroCentro=   document.getElementById("numero-centro").value;
    if ( numeroCentro.length < 2 ) {
        alert ("Por favor, introduzca un número de centro correcto");
        return;
    }
    let seccion=        document.getElementById("seccion").value;
    if ( seccion.length < 2 ) {
        alert ("Por favor, introduzca una sección correcta");
        return;
    }
    let dc=             document.getElementById("D.C.").value;
    if ( dc.length < 1 ) {
        alert ("Por favor, introduzca un D.C. correcto");
        return;
    }
    let telefono =      document.getElementById("telefono").value.trim();
    if ( !esTelefonoValido(telefono) ) {
        alert ("El formato del número de teléfono no es correcto. Introduzca 9 cifras");
        return;
    }
    let email =         document.getElementById("email").value;
    if ( !esEmailValido(email) ) {
        alert ("El formato del email no es correcto");
        return;
    }
    let direccion =     document.getElementById("direccion").value;
    if ( direccion.length < 5 ) {
        alert ("Por favor, introduzca una dirección correcta");
        return;
    }
    let codigoPostal =  document.getElementById("codigo-postal").value.trim();
    if ( codigoPostal.length != 5 ) {
        alert ("Por favor, introduzca un código postal correcto");
        return;
    }
    let localidad =     document.getElementById("localidad").value;
    if ( localidad.length < 3 ) {
        alert ("Por favor, introduzca una localidad correcta");
        return;
    }
    let provincia =     document.getElementById("provincia").value;
    if ( provincia.length < 3 ) {
        alert ("Por favor, introduzca una provincia correcta");
        return;
    }
    let CIF =           document.getElementById("CIF").value.trim();
    if ( CIF.length < 5 ) {
        alert ("Por favor, introduzca un CIF correcto");
        return;
    }
    let IVA =           document.getElementById("IVA").value;
    if ( IVA.length < 1 ) {
        alert ("Por favor, introduzca un IVA correcto");
        return;
    }

    //formulario JS
    let fd = new FormData();
    fd.append('autoescuela_id', autoescuela_id);
    fd.append('nombre', nombre);
    fd.append('razon-social', razonSocial);
    fd.append('numero-centro', numeroCentro);
    fd.append('seccion', seccion);
    fd.append('DC', dc);
    fd.append('telefono', telefono);
    fd.append('email', email);
    fd.append('direccion', direccion);
    fd.append('codigo-postal', codigoPostal);
    fd.append('localidad', localidad);
    fd.append('provincia', provincia);
    fd.append('CIF', CIF);
    fd.append('IVA', IVA);

    // Llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/guardarAutoescuela.php");
    xhr.onreadystatechange = function() {
        
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;
            var obj = JSON.parse(string);
            if (obj["id"] == 0) {
				alert ("Datos de autoescuela guardados");
                window.location.href = "configuracion.php";
			} else {
				alert (obj["texto"]);
			}
        }
    }
    xhr.send(fd);
}

//funciones de vehículos
function nuevoVehiculo() {

    //recuperamos elementos a actualizar
    let tipo =      document.getElementById("tipo-vehiculo").value;
    let marca =     document.getElementById("marca").value;
    let matricula = document.getElementById("matricula").value;


    //formulario JS
    let fd = new FormData();
    fd.append('tipo', tipo);
    fd.append('marca', marca);
    fd.append('matricula', matricula);


    // Llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/añadirVehiculo.php");
    xhr.onreadystatechange = function() {
        
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;
            var obj = JSON.parse(string);
            if (obj["id"] == 0) {
				alert ("Vehiculo guardado");
                window.location.href = "configuracion.php";
			} else {
				alert (obj["texto"]);
			}
        }
    }
    xhr.send(fd);
}

function eliminarVehiculo() {
    //recuperamos elementos a actualizar
    let id = document.getElementById("idVehiculo").value;


    //formulario JS
    let fd = new FormData();
    fd.append('id', id);


    // Llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/eliminarVehiculo.php");
    xhr.onreadystatechange = function() {
        
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;
            var obj = JSON.parse(string);
            if (obj["id"] == 0) {
                alert ("Vehiculo eliminado");
                window.location.href = "configuracion.php";
            } else {
                alert (obj["texto"]);
            }
        }
    }
    xhr.send(fd);
}

//funciones de tasas
function nuevaTasa() {

    //recuperamos elementos a actualizar
    let tipo =      document.getElementById("tipo_tasa").value;
    let precio =     document.getElementById("precio").value;

    //formulario JS
    let fd = new FormData();
    fd.append('tipo', tipo);
    fd.append('precio', precio);


    // Llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/añadirTasa.php");
    xhr.onreadystatechange = function() {
        
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;
            var obj = JSON.parse(string);
            if (obj["id"] == 0) {
                alert ("Tasa creada");
                window.location.href = "configuracion.php";
            } else {
                alert (obj["texto"]);
            }
        }
    }
    xhr.send(fd);
}

function eliminarTasa() {
    //recuperamos elementos a actualizar
    let id = document.getElementById("idTasa").value;


    //formulario JS
    let fd = new FormData();
    fd.append('id', id);


    // Llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/eliminarTasa.php");
    xhr.onreadystatechange = function() {
        
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;
            var obj = JSON.parse(string);
            if (obj["id"] == 0) {
                alert ("Tasa eliminada");
                window.location.href = "configuracion.php";
            } else {
                alert (obj["texto"]);
            }
        }
    }
    xhr.send(fd);
}

//funciones de examenes

function llamarAPInuevaRelacionExamen() {

    //recuperamos información

    let fechaPresentacion = document.getElementById("fecha-presentacion").value;
    let fechaExamen = document.getElementById("fecha-examen").value;
    const profesor = document.getElementById("profesor").value;
    if ( profesor.length < 1 ) {
        alert ("Por favor, introduzca un profesor");
        return;
    }
    const tipoPrueba = document.getElementById("tipo-prueba").value;

    if ( fechaPresentacion == "" || fechaExamen == "" ) {
        alert('Por favor, introduzca una fecha de presentación y de examen.');
        return;
    };

    //formulario JS
    let fd = new FormData();
    fd.append( 'fechaPresentacion', fechaPresentacion );
    fd.append( 'fechaExamen', fechaExamen );
    fd.append( 'profesor', profesor );
    fd.append( 'tipoPrueba', tipoPrueba );

    // Llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/nuevaRelacionExamen.php");
    xhr.onreadystatechange = function() {
            
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;		
            var obj = JSON.parse(string);
            if (obj["id"] == 0) {  
                alert ("Relación de examen creado");
                window.location.href = "examen.php?relacion_id="+obj["relacion_id"];
            } else {
                alert (obj["texto"]);
            }
        }
    }
    xhr.send(fd);
};

function llamarAPIhistoricoExamenes() {

    //borramos búsquedas anteriores
    $('#cuerpo_tabla').empty();

    //recuperamos las fechas para mostrar
    let inicio = document.getElementById("fecha-inicial-examen").value;
    let final = document.getElementById("fecha-final-examen").value;

    if ( inicio == "" || final == "" ) {
        alert("Por favor, introduzca una fecha de inicio y final para la búsqueda");
        return;
    }

    //formulario JS
    let fd = new FormData();

    fd.append('inicio', inicio);
    fd.append('final', final);

    //llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/historicoExamenes.php");
    xhr.onreadystatechange = function () {

        //el status 200 y 4 significa q se ha ejecutado
         if (xhr.readyState == 4 && xhr.status == 200) {	
             // recupero la respuesta del API
             var string = xhr.response;		
             var obj = JSON.parse(string);
             for ( let ex of obj )
                $('#cuerpo_tabla').append('<tr><td>'+ex['fecha_examen']+'</td> \
                                           <td>'+ex['profesor']+'</td> \
                                           <td>'+ex['tipo_prueba']+'</td> \
                                           <td><a href="../paginas/examen.php?relacion_id='+ex['relacion_id']+'">Seleccionar</a></td></tr>');
        }
    }
    xhr.send(fd);
}

function eliminarExamen() {
    //recuperamos elementos a actualizar
    let id = document.getElementById("idExamen").value;
    let relacion_id = document.getElementById("relacion_id-borrar").value;


    //formulario JS
    let fd = new FormData();
    fd.append('id', id);


    // Llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/eliminarExamen.php");
    xhr.onreadystatechange = function() {
        
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;
            var obj = JSON.parse(string);
            if (obj["id"] == 0) {
                alert ("Eliminado");
                window.location.href = "examen.php?relacion_id="+relacion_id;
            } else {
                alert (obj["texto"]);
            }
        }
    }
    xhr.send(fd);
}



// funciones para inscribir alumnos a examenes

function llamarAPIinscribirAlumno( curso_id ) {

    const relacion_id = document.getElementById('relacion_id').value;
    const profesor_id = document.getElementById('profesor_id').value;
    const fecha = document.getElementById('fecha_presentacion').value;
    const tipo = document.getElementById('tipo_prueba').value;

    //formulario JS
    let fd = new FormData();
    fd.append('curso_id', curso_id);
    fd.append('relacion_id', relacion_id);
    fd.append('profesor_id', profesor_id);


    // Llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/nuevoAlumnoExamen.php");
    xhr.onreadystatechange = function() {
        
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;
            var obj = JSON.parse(string);
            if (obj["id"] == 0) {
                alert ("Alumno inscrito");
                window.location.href = "examen.php?relacion_id="+relacion_id;
            } else {
                alert (obj["texto"]);
            }
        }
    }
    xhr.send(fd);
}

function llamarAPIbuscarAlumnoInscribir() {

    //borramos búsquedas anteriores
    $('#cuerpo-tabla-inscribir').empty();

    //recuperamos elemento a buscar
    let nom = document.getElementById("nombre-alumno-inscribir").value;
    if ( nom.length < 2  ) {
        alert ( "Introduzca al menos 2 letras o números" );
        return;
    };

    //formulario JS
    let fd = new FormData();
    fd.append('nombre', nom);

    // Llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/buscarAlumno.php");
    xhr.onreadystatechange = function() {
            
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;		
            var res = JSON.parse(string);
            let obj= res['search'];

            if (res["id"] == 0) {
                //ordeno empezando por el último y creo la tabla
                let listado = obj.reverse();
                for ( let alumno of listado) {
                    let cur = alumno['cursos'];
                    for (let c of cur ){
                        if ( c['finalizado'] !== "1" ) {
                            let nombreAlumno = alumno['nombre']+' '+alumno['apellido1']+' '+alumno['apellido2'];
                            let curso = 'Nº '+c['numero_curso']+' - Permiso '+c['tipo'];
                            $('#cuerpo-tabla-inscribir').append('<tr><td><a href="#" onclick="llamarAPIinscribirAlumno('+c["curso_id"]+')">\
                            '+nombreAlumno+'</a></td><td id="curso'+alumno["alumno_id"]+'">'+curso+'</td></tr>');
                        };
                    };
                };
            } else {
            alert (obj["texto"]);
            };
        };
    };
    xhr.send(fd);
    return Promise.resolve();
};

function llamarAPIintroducirVehiculo() {
    const vehiculo_id = document.querySelector('input[name="vehiculo"]:checked').value;
    const examen_id = document.getElementById('idExamenVehiculo').value;
    const relacion_id = document.getElementById("relacion_id_vehiculo").value;

    //formulario JS
    let fd = new FormData();
    fd.append('vehiculo_id', vehiculo_id);
    fd.append('examen_id', examen_id);  

    // Llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/introducirVehiculoExamen.php");
    xhr.onreadystatechange = function() {
        
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;
            var obj = JSON.parse(string);
            if (obj["id"] == 0) {
                window.location.href = "examen.php?relacion_id="+relacion_id;
            } else {
                alert (obj["texto"]);
            }
        }
    }
    xhr.send(fd);
}

function llamarAPIestadoExamen() {
    const estado = document.querySelector('input[name="estado"]:checked').value;
    const examen_id = document.getElementById('idExamenEstado').value;
    const relacion_id = document.getElementById("relacion_id_estado").value;

    //formulario JS
    let fd = new FormData();
    fd.append('estado', estado);
    fd.append('examen_id', examen_id);  

    // Llamada HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../scripts/cambiarEstadoExamen.php");
    xhr.onreadystatechange = function() {
        
        //el status 200 y 4 significa q se ha ejecutado
        if (xhr.readyState == 4 && xhr.status == 200) {	
            // recupero la respuesta del API
            var string = xhr.response;
            var obj = JSON.parse(string);
            if (obj["id"] == 0) {
                window.location.href = "examen.php?relacion_id="+relacion_id;
            } else {
                alert (obj["texto"]);
            }
        }
    }
    xhr.send(fd);
}