<div class="modal" tabindex="-1" role="dialog" id="modal-import-sailor-rental">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Importer des forfaits clients</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        Si vous souhaitez importer massivement des forfaits clients,
                        <a href="{{ route('rental-package.add') }}">créer vos différents types de forfaits</a>,
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
                                <input name="file-import" type="file" class="custom-file-input" id="exampleInputFile">
                                <label class="custom-file-label" for="exampleInputFile">Choisir le fichier</label>
                            </div>
                        </div>
                    </div>
                    <input type="submit" class="pull-right btn btn-primary" value="Importer les forfaits clients"/>
                </form>
            </div>
        </div>
    </div>
</div>
