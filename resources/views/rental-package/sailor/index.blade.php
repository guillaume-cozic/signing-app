@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Liste des forfaits clients</h3>
                    <div class="card-tools">
                        @if(isset($rentalPackageId))
                            <a href="{{ route('sailor-rental-package.index') }}" class="btn btn-sm btn-info">
                                Voir tous les forfaits
                            </a>
                        @endif
                        <a href="{{ route('larecipe.show', ['version' => '1.0', 'page' => 'sailor-rental-package']) }}" target="_blank" class="btn btn-tool"
                           data-toggle="tooltip" data-placement="top" title="Consulter la documentation utilisateur">
                            <i class="fas fa-question-circle text-primary"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @include('rental-package.sailor.list-rental-package')
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
            <button class="btn-block mb-3 btn btn-success btn-modal-import-sailor-rental-package">
                <i class="fa fa-copy"></i> Importer des forfaits clients
            </button>
            <div class="row">
                <div class="col-12">
                    @include('rental-package.sailor.add-form')
                </div>
            </div>
        </div>
    </div>
    @include('rental-package.modals.import-form')
@endsection
