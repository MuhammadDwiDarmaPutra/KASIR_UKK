@extends('main')
@section('title', 'User - My Website')
@section('breadcrumb', 'User')
@section('page-title', 'User')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-end align-items-center mb-4">
            <div>
                <a class="btn btn-primary" href="{{ route('users.create') }}">Tambah User</a>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" class="text-center">#</th>
                    <th scope="col" class="text-center">Email</th>
                    <th scope="col" class="text-center">Nama</th>
                    <th scope="col" class="text-center">Role</th>
                    <th scope="col" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $id = 1;
                @endphp
                @foreach ($users as $user)
                    <tr>
                        <th scope="row" class="text-center">{{ $id++ }}</th>
                        <td class="text-center">{{ $user->email }}</td>
                        <td class="text-center">{{ $user->name }}</td>
                        <td class="text-center">{{ $user->role }}</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-light border-0" type="button"
                                    id="dropdownMenuButton{{ $user->id }}" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $user->id }}">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                                            <i class="mdi mdi-pencil-outline me-2"></i> Edit
                                        </a>
                                    </li>
                                
                                    @if ($user->role === 'kasir')
                                        <li>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger" type="submit">
                                                    <i class="mdi mdi-trash-can-outline me-2"></i> Delete
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                </ul>                                
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
