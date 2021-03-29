<div class="row row-boat-trip">
    <div class="col-7">
        <div class="form-group">
            <select class="form-control" name="boats[{{$count}}][id]">
                @foreach($fleets as $fleet)
                    <option value="{{ $fleet->id }}">{{ $fleet->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-4">
        <div class="form-group">
            <input class="form-control" name="boats[{{$count}}][number]" value="1" type="number" step="1" min="1"/>
        </div>
    </div>
    <div class="col-1">
        <div class="form-group">
            <i class="fa fa-trash text-red delete-boat" style="vertical-align: bottom;"></i>
        </div>
    </div>
</div>
