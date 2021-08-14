<div class="modal" tabindex="-1" role="dialog" id="modal-sailor-rental-create">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Créer un forfait client</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('sailor-rental-package.add.ajax') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Nom du client <small>Il est préférable de préciser le nom et prénom du client pour éviter des confusions</small></label>
                        <input value=""
                               type="search"
                               class="autocomplete-sailor-name form-control"
                               name="name" placeholder="Nom du client"
                               autocomplete="off"
                        >
                        <input type="hidden" name="sailor_id" value="" class="input_sailor_id"/>
                    </div>
                    <div class="form-group">
                        <label for="select-rental-package">Forfait</label>
                        <select id="rental-package" class="form-control" name="rental_package_id">
                            @foreach($rentalPackages as $rentalPackage)
                                <option value="{{ $rentalPackage->id }}">{{ $rentalPackage->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="hours">Nombre d'heures</label>
                        <select class="form-control {{ $errors->has('hours') ? 'is-invalid' : '' }}" name="hours">
                            <option value="3">3 heures</option>
                            <option value="5">5 heures</option>
                            <option value="10">10 heures</option>
                        </select>
                    </div>
                    <div class="alert-error alert alert-danger" style="display: none;">

                    </div>
                </div>
                <div class="card-footer">
                    <input type="submit" class="pull-right btn btn-primary" value="Créer le forfait client"/>
                </div>
            </form>
        </div>
    </div>
</div>
