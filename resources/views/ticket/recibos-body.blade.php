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