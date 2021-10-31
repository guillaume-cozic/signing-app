@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-default">
                <div class="card-header">Editer {{$team->name}}</div>
                <div class="card-body">
                    <form class="form-horizontal" method="post" action="{{route('teams.update', $team)}}">
                        <input type="hidden" name="_method" value="PUT" />
                        {!! csrf_field() !!}
                        <div class="form-group{{ $errors->has('name') ? ' has-errors' : '' }}">
                            <label class="col-md-4 control-label">Nom</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name', $team->name) }}">
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-save"></i> Sauvegarder
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
