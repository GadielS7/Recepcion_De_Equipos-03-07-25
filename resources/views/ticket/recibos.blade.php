@extends('layouts.ticket.app-master')

@section('content')


    <h1 class="titulo">Ticket</h1>
    
    <!-- Input para búsqueda en tiempo real con estilos de Bootstrap -->
    <div class="input-group mb-3" style="max-width: 700px;">
        <!-- Ajusta el ancho máximo según tus necesidades -->
        <input type="text" id="searchInput" class="form-control" placeholder="Buscar recibo..." onkeyup="buscarRecibos()">

    </div>

    <strong><h2>Total De Ordenes Realizadas: {{ $totalRecibos }}</h2></strong><br>
    <table class="tabla">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Fecha de Recibido</th>
                <th>Fecha de Reparado</th>
                <th>Nota De Recepción</th>
                <th>Generar Ticket</th>
                <th>Comentarios</th>
                @auth
                    @if(auth()->user()->isAdmin())
                        <th>Marcar Completado</th>
                    @endif
                @endauth

                <th>
            </tr>
        </thead>
        <tbody id="recibosBody">
            @foreach($recibos as $recibo)
            <tr>
                <td>
                @if(isset($recibo->tipoEquipo[0]->cliente))
                    {{ $recibo->tipoEquipo[0]->cliente->nombre}}
                @else
                    Cliente No Encontrado
                @endif
                </td>
                <td>{{ date('d-m-Y', strtotime($recibo->created_at)) }}</td>
                <td>{{ date('d-m-Y', strtotime($recibo->fechaReparacion)) }}</td>
                <td>
                    <form form action="{{ route('recibos.pdf', ['id' => $recibo->id]) }}" method="GET" target="_blank">
                        @csrf
                        <button type="submit" style="border: none; background-color: transparent; padding: 0;">
                            <img src="{{ url('assets/iconos/file-earmark-arrow-down-fill.svg') }}" width="190%" height="190%" style="display: block;">
                        </button>
                    </form>
                </td>

                <td>
                    <button type="button" onclick="confirmarGenerarTicket({{ $recibo->id }})" style="border: none; background-color: transparent; padding: 0;">
                        <img src="{{ url('assets/iconos/file-earmark-break.svg') }}" width="190%" height="190%" style="display: block;">
                    </button>
                </td>
                <td>
                    <button type="button"  onclick="abrirNotaModal({{ $recibo->id }})" style="border: none; background-color: transparent; padding: 0;">
                        <img src="{{ url('assets/iconos/journals.svg') }}" width="24" height="24" style="display: block;">
                    </button>   
                </td>

                 @auth
                      @if(auth()->user()->isAdmin())
                        <td>
                            <button type="button" 
                                    onclick="abrirCompletadoConfirmar({{ $recibo->id }})" 
                                    class="btn-completar" 
                                    title="Marcar como completado">
                                <svg width="24" height="24" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" 
                                        fill="green"/> <!-- Usa currentColor para mejor control -->
                                </svg>
                            </button>
                        </td>
                        @endif
                 @endauth
            
            </tr>
            @endforeach
        </tbody>
    </table>

    <nav aria-label="...">
            <ul class="pagination">
                {{ $recibos->links() }}
            </ul>
    </nav>

<!-- Modal para mostrar y editar la nota -->
<div class="modal fade" id="notaModal" tabindex="-1" role="dialog" aria-labelledby="notaModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal-width" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h4 class="modal-title font-weight-bold" id="notaModalLabel">Nota del Recibo</h4>
            </div>
            <div class="modal-body">
                <p id="notaContent"></p>
                <textarea id="notaInput" style="display: none;"></textarea>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="cerrarNotaModal()">Cerrar</button>
                
                <button type="button" id="guardarNotaButton" class="btn btn-primary" style="display: none;" onclick="guardarNota()">Guardar</button>
                <button type="button" id="editNotaButton" class="btn btn-success" onclick="habilitarEdicionNota()">Editar</button>
            </div>
        </div>
    </div>
</div>

@include('ticket.generarTicket')
@endsection
