@extends('layouts.app')

@section('title', 'Laporan Harian : ' . dateId($date))

@section('content')
<?php $dt = Carbon::parse($date) ?>

<ul class="breadcrumb hidden-print">
    <li>{{ link_to_route('reports.payments.yearly', 'Laporan Tahun ' . $dt->year, ['year' => $dt->year]) }}</li>
    <li>{{ link_to_route('reports.payments.monthly', getMonths()[monthNumber($dt->month)], ['year' => $dt->year,'month' => monthNumber($dt->month)]) }}</li>
    <li class="active">{{ $dt->format('d') }}</li>
</ul>

<h1 class="page-header">Laporan Harian : {{ dateId($date) }}</h1>

{!! Form::open(['method'=>'get','class'=>'form-inline well well-sm']) !!}
{!! Form::text('date', $date, ['class'=>'form-control','required','id'=>'date']) !!}
{!! Form::submit('Lihat Laporan', ['class'=>'btn btn-primary']) !!}
{!! link_to_route('reports.payments.daily', 'Hari Ini', [], ['class'=>'btn btn-default']) !!}
{!! link_to_route('reports.payments.monthly', 'Lihat Bulanan', ['month' => monthNumber($dt->month), 'year' => $dt->year], ['class'=>'btn btn-default']) !!}
{!! Form::close() !!}

<table class="table table-condensed table-hover">
    <thead>
        <th>{{ trans('app.table_no') }}</th>
        <th class="col-md-3">{{ trans('payment.project') }}</th>
        <th class="col-md-1 text-center">{{ trans('app.date') }}</th>
        <th class="col-md-2 text-right">{{ trans('payment.amount') }}</th>
        <th class="col-md-2">{{ trans('payment.customer') }}</th>
        <th class="col-md-3">{{ trans('payment.description') }}</th>
        <th class="col-md-1">{{ trans('app.action') }}</th>
    </thead>
    <tbody>
        <?php $total = 0; ?>
        @forelse($payments as $key => $payment)
        <tr>
            <td>{{ 1 + $key }}</td>
            <td>{{ $payment->project->name }}</td>
            <td class="text-center">{{ $payment->date }}</td>
            <td class="text-right">{{ $payment->present()->amount }}</td>
            <td>{{ $payment->customer->name }}</td>
            <td>{{ $payment->description }}</td>
            <td>
                {!! link_to_route('payments.show','Lihat',[$payment->id],['title' => 'Lihat Detail Pembayaran','target' => '_blank','class'=>'btn btn-info btn-xs']) !!}
            </td>
        </tr>
        <?php $total = $payment->in_out == 0 ? $total - $payment->amount : $total + $payment->amount ?>
        @empty
        <tr><td colspan="5">{{ trans('payment.not_found') }}</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th class="text-right" colspan="3">Jumlah</th>
            <th class="text-right">{{ formatRp($total) }}</th>
            <th colspan="3"></th>
        </tr>
    </tfoot>
</table>
@endsection

@section('ext_css')
    {!! Html::style(url('assets/css/plugins/jquery.datetimepicker.css')) !!}
@endsection

@section('ext_js')
    {!! Html::script(url('assets/js/plugins/jquery.datetimepicker.js')) !!}
@endsection

@section('script')
<script>
(function() {
    $('#date').datetimepicker({
        timepicker:false,
        format:'Y-m-d'
    });
})();
</script>
@endsection