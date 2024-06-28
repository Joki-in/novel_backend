@extends('layout.app')

@section('title', 'Terima Buku')

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Terima Buku</h4>

                            <div class="align-right text-right">

                            </div>
                            <br>
                            <div class="search-element">
                                <input id="searchInput" class="form-control" type="search" placeholder="Search"
                                    aria-label="Search">
                            </div>

                            <br>

                            <div class="table-responsive">
                                <table id="example" class="table table-bordered zero-configuration">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Judul</th>
                                            <th class="text-center">Genre</th>
                                            <th class="text-center">18+</th>
                                            <th class='text-center'>Penulis</th>
                                            <th class="text-center">Sinopsis</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($buku as $item)
                                            <tr>
                                                <td>
                                                    {{ $item->judul }}
                                                </td>
                                                <td>
                                                    {{ $item->genre }}
                                                </td>
                                                <td>
                                                    @if ($item['18+'] == 0)
                                                        Non Adult Content
                                                    @else
                                                        Adult Content
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $item->penulis->name }}
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#sinopsisModal{{ $item->id }}">
                                                        View Sinopsis
                                                    </button>
                                                </td>
                                                <td>
                                                    <form id="form-terima-{{ $item->id }}"
                                                        action="{{ route('terima-buku', ['id' => $item->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-icon icon-left btn-success"><i
                                                                class="fas fa-check"></i> Terima</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($buku as $item)
        <!-- Modal Sinopsi -->
        <div class="modal fade" id="sinopsisModal{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="sinopsisModalLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sinopsisModalLabel{{ $item->id }}">Sinopsis</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ $item->sinopsis }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <script>
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('table tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
    @if (session('notification'))
        <script>
            $(document).ready(function() {
                const {
                    title,
                    text,
                    type
                } = @json(session('notification'));
                Swal.fire(title, text, type);
            });
        </script>
    @endif


@endsection
