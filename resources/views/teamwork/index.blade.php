@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teams as $team)
                                <tr>
                                    <td>{{$team->name}}</td>
                                    <td>
                                        @if(auth()->user()->isOwnerOfTeam($team))
                                            <span class="label label-success">Owner</span>
                                        @else
                                            <span class="label label-primary">Member</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('teams.members.show', $team)}}" class="btn btn-sm btn-default">
                                            <i class="fa fa-users"></i> Membres de l'équipe
                                        </a>

                                        @if(auth()->user()->isOwnerOfTeam($team))
                                            <a href="{{route('teams.edit', $team)}}" class="btn btn-sm btn-default">
                                                <i class="fa fa-pencil"></i> Editer
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
