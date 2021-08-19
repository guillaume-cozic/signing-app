@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
            <div class="card card-primary">
                <div class="card-header">
                    Liste des erreurs d'import
                </div>
                <div class="card-body">
                    @if(!empty($result['errors']))
                        <div class="row">
                            <div class="col-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary"><i class="far fa-copy"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total</span>
                                        <span class="info-box-number">{{ $result['err'] + $result['processed'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fa fa-check"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Importés</span>
                                        <span class="info-box-number">{{ $result['processed'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-danger"><i class="fa fa-times"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Erreurs</span>
                                        <span class="info-box-number">{{ $result['err'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($result['errors']))
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Identité du client</th>
                                    <th>Nombre d'heures</th>
                                    <th>Date de fin de validité</th>
                                </tr>
                            </thead>
                            @foreach($result['errors'] as $errorImport)
                                <tr>
                                    <td>{{ $errorImport[0] }}</td>
                                    <td>{{ $errorImport[1] }}</td>
                                    <td>{{ $errorImport[2] }}</td>
                                </tr>
                            @endforeach
                        </table>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <div class="card card-primary">
                <div class="card-header">
                    Importer un fichier de forfaits clients
                </div>
                <div class="card-body">
                    <div class="card card-outline card-info">
                        <div class="card-body">
                            Si vous souhaitez importer massivement des forfaits clients,
                            <a target="_blank" href="{{ route('rental-package.add') }}">créer vos différents types de forfaits</a>,
                            télécharger ci dessous le document excel et remplissez le.
                        </div>
                        <div class="card-footer">
                            <div class="form-group pull-right">
                                <a class="btn btn-info" href="{{ route('sailor-rental-package.download-import') }}">
                                    <i class="fa fa-cloud-download-alt"></i>
                                    Télécharger le fichier modèle d'import
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="callout callout-warning">
                            Attention si vous importez plusieurs fois les mêmes forfaits, le nombre d'heures disponibles sera impacté !
                        </div>
                    </div>
                    <form action="{{ route('sailor-rental-package.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputFile">Importer le fichier</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input name="file_import" type="file"
                                           class="form-control custom-file-input {{ $errors->has('file_import') ? 'is-invalid' : '' }}"
                                           id="exampleInputFile">
                                    <label class="custom-file-label" for="exampleInputFile">Choisir le fichier</label>
                                </div>
                            </div>
                            @if ($errors->has('file_import'))
                                <span style="display: block;" class="error invalid-feedback">
                                    <strong>{{ $errors->first('file_import') }}</strong>
                                </span>
                            @endif
                        </div>
                        <input type="submit" class="pull-right btn btn-primary" value="Importer les forfaits clients"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
