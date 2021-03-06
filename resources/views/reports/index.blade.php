@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-12">
    <h2 class="page-header">Laporan
      <small>Transaksi - sallary</small>
    </h2>
    {!! Form::open(['route' => 'admin.report.process']) !!}
    <div class="row">
      <div class="col-md-2">
        <div class="form-group">
          {!! Form::label('date_start', 'Mulai:') !!}
          {!! Form::text('date_start',null,['id'=>'date-start','class'=>'form-control','required'=>'true']) !!}
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          {!! Form::label('date_end', 'Akhir:') !!}
          {!! Form::text('date_end',null,['id'=>'date-end','class'=>'form-control','required'=>'true']) !!}
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          {!! Form::label('user_id', 'Petugas:') !!}
          {!! Form::select('user_id', $user, null, ['class' => 'form-control']) !!}
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
            {!! Form::label('tipe', 'Tipe:') !!}
          {!! Form::select('tipe', [
          'Non Pcs' => 'Non Pcs',
          'Pcs' => 'Pcs'],
          null, ['class'=>'form-control']
          ) !!}
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
            {!! Form::label('tugas', 'Tugas:') !!}
          {!! Form::select('tugas', [
          'Tag' => 'Tag',
          'Qc' => 'Qc',
          'Setrika' => 'Setrika',
          'Cuci' => 'Cuci',
          'Packing' => 'Packing',
          'Penerimaan Laundry' => 'Penerimaan Laundry',
          'Penerimaan Laundry Full Day' => 'Penerimaan Laundry Full Day'
          ],
          null, ['class'=>'form-control']
          ) !!}
        </div>
      </div>


      <div class="col-md-2">
        <div class="form-group">
          <label>&nbsp;</label>
          {!! Form::submit('Proses', ['class' => 'btn btn-success form-control']) !!}
        </div>
      </div>
    {!! Form::close() !!}
  </div>
</div>

<script>
$(document).ready(function() {

  $('#date-start').datepicker({
    format: "dd/mm/yyyy",
    language: "id"
  });
  $('#date-end').datepicker({
    format: "dd/mm/yyyy",
    language: "id"
  });

});
</script>
@stop
