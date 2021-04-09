<div id="availability"></div>
@section('adminlte_js')
    @parent
    <script type="text/javascript">
        var routeAvailability = '{{ route('dashboard.availability') }}';
        loadAvailability();
        function loadAvailability() {
            $.ajax({
                url : routeAvailability,
                success: function (data){
                    $('#availability').html(data);
                }
            });
        }
    </script>
@endsection
