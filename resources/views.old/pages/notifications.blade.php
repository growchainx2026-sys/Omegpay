@php
    $id = request()->get('id');
    $user = auth()->user();
    $notificacoes = $user->unreadNotifications();
    $quantidade = (clone $notificacoes)->count();

    foreach ($user->unreadNotifications as $notification) {
        $notification->markAsRead();
    }

@endphp

@extends('layouts.app')

@section('title', 'Notificações')

@section('content')
    <div class="header mb-3">
        <h1 class="header-title">
            Notificações
        </h1>
    </div>

    <div class="card card-dash">
        <div class="card-body">
            <table class="table" id="table-notificacoes">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Assunto</th>
                        <th>Mensagem</th>
                        <th>Status</th>
                        <th></th>

                    </tr>
                </thead>
                <tbody>
                    @foreach (auth()->user()->notifications as $notification)
                        <tr>
                            <td>{{ $notification->created_at->format('d/m/Y \à\s H:i:s') ?? '--' }}</td>
                            <td>{{ $notification->data['assunto'] ?? '--' }}</td>
                            <td>{{ $notification->data['mensagem'] ?? '--' }}</td>
                            <td>
                                @if (is_null($notification->read_at))
                                    <span class="badge text-bg-danger text-white">Não Lida</span>
                                @else
                                    <span class="badge text-bg-success text-white">Visualizada</span>
                                @endif
                            </td>
                            <td>

                                <button type="button" class="btn btn-outline-primary btn-sm button-more"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical text info"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a  data-bs-toggle="modal"
                                            data-bs-target="#deleteNotificacao{{ $notification->id }}">
                                            <button class="dropdown-item btn-visualizar">
                                                <i data-lucide="square-x" class="me-2"></i>Excluir
                                            </button>
                                        </a>
                                    </li>
                                </ul>
                            </td>
                        </tr>

                        
                        <div class="modal fade" id="deleteNotificacao{{ $notification->id }}" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteNotificacao{{ $notification->id }}Label"
                            aria-hidden="true">
                            <div class="modal-dialog modal-md modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteNotificacao{{ $notification->id }}Label">Excluir coprodução
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h6 class="text-danger">Você tem certeza que deseja excluir esta notificação?</h6></div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancelar</button>
                                        <form method="POST" action="{{ route('notifications.delete') }}">
                                            @csrf
                                            <input style="display: none;" name="id" value="{{ $notification->id }}">
                                            <button type="submit" class="btn btn-danger text-white">Excluir</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>     
                    </div>

                    @endforeach
                </tbody>
            </table>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var table = $("#table-notificacoes").DataTable({
                    responsive: true,
                    ordering: false,
                    lengthChange: false,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                        search: ''
                    }
                });

                table.on('draw', function() {
                    $('#table-notificacoes tbody tr').each(function() {
                        $(this).find('td').css('border-bottom', 'none');
                    });
                });

                // Garante que o evento draw também seja executado na primeira renderização
                table.draw();
            });
        </script>
    @endsection
