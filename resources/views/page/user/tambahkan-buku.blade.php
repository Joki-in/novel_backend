@extends('layout.app')

@section('title', 'Tambahkan Buku')

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Data Buku</h4>

                            <div class="align-right text-right">

                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">
                                    Tambah Buku
                                </button>
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
                                            <th class="text-center">No</th>
                                            <th class="text-center">Judul</th>
                                            <th class="text-center">Sinopsis</th>
                                            <th class="text-center">Genre</th>
                                            <th class="text-center">Adult Content</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($buku as $index => $item)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td class="text-center">{{ $item->judul }}</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#sinopsisModal{{ $item->id }}">
                                                        View Sinopsis
                                                    </button>
                                                </td>
                                                <td class="text-center">{{ $item->genre }}</td>
                                                <td class="text-center">
                                                    @if ($item['18+'] == 0)
                                                        Non Adult Content
                                                    @else
                                                        Adult Content
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($item->status == 'belum diterima')
                                                        <span class="badge badge-warning">{{ $item->status }}</span>
                                                    @elseif($item->status == 'diterima')
                                                        <span class="badge badge-success">{{ $item->status }}</span>
                                                    @elseif($item->status == 'ditolak')
                                                        <span class="badge badge-danger" data-toggle="modal"
                                                            data-target="#alasanModal-{{ $item->id }}">
                                                            {{ $item->status }}<br>Klik untuk melihat alasan
                                                        </span>
                                                    @else
                                                        <span class="badge badge-secondary">{{ $item->status }}</span>
                                                    @endif
                                                </td>
                                                <td class="d-flex justify-content-between align-items-center">
                                                    @if ($item->status == 'belum diterima' || $item->status == 'ditolak')
                                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                                            data-target="#editModal{{ $item->id }}">
                                                            Edit
                                                        </button>
                                                    @else
                                                        <a href="{{ route('tambah-isi.index', ['id_buku' => Crypt::encrypt($item->id)]) }}"
                                                            class="btn btn-primary">
                                                            Chapter
                                                        </a>
                                                    @endif
                                                    <form id="deleteForm{{ $item->id }}"
                                                        action="{{ route('buku.destroy', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger"
                                                            onclick="confirmDelete({{ $item->id }})">Hapus</button>
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
        <!-- Tambah Pengguna Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">Tambah Buku</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="judul" class="form-label">Judul Buku</label>
                                <input type="text" class="form-control" id="judul" name="judul" required>
                            </div>
                            <div class="mb-3">
                                <label for="sinopsis" class="form-label">Sinopsis</label>
                                <textarea class="form-control" id="sinopsis" name="sinopsis" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="genre" class="form-label">Genre</label>
                                <select class="form-control" id="genre" name="genre" required>
                                    <option value="">Pilih Genre</option>
                                    <!-- Tambahkan opsi genre di sini -->
                                    <option value="Fiksi">Fiksi</option>
                                    <option value="Drama">Drama</option>
                                    <option value="Horor">Horor</option>
                                    <option value="Komedi">Komedi</option>
                                    <option value="Aksi">Aksi</option>
                                    <option value="Romantis">Romantis</option>
                                    <option value="Misteri">Misteri</option>
                                    <option value="Kriminal">Kriminal</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="cover" class="form-label">Cover Buku</label>
                                <input type="file" class="form-control" id="cover" name="cover"
                                    accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label for="adult_content" class="form-label">Konten Dewasa (18+)</label>
                                <select class="form-control" id="adult_content" name="adult_content" required>
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @foreach ($buku as $item)
            {{-- modal edit --}}
            <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1"
                aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit Buku</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('buku.update', $item->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="judul{{ $item->id }}" class="form-label">Judul Buku</label>
                                    <input type="text" class="form-control" id="judul{{ $item->id }}"
                                        name="judul" value="{{ $item->judul }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="sinopsis{{ $item->id }}" class="form-label">Sinopsis</label>
                                    <textarea class="form-control" id="sinopsis{{ $item->id }}" name="sinopsis" required>{{ $item->sinopsis }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="genre{{ $item->id }}" class="form-label">Genre</label>
                                    <select class="form-control" id="genre{{ $item->id }}" name="genre" required>
                                        <option value="">Pilih Genre</option>
                                        <option value="Fiksi" {{ $item->genre == 'Fiksi' ? 'selected' : '' }}>Fiksi
                                        </option>
                                        <option value="Drama" {{ $item->genre == 'Drama' ? 'selected' : '' }}>Drama
                                        </option>
                                        <option value="Horor" {{ $item->genre == 'Horor' ? 'selected' : '' }}>Horor
                                        </option>
                                        <option value="Komedi" {{ $item->genre == 'Komedi' ? 'selected' : '' }}>Komedi
                                        </option>
                                        <option value="Aksi" {{ $item->genre == 'Aksi' ? 'selected' : '' }}>Aksi
                                        </option>
                                        <option value="Romantis" {{ $item->genre == 'Romantis' ? 'selected' : '' }}>
                                            Romantis</option>
                                        <option value="Misteri" {{ $item->genre == 'Misteri' ? 'selected' : '' }}>Misteri
                                        </option>
                                        <option value="Kriminal" {{ $item->genre == 'Kriminal' ? 'selected' : '' }}>
                                            Kriminal</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="cover{{ $item->id }}" class="form-label">Cover Buku</label>
                                    <input type="file" class="form-control" id="cover{{ $item->id }}"
                                        name="cover" accept="image/*">
                                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti cover.</small>
                                </div>
                                <div class="mb-3">
                                    <label for="adult_content{{ $item->id }}" class="form-label">Konten Dewasa
                                        (18+)
                                    </label>
                                    <select class="form-control" id="adult_content{{ $item->id }}"
                                        name="adult_content" required>
                                        <option value="0" {{ $item->adult_content == 0 ? 'selected' : '' }}>Tidak
                                        </option>
                                        <option value="1" {{ $item->adult_content == 1 ? 'selected' : '' }}>Ya
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="{{ asset('/coverbuku/' . $item->cover) }}" class="img-fluid"
                                        alt="Cover Buku">
                                </div>
                                <div class="col-md-8">
                                    <h5>{{ $item->judul }}</h5>
                                    <p>{{ $item->sinopsis }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal alasan -->
            <div class="modal fade" id="alasanModal-{{ $item->id }}" tabindex="-1"
                aria-labelledby="alasanModalLabel-{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="alasanModalLabel-{{ $item->id }}">Alasan Ditolak</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>{{ $item->alasan }}</p>
                            <br>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <p><strong>Silahkan lakukan edit dan sesuaikan dengan alasan</strong></p>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
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
        <script>
            $(document).ready(function() {
                $('#createModal').on('hidden.bs.modal', function() {
                    $(this).find('form')[0].reset();
                });
            });
        </script>
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
        <script>
            function confirmDelete(bukuId) {
                Swal.fire({
                    title: 'Anda yakin?',
                    text: 'Data buku ini akan dihapus secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm' + bukuId).submit();
                    }
                });
            }
        </script>

    @endsection
