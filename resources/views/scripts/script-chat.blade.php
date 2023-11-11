<script>
    // Listar los Chats
    $(document).ready(function() {
        showMessage(1, null);
        indexChats();

    });

    //Listar chats
    function indexChats() {
        $.ajax({
            url: "http://127.0.0.1:8000/chat/index",
            method: "GET",
            dataType: "json", // Especifica que esperamos una respuesta JSON
            success: function(response) {
                if (response.success) {
                    // Itera a través de los chats y haz algo con ellos
                    response.chats.forEach(function(chat) {
                        // O mostrarlos en tu página HTML
                        $("#result-chat").append('<p class="boton" onclick="showMessage(' + chat.id+ ', this)" >Chat: ' + chat.id + '<i onclick="deleteChat(' + chat.id+')" class="fa-solid fa-trash"></i></p>');

                    });
                } else {
                    console.error("Error en la respuesta");
                }
            },
            error: function(xhr, status, error) {
                console.error(error); // Registrar errores en la consola
            }
        });
    }

    // Crear el Chat
    function createChat() {
        $.ajax({
            url: "http://127.0.0.1:8000/chat/create",
            method: "POST",
            dataType: "json",
            contentType: 'application/json',
            xhrFields: {
                withCredentials: true // Esta opción permite enviar las cookies
            },

            success: function(response) {
                $("#result").html(response);
                $("#result-chat").html(" ");
                indexChats();
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }


    // Eliminar Chat
    function deleteChat(id) {
        $.ajax({
            url: "http://127.0.0.1:8000/chat/"+id+"/message/delete",
            method: "DELETE",
            success: function(response) {
                $("#result-chat").html(" ");
                indexChats();
            },
            error: function(xhr, status, error) {
                console.error(error); // Log any errors to the console
            }
        });
    }



    // Enviar mensaje
    function sendMessage(id) {
        var promptText = $("#promptInput").val();
        $("#result").append('<div class="msg2"><i class="fa-solid fa-user-tie"></i>' +
            promptText + '</div>');
        // Vaciar el campo de entrada
        $("#promptInput").val("");
        $.ajax({
            url: "http://127.0.0.1:8000/chat/"+id+"/message/send",
            method: "POST",
            data: {
                prompt: promptText
            },
            success: function(response) {

                $("#result").append('<div class="msg1"><i class="fa-solid fa-user-tie"></i>' +
                    response.answer + '</div>');

            },
            error: function(xhr, status, error) {
                console.error(error); // Log any errors to the console
            }
        });
    }

    // Mostrar info Chat especifico

    function showMessage(id, boton) {

        document.getElementById('sendMessage').setAttribute('onclick', 'sendMessage(' + id + ')');


        // Remover la clase 'active-btn-chat' de todos los botones
        $('.boton').removeClass('active-btn-chat');

        // Agregar la clase 'active-btn-chat' al botón seleccionado
        $(boton).addClass('active-btn-chat');

        $.ajax({
            url: "http://127.0.0.1:8000/chat/"+id+"/message/show",
            method: "POST",
            dataType: "json", // Especifica que esperamos una respuesta JSON
            success: function(response) {
                if (response.success) {
                    $("#result").html(' ');
                    // Itera a través de los chats y haz algo con ellos
                    response.menssages.forEach(function(menssage) {
                        // O mostrarlos en tu página HTML

                        if(menssage.role == "user"){
                            $("#result").append('<div class="msg1"><i class="fa-solid fa-comment-dots"></i> ' +
                                menssage.content + '</div>');
                        }
                        if(menssage.role == "assistant"){
                            $("#result").append('<div class="msg2">' +
                                menssage.content + '<i class="fa-solid fa-user-tie"></i></div>');
                        }


                    });
                } else {
                    console.error("Error en la respuesta");
                }
            },
            error: function(xhr, status, error) {
                console.error(error); // Registrar errores en la consola
            }
        });
    }
</script>
