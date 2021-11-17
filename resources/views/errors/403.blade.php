@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-danger card-outline">
                <div class="card-body">
                    @if(env('IS_DEMO'))
                        Cette page est désactivée sur le compte de démonstration
                    @else
                        Vous n'avez pas accès à cette page.
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
