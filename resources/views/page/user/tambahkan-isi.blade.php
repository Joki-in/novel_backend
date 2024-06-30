@extends('layout.app')

@section('title', 'Tambahkan Buku')

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Data Isi</h4>

                            <div class="align-right text-right">

                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">
                                    Tambah Isi
                                </button>
                            </div>
                            <br>
                            <div class="search-element">
                                <input id="searchInput" class="form-control" type="search" placeholder="Search"
                                    aria-label="Search">
                            </div>
                            <br>
                            <br>
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <!-- Tampilkan pesan success jika ada -->
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <br>

                            <div class="table-responsive">
                                <table id="example" class="table table-bordered zero-configuration">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Chapter</th>
                                            <th class="text-center">Isi</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($isi as $index => $item)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td class="text-center">{{ $item->chapter }}</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#isiModal{{ $item->id }}">
                                                        View Isi
                                                    </button>
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
                                                <td class="d-flex justify-content-center align-items-center">
                                                    <button type="button" class="btn btn-primary m-2" data-toggle="modal"
                                                        data-target="#editModal{{ $item->id }}">
                                                        Edit
                                                    </button>
                                                    <form id="deleteForm{{ $item->id }}"
                                                        action="{{ route('tambah-isi.destroy', $item->id) }}"
                                                        method="POST" class="ms-2">
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


        @foreach ($isi as $item)
            <!-- Modal for Add Isi -->
            <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createModalLabel">Tambah Isi</h5>
                            <!-- Tombol close untuk modal -->
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Tampilkan pesan error jika ada -->
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <!-- Tampilkan pesan success jika ada -->
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form action="{{ route('isi.store') }}" method="POST" id="createForm">
                                @csrf
                                <div class="form-group">
                                    <label for="buku_id">Judul Buku</label>
                                    <input value="{{ $item->buku->judul }}" type="text" id="judul_buku"
                                        name="judul_buku" class="form-control" readonly>
                                    <input value="{{ $item->buku->id }}" type="hidden" id="buku_id" name="buku_id">
                                </div>
                                <div class="form-group">
                                    <label for="chapter">Judul Chapter</label>
                                    <input type="text" id="chapter" name="chapter" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="isi">Isi</label>
                                    <div class="toolbar">
                                        <button type="button" class="btn btn-light" id="boldButton"
                                            onclick="toggleStyle('bold')"><b>B</b></button>
                                        <button type="button" class="btn btn-light" id="italicButton"
                                            onclick="toggleStyle('italic')"><i>I</i></button>
                                        <button type="button" class="btn btn-light" id="underlineButton"
                                            onclick="toggleStyle('underline')"><u>U</u></button>
                                    </div>
                                    <div id="isi" name="isi" class="note-editable card-block"
                                        contenteditable="true"
                                        style="min-height: 150px; height: 238px; border: 1px solid #ced4da; padding: 10px;">
                                    </div>
                                    <!-- Hidden textarea untuk mengirim data -->
                                    <textarea id="isi_hidden" name="isi" style="display:none;"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal for View Isi -->
            <div class="modal fade" id="isiModal{{ $item->id }}" tabindex="-1"
                aria-labelledby="isiModalLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="isiModalLabel{{ $item->id }}">{{ $item->chapter }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>{{ $item->isi }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
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
                        <div class="modal-footer justify-content-between ">
                            <p><strong>Silahkan lakukan edit dan sesuaikan dengan alasan</strong></p>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Update -->
            <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1"
                aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Update Isi</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('tambah-isi.update', $item->id) }}" method="POST"
                            id="updateForm{{ $item->id }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <!-- Tampilkan pesan error jika ada -->
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                <!-- Tampilkan pesan success jika ada -->
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label for="buku_id" class="form-label">Judul Buku</label>
                                    <input value="{{ $item->buku->judul }}" type="text" class="form-control"
                                        id="judul_buku" name="judul_buku" readonly>
                                    <input value="{{ $item->buku->id }}" type="hidden" id="buku_id" name="buku_id">
                                </div>
                                <div class="mb-3">
                                    <label for="chapter" class="form-label">Judul Chapter</label>
                                    <input type="text" class="form-control" id="chapter" name="chapter"
                                        value="{{ $item->chapter }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="isi" class="form-label">Isi</label>
                                    <div class="toolbar mb-2">
                                        <button type="button" class="btn btn-light" id="boldButton{{ $item->id }}"
                                            onclick="toggleStyle('bold', {{ $item->id }})"><b>B</b></button>
                                        <button type="button" class="btn btn-light"
                                            id="italicButton{{ $item->id }}"
                                            onclick="toggleStyle('italic', {{ $item->id }})"><i>I</i></button>
                                        <button type="button" class="btn btn-light"
                                            id="underlineButton{{ $item->id }}"
                                            onclick="toggleStyle('underline', {{ $item->id }})"><u>U</u></button>
                                    </div>
                                    <div id="isi{{ $item->id }}" name="isi" class="note-editable card-block"
                                        contenteditable="true"
                                        style="min-height: 150px; height: 238px; border: 1px solid #ced4da; padding: 10px;">
                                        {{ $item->isi }}
                                    </div>
                                    <!-- Hidden textarea untuk mengirim data -->
                                    <textarea id="isi_hidden{{ $item->id }}" name="isi" style="display:none;"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        <script>
            function toggleStyle(style, itemId) {
                document.getElementById(`isi${itemId}`).focus();
                document.execCommand(style, false, null);
            }

            document.addEventListener('DOMContentLoaded', function() {
                @foreach ($isi as $item)
                    const form{{ $item->id }} = document.querySelector(`#updateForm{{ $item->id }}`);
                    form{{ $item->id }}.addEventListener('submit', function() {
                        const isiContent = document.getElementById(`isi{{ $item->id }}`).innerHTML;
                        document.getElementById(`isi_hidden{{ $item->id }}`).value = isiContent;
                    });
                @endforeach
            });
        </script>

        <script>
            function toggleStyle(command) {
                document.execCommand(command, false, null);
                updateActiveButtons();
            }

            // Memperbarui status tombol toolbar berdasarkan gaya teks aktif
            function updateActiveButtons() {
                const commands = ['bold', 'italic', 'underline'];
                commands.forEach(command => {
                    const button = document.getElementById(`${command}Button`);
                    if (document.queryCommandState(command)) {
                        button.classList.add('btn-primary');
                        button.classList.remove('btn-light');
                        button.style.border = '2px solid #007bff';
                        button.style.color = '#fff';
                    } else {
                        button.classList.add('btn-light');
                        button.classList.remove('btn-primary');
                        button.style.border = 'none';
                        button.style.color = '#000';
                    }
                });
            }

            // Memperbarui status tombol toolbar saat halaman dimuat dan saat konten diubah
            document.addEventListener('DOMContentLoaded', updateActiveButtons);
            document.getElementById('isi').addEventListener('input', updateActiveButtons);

            // Saat form disubmit, salin konten dari contenteditable div ke hidden input field
            document.getElementById('createForm').addEventListener('submit', function(event) {
                var isiContent = document.getElementById('isi').innerHTML.trim();
                console.log('Isi Content:', isiContent); // Log isi contenteditable div
                if (isiContent === '') {
                    event.preventDefault(); // Hentikan pengiriman form
                    alert('Field Isi tidak boleh kosong.');
                } else {
                    // Salin konten ke textarea tersembunyi
                    document.getElementById('isi_hidden').value = isiContent;
                }
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
            function confirmDelete(isiId) {
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
                        document.getElementById('deleteForm' + isiId).submit();
                    }
                });
            }
        </script>
    @endsection
