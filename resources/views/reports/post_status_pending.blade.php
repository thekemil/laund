<title>Laporan Status Trsansaksi</title>

<span style="font-size:15px; font-weight:bold;">Glory Laundry</span><br/>
<address>
Lobby Kolam Renang, Tower B.No B2<br>
Apartement Jarrdin Cihampelas<br>
022-91323820, 0857 9444 0447
</address>
<div style="text-align:center;">
<span style="font-size:15px; font-weight:bold;">Laporan Pelanggan Belum Bayar</span><br/>
<span style="font-size:12px;">Periode Order: {{$date_start}} - {{$date_end}}</span><br/>
<br/>
</div>
<table style="font-size:12px; border: 1px solid gray; text-align:center;" width="100%">
  <thead><?php
  $total_sudah_bayar = 0;
  $total_sisa_bayar = 0;

  $total_pcs = 0;
  $total_kg = 0;
  $total_mtr = 0;
  ?>
  <?php
          if( !function_exists('ceiling') )
          {
              function ceiling($number, $significance = 1)
              {
                  return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;
              }
          }
          ?>
      <tr>
        <th>No</th>
        <th>Invoice</th>
        <th>Nama/Alamat/Hp</th>
        <th>Tgl Order</th>
        <th>KG</th>
        <th>Mtr</th>
        <th>PCS</th>
        <th>Sudah Bayar</th>
        <th>Sisa Bayar</th>
      </tr>
        </thead>
        <tbody>
      @foreach ($data as $key=>$data)
      @if (( ($data->sudah_bayar-$data->trans_amount_total) * -1) > 0)
      <tr>
        <td>{{ $key+1 }}</td>
        <td><a href="/kasir/transaction/detail/{{$data->trans_id}}">{{ $data->trans_invoice}}</a></td>
        <td>{{ $data->cust_name.' / '.$data->cust_address.' / '.$data->cust_phone}}</td>
        <td>{{ date('d/m/Y', strtotime($data->trans_date_order)) }}</td>
        <td>{{ ($data->jml_kg == '') ? '0':$data->jml_kg }}</td>
        <td>{{ ($data->jml_mtr == '') ? '0':$data->jml_mtr }}</td>
        <td>{{ ($data->jml_pcs == '') ? '0':$data->jml_pcs }}</td>
        <td align="right">{{ number_format( ceiling($data->sudah_bayar,100), 0, ',', '.') }}</td>
        
          <td align="right">{{ number_format( (($data->sudah_bayar-$data->trans_amount_total)*-1), 0, ',', '.') }}</td>
      </tr>
      <?php
      $total_sudah_bayar += $data->sudah_bayar;
      $total_sisa_bayar += ($data->sudah_bayar-$data->trans_amount_total);
      $total_kg += $data->jml_kg;
      $total_mtr += $data->jml_mtr;
      $total_pcs += $data->jml_pcs;


      ?>
      @endif
      @endforeach
    </tbody>
    <tr style="font-weight:bold">
      <td colspan="4" align="right">
        Total:
      </td>
      <td>{{$total_kg}}</td>
      <td>{{$total_mtr}}</td>
      <td>{{$total_pcs}}</td>
      <td align="right">{{ number_format( ceiling($total_sudah_bayar,100), 0, ',', '.') }}</td>
      <td align="right">{{ number_format( ceiling($total_sisa_bayar,100), 0, ',', '.') }}</td>
    </tr>
  </tbody>
</table>
<style type="text/css">
  table{
    border-collapse: collapse;
    border: 1px solid gray;
  }
  table td{
    border: 1px solid gray;
  }

  address {
    display: block;
    font-style: normal;
    font-size: 11px;
  }
</style>
