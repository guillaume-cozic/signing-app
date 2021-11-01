@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header clearfix">Membres de l'équipe</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        @foreach($team->users AS $user)
                            <tr>
                                <td>{{ucfirst($user->firstname).' '.ucfirst($user->surname)}}</td>
                                <td>
                                    @if(auth()->user()->isOwnerOfTeam($team))
                                        @if(auth()->user()->getKey() !== $user->getKey())
                                            <form style="display: inline-block;"
                                                  action="{{route('teams.members.destroy', [$team, $user])}}"
                                                  method="post">
                                                {!! csrf_field() !!}
                                                <input type="hidden" name="_method" value="DELETE"/>
                                                <button class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i>
                                                    Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header clearfix">Inviter un collaborateur</div>
                        <div class="card-body">
                            <form class="form-horizontal" method="post" action="{{route('teams.members.invite', $team)}}">
                                {!! csrf_field() !!}
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">Adresse email</label>
                                    <div class="col-md-6">
                                        <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="INSTRUCTOR_HELP" name="roles[]" id="INSTRUCTOR_HELP"/>
                                        <label class="form-check-label" for="INSTRUCTOR_HELP">Aide moniteur</label>
                                        <small>(Peut seulement démarrer ou terminer une sortie en mer)</small>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="INSTRUCTOR" name="roles[]" id="INSTRUCTOR"/>
                                        <label class="form-check-label" for="INSTRUCTOR">Moniteur</label>
                                        <small>(Peut démarrer, terminer ou supprimer une sortie en mer et gérer les forfaits clients)</small>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="RTQ" name="roles[]" id="RTQ"/>
                                        <label class="form-check-label" for="RTQ">RTQ</label>
                                        <small>(A accès à tous sauf la facturation)</small>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="BUYER" name="roles[]" id="BUYER"/>
                                        <label class="form-check-label" for="BUYER">Facturation</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-btn fa-envelope-o"></i>Inviter
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card card-default">
                        <div class="card-header clearfix">Invitation en attente</div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>E-Mail</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                @foreach($team->invites AS $invite)
                                    <tr>
                                        <td>{{$invite->email}}</td>
                                        <td>
                                            <a href="{{route('teams.members.resend_invite', $invite)}}"
                                               class="btn btn-sm btn-default">
                                                <i class="fa fa-envelope-o"></i> Envoyer à nouveau
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
