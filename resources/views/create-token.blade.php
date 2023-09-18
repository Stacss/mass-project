@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Создать API-токен</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('store.token') }}">
                            @csrf

                            <div class="form-group">
                                <label for="user_id">ID пользователя</label>
                                <input type="text" id="user_id" name="user_id" class="form-control" required>
                            </div>
                            @if(session('message'))
                                <div class="alert alert-success mt-3">
                                    {{ session('message') }}
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="token_name">Название токена</label>
                                <input type="text" id="token_name" name="name" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Создать</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
