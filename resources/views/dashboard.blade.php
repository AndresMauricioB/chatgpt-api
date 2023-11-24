<x-app-layout >
  

   <div>
    <div class="container-dash">
        <div>
            <p class="msg-dash">¡Descubre un mundo de conocimiento personalizado con nuestra suscripción de chat exclusiva de la Universidad de Cladas! Con acceso directo a expertos en cada campo, obtendrás respuestas instantáneas a tus preguntas, tutorías personalizadas y una experiencia educativa única. ¡Invierte en tu aprendizaje y desbloquea el potencial ilimitado de tu mente con nuestra suscripción de chat personalizado hoy mismo!</p>
        </div>

        <div>
            <a href="/paypal/pay" class="btn-paypal">
                <i class="fa-brands fa-cc-paypal"></i>Paypal $3.20
            </a> 
        </div>

        @if(session('status'))
        <div class="mensaje-pago">
            {{ session('status') }}
        </div>
        @endif


        <div>
            <div>ID</div>
            <div>Valor</div>
            <div>Fecha Pago</div>
            <div>Fecha de vencimiento</div>
            <div>Estado</div>

        </div>
        <div id="result-pagos">

        </div>
        
    </div>
   </div>
   <script>
      // Listar los pagos
      $(document).ready(function() {
        indexPagos();

     });
        function indexPagos() {
                $.ajax({
                    url: "http://127.0.0.1:8000/paypal/pagos",
                    method: "GET",
                    dataType: "json", // Especifica que esperamos una respuesta JSON
                    success: function(response) {
                        if (response.success) {
                            console.log(response);
                            // Itera a través de los chats y haz algo con ellos
                            response.pagos.forEach(function(pago) {
                                // O mostrarlos en tu página HTML
                                $("#result-pagos").append(
                                '<div>' + pago.id + '</div>' +
                                '<div>' + pago.amount + '</div>' +
                                '<div>' + pago.created_at + '</div>' +
                                '<div>' + pago.expiration_date + '</div>');
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
    
</x-app-layout>
