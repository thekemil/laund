@extends('layouts.app')

@section('content')
<div class="row">
  <h3 class="page-header">Transaksi Baru</h3>
  {!! Form::open(['route' => 'kasir.transaction.store']) !!}
  <h5 class="page-header">Informasi Order:</h5>

  <div class="row">
    <div class="col-md-4">
      <div class="form-group">
        {!! Form::label('date_order', 'Date Order:') !!}
        {!! Form::text('date_order',null,['id'=>'date-order', 'class'=>'form-control']) !!}
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        {!! Form::label('date_deliver', 'Tanggal Selesai:') !!}
        {!! Form::text('date_deliver',null,['id'=>'date-deliver','class'=>'form-control']) !!}
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        {!! Form::label('time_deliver', 'Waktu Selesai:') !!}
        <div class="input-group bootstrap-timepicker timepicker">
          {!! Form::text('time_deliver',null,['id'=>'time_deliver', 'class'=>'form-control']) !!}<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
        </div>
      </div>
    </div>
  </div>
  <h5 class="page-header">Informasi Pelanggan:</h5>
  <div class="row">
    <div class="col-md-4">
      <div class="form-group">
        {!! Form::label('customer_id', 'Nama Pelanggan:') !!}
        {!! Form::text('customer',null,['id'=>'customer', 'class'=>'form-control','placeholder'=>'Enter name or phone']) !!}
        {!! Form::hidden('customer_id',null,['id'=>'customer_id', 'class'=>'form-control']) !!}
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        {!! Form::label('address', 'Alamat Pelanggan:') !!}
        <input type="text" id="address" class="form-control" readonly="true"></input>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        {!! Form::label('phone', 'Telepon Pelanggan:') !!}
        <input type="text" id="phone" class="form-control" readonly="true"></input>
      </div>
    </div>
  </div>
  <h5 class="page-header">Konfirmasi order:</h5>
  <div class="row">
    <div class="col-md-4">
      {!! Form::label('discount', 'Diskon:') !!}
      {!! Form::text('discount',null,['class'=>'form-control']) !!}
      {!! Form::label('user_id', 'Petugas Penerima:') !!}
      {!! Form::text('user_name',Auth::user()->name,['class'=>'form-control','readonly'=>true]) !!}
      {!! Form::hidden('user_id',Auth::user()->id,['class'=>'form-control']) !!}
    </div>
    <div class="col-md-4">
      {!! Form::label('rack_info', 'Rak Info:') !!}
      {!! Form::text('rack_info',null,['class'=>'form-control']) !!}
    </div>
  </div><br/>
  <div class="row">
    <div class="col-md-4">
      <a href="{{route('kasir.transaction')}}" class="btn btn-info"/>Back</a>
    </div>
    <div class="col-md-8">
      {!! Form::submit('Detail Transaksi', ['class' => 'btn btn-primary form-control']) !!}
    </div>
  </div>

  {!! Form::close() !!}
</div>
<script>
$(document).ready(function() {
  $('#time_deliver').timepicker({
    minuteStep: 1,
    template: 'modal',
    appendWidgetTo: 'body',
    showSeconds: true,
    showMeridian: false,
    defaultTime: false
  });

  $('#date-order').datepicker({
    format: "dd/mm/yyyy",
    language: "id"
  });

  $('#date-deliver').datepicker({
    format: "dd/mm/yyyy",
    language: "id"
  });

  autocomplete_customer();
});


function autocomplete_customer(){
  var substringMatcher = function(strs) {
    return function findMatches(q, cb) {
      var matches, substringRegex;
      matches = [];
      substrRegex = new RegExp(q, 'i');
      $.each(strs, function(i, str) {
        if (substrRegex.test(str)) {
          matches.push(str);
        }
      });
      cb(matches);
    };
  };

  var arr1 = [];
  $("#customer").typeahead({
    hint: false,
    highlight: true,
    minLength: 2

  },
  {
    limit: 50,
    async: true,
    templates: {notFound:"Data not found"},
    source: function (query, processSync, processAsync) {
      return $.ajax({
        url: '{!! route("kasir.transaction.autocomplete") !!}',
        type: 'GET',
        data: {"term": query},
        dataType: 'json',
        success: function (json) {
          var _tmp_arr = [];
          json.map(function(item){
            _tmp_arr.push(item.name)
            arr1.push({id: item.id, st: item.name, st_a: item.address, st_p: item.phone})
          })
          return processAsync(_tmp_arr);
        }
      });
    }
  })
  $("#customer").on('typeahead:selected', function (e, code) {
      arr1.map(function(i){
        if (i.st == code){
          $("#customer_id").val(i.id);
          $("#address").val(i.st_a);
          $("#phone").val(i.st_p);
        }
      })

      if(e.keyCode==13){
        arr1.map(function(i){
          if (i.st == code){
            $("#customer_id").val(i.id);
            $("#address").val(i.st_a);
            $("#phone").val(i.st_p);
          }
        })
      }
    })
  }
</script>
@endsection
