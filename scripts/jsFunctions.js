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
            for ( let profesor of obj) {
                $('#cuerpo_tabla').append('<tr><td><a href="profesor.php?profesor_id='+profesor['profesor_id']+'"> \
                '+profesor['nombre']+' '+profesor['apellido1']+' '+profesor['apellido2']+'</a></td>')
            }
        }
    }
    xhr.send(fd);
}

function llamarAPIguardarProfesor() {

    //recuperamos elementos a actualizar
    let profesor_id =           document.getElementById("profesor_id").value;
    let nombre =                document.getElementById("nombre").value;
    let apellido1 =             document.getElementById("apellido1").value;
    let apellido2=              document.getElementById("apellido2").value;
    let DNI=                    document.getElementById("DNI").value;
    let caducidad_dni =         document.getElementById("caducidad_dni").value;
    let telefono =              document.getElementById("telefono").value;
    let email =                 document.getElementById("email").value;
    let direccion =             document.getElementById("direccion").value;
    let codigo_postal =         document.getElementById("codigo_postal").value;
    let localidad =             document.getElementById("localidad").value;
    let provincia =             document.getElementById("provincia").value;
    let fecha_nacimiento =      document.getElementById("fecha_nacimiento").value;
    let fecha_ingreso =         document.getElementById("fecha_ingreso").value;
    let numero_ss =             document.getElementById("numero_ss").value;
    let contraseña =            document.getElementById("contraseña").value;

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
                window.location.href = "profesores.php";
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
            var obj = JSON.parse(string);
            console.log(obj);
            for ( let alumno of obj) {
                $('#cuerpo_tabla').append('<tr><td><a href="alumno.php?alumno_id='+alumno['alumno_id']+'"> \
                '+alumno['nombre']+' '+alumno['apellido1']+' '+alumno['apellido2']+'</a></td>')
            }
        }
    }
    xhr.send(fd);
}

function llamarAPIguardarAlumno() {

    //recuperamos elementos a actualizar
    let alumno_id =             document.getElementById("alumno_id").value;
    let nombre =                document.getElementById("nombre").value;
    let apellido1 =             document.getElementById("apellido1").value;
    let apellido2=              document.getElementById("apellido2").value;
    let dni=                    document.getElementById("dni").value;
    let caducidad_dni =         document.getElementById("caducidad_dni").value;
    let telefono =              document.getElementById("telefono").value;
    let email =                 document.getElementById("email").value;
    let direccion =             document.getElementById("direccion").value;
    let codigo_postal =         document.getElementById("codigo_postal").value;
    let localidad =             document.getElementById("localidad").value;
    let provincia =             document.getElementById("provincia").value;
    let nacionalidad =          document.getElementById("nacionalidad").value;
    let fecha_nacimiento =      document.getElementById("fecha_nacimiento").value;
    let fecha_ingreso =         document.getElementById("fecha_ingreso").value;
    let estudios =              document.getElementById("estudios").value;
    let contraseña =            document.getElementById("contraseña").value;

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
				alert ("Alumno guardado");
                window.location.href = "alumnos.php";
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
    let nom = document.getElementById("prof-alumn-find").value;

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
            var obj = JSON.parse(string);
            console.log(obj);
            for ( let alumno of obj) {
                $('#cuerpo_tabla').append('<tr><td><a href="alumno.php?alumno_id='+alumno['alumno_id']+'"> \
                '+alumno['nombre']+' '+alumno['apellido1']+' '+alumno['apellido2']+'</a></td>')
            }
        }
    }
    xhr.send(fd);
}

function llamarAPIguardarCurso() {

    //recuperamos elementos a actualizar
    let curso_id =                  document.getElementById("curso_id").value;
    let alumno_id =                 document.getElementById("alumno_id").value;
    let profesor_id =               document.getElementById("profesor_id").value;
    let permiso_id =                document.getElementById("permiso_id").value;
    let fecha_inicio =              document.getElementById("inicio").value;
    let fecha_examen_teorico =      document.getElementById("teorico").value;
    let fecha_examen_destreza =     document.getElementById("destreza").value;
    let fecha_examen_circulacion =  document.getElementById("circulacion").value;
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
    fd.append('profesor_id', profesor_id);
    fd.append('permiso_id', permiso_id);
    fd.append('inicio', fecha_inicio);
    fd.append('teorico', fecha_examen_teorico);
    fd.append('destreza', fecha_examen_destreza);
    fd.append('circulacion', fecha_examen_circulacion);
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
                window.location.href = "cursos.php?curso_id="+curso_id;
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
    let tipo_curso = document.getElementById("inputCurso").value;

    //hacemos el formulario JS
    let fd = new FormData();
    fd.append('curso', tipo_curso);
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
             for ( let profesor of obj) {
                 $('#profesores').append('<tr><td><a href="../scripts/cambiarProfeCurso.php?curso_id='+curso_id+'&profesor_id='+profesor['profesor_id']+'"> \
                 '+profesor['nombre']+' '+profesor['apellido1']+' '+profesor['apellido2']+'</a></td></tr>')
             }
         }
     }
     xhr.send(fd);
}

function llamarAPIhistoricoCursos() {

    //recuperamos las fechas para mostrar
    let inicio = document.getElementById("fecha-inicial").value;
    let final = document.getElementById("fecha-final").value;

    /*let fecha = new Date();
    let fechaActual = `${fecha.getFullYear()}-${fecha.getMonth() + 1}-${fecha.getDay()}`;

    if ( final = "" ) {
        final = fechaActual;
    }*/

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
                 <td><a href="../paginas/curso.php?curso_id='+curso['curso_id']+'">'+curso['permiso']+'</a> \
                 </td><td>'+curso['fecha_inicio']+'</td><td>'+curso['fecha_finalizacion']+'</td></tr>');
        }
    }
    xhr.send(fd);
}

