@extends('layouts.app')

@section('title', 'Preview Purchase Order')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Preview Purchase Order</h1>
        <a href="{{ route('new-purchase-order.generatePDF', ['id' => $po->id, 'download' => true]) }}" 
           class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Download PDF
        </a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <h6 class="mb-3">From:</h6>
                            <div>
                                <strong>Your Company Name</strong>
                            </div>
                            <div>Company Address</div>
                            <div>Phone: Company Phone</div>
                            <div>Email: Company Email</div>
                        </div>

                        <div class="col-sm-6">
                            <h6 class="mb-3">To:</h6>
                            <div>
                                <strong>{{ $po->supplier->name }}</strong>
                            </div>
                            <div>{{ $po->supplier->address }}</div>
                            <div>Phone: {{ $po->supplier->phone }}</div>
                            <div>Email: {{ $po->supplier->email }}</div>
                        </div>
                    </div>

                    <div class="table-responsive-sm">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-right">Quantity</th>
                                    <th class="text-right">Unit Price</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($po->items as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td class="text-right">{{ $item->pivot->quantity }}</td>
                                    <td class="text-right">{{ number_format($item->pivot->unit_price, 2) }}</td>
                                    <td class="text-right">{{ number_format($item->pivot->quantity * $item->pivot->unit_price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                    <td class="text-right"><strong>{{ number_format($po->total_amount, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-sm-5">
                            <div class="mb-2">Purchase Order Number: {{ $po->po_number }}</div>
                            <div class="mb-2">Issue Date: {{ $po->created_at->format('Y-m-d') }}</div>
                            <div>Reference PR: {{ $po->purchaseRequest->pr_number }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection