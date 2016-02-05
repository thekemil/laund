<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Session;
use App\Models\Transaction;
use App\Models\Package;
use App\Models\Item;
use App\User;
use App\Models\TransactionDetail;
use App\Models\TransactionItem;
use App\Models\TransactionUser;
use App\Models\TransactionPcs;
use App\Models\TransactionPayroll;
use App\Models\PaymentHistory;
use App\Models\Customer;
use App\Models\Status;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;

class TransactionPayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function report()
    {
     return view('payrolls.report');
 }

 public function process_report(Request $request)
 {
    $date_start = $this->saved_date_format($request->input('date_start'));
    $date_end = $this->saved_date_format($request->input('date_end'));

    $data = $this->get_data_report($date_start,$date_end);
    $date_start = $request->input('date_start');
    $date_end = $request->input('date_end');

    return view('reports.print_payroll', compact('data','date_start','date_end'));

}

public function get_data_report($date_start,$date_end)
  {
    $date_end = Carbon::parse($date_end)->addDays(1);
    $results = TransactionPayroll::whereBetween('transaction_payrolls.payroll_date', [$date_start, $date_end])
    ->join('users','transaction_payrolls.user_id','=','users.id')
    ->select('transaction_payrolls.payroll_date','transaction_payrolls.depart','transaction_payrolls.gpk','transaction_payrolls.bonus','transaction_payrolls.description','users.name')
    ->whereBetween('transaction_payrolls.payroll_date', [$date_start, $date_end])
    ->where('transaction_payrolls.deleted','=',0)
    ->get();

    return $results;
  }

public function index()
{
 return view('payrolls.index');
}

public function payroll_data()
{
    \DB::statement(\DB::raw('set @rownum=0'));
    $transaction_payrolls = \DB::table('transaction_payrolls')
    ->join('users', 'transaction_payrolls.user_id', '=', 'users.id')
    ->select([\DB::raw('@rownum  := @rownum  + 1 AS rownum'),
      'transaction_payrolls.id as tp_id',
      'transaction_payrolls.payroll_date',
      'users.name',
      'transaction_payrolls.depart as depart',
      'transaction_payrolls.gpk as gpk',
      'transaction_payrolls.bonus as bonus'

      ])
    ->where('transaction_payrolls.deleted','=','0')
    ->orderBy('transaction_payrolls.payroll_date', 'desc');

    return Datatables::of($transaction_payrolls)
    ->editColumn('payroll_date', function ($transaction_payrolls) {
        return $transaction_payrolls->payroll_date ? with(new Carbon($transaction_payrolls->payroll_date))->format('d/m/Y') : '';
    })
    ->addColumn('action', function ($transaction_payrolls) {
      return
      '<div class="col-md-3">
      <a href="./payroll/edit/'.$transaction_payrolls->tp_id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>
  </div>
  <div class="col-md-3">
    <form method="POST" action="./payroll/destroy/'.$transaction_payrolls->tp_id.'" accept-charset="UTF-8" class="inline">
      <input name="_method" type="hidden" value="PATCH">
      <input name="_token" type="hidden" value="'.csrf_token().'">
      <input id="deleted" class="form-control" name="deleted" type="hidden" value="1">
      <input class="inline btn btn-danger btn-xs" type="submit" value="Hapus">
  </form>
</div>

';
})
    ->make(true);
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('payrolls.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $date_payroll = $this->saved_date_format($request->input('payroll_date'));
        
        $request->merge(array('payroll_date'=>$date_payroll));

        $transaction=$request->input();
        $save_trans = TransactionPayroll::create($transaction);

        Session::flash('flash_message', 'Data Payroll berhasil ditambahkan');

        return redirect()->route('payroll.payroll');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transaction_payroll=TransactionPayroll::find($id);
        $user = User::where('id', '=', $transaction_payroll->user_id)->firstOrFail();

        return view('payrolls.edit',compact(['transaction_payroll','user']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $date_payroll = $this->saved_date_format($request->input('payroll_date'));
        
        $request->merge(array('payroll_date'=>$date_payroll));

        $transUpdate=$request->input();
        $trans=TransactionPayroll::find($id);

        $trans->update($transUpdate);

        Session::flash('flash_message', 'Data berhasil diubah!');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $transUser=$request->input();

        $trans = TransactionPayroll::find($id);

        $trans->update($transUser);

        Session::flash('flash_message', 'Data berhasil dihapus!');

        return redirect()->back();
    }

    private function saved_date_format($date)
    {
        $date_split = explode('/',$date);

        $year = $date_split[2];
        $month = $date_split[1];
        $day = $date_split[0];

        $format = $year.'-'.$month.'-'.$day;

        return $format;
    }
}