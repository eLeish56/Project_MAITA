<x-layout>
    <x-slot:title>Purchase Order</x-slot:title>
    <x-slot:styles>
        <style>
            .nav-pills .nav-link.active {
                background-color: #0d6efd;
                color: white !important;
            }
            .nav-link {
                padding: 0.5rem 1rem;
                border-radius: 0.25rem;
            }
            .nav-link:hover {
                background-color: #e9ecef;
            }
            table thead th {
                white-space: nowrap;
            }
        </style>
    </x-slot:styles>
    
    <div class="d-flex">

    <!-- Main content -->
    <div class="flex-grow-1 p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Purchase Orders</h2>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Approved Purchase Requests -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Approved Purchase Requests</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>PR Number</th>
                                <th>Supplier</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($approvedPRs as $pr)
                                <tr>
                                    <td>{{ $pr->pr_number }}</td>
                                    <td>{{ $pr->supplier->name }}</td>
                                    <td>{{ $pr->request_date->format('d/m/Y') }}</td>
                                    <td>
                                        @if (!$pr->purchaseOrders->count())
                                            <a href="{{ route('new-purchase-orders.create-direct', ['purchaseRequest' => $pr->id]) }}" 
                                               class="btn btn-primary btn-sm">
                                                Create PO
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No approved purchase requests found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Ongoing Purchase Orders -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Ongoing Purchase Orders</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>PO Number</th>
                                <th>Supplier</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ongoingPOs as $po)
                                <tr>
                                    <td>{{ $po->po_number }}</td>
                                    <td>{{ $po->supplier->name }}</td>
                                    <td>
                                        <span class="badge {{ $po->status === 'sent' ? 'bg-info' : 'bg-warning' }}">
                                            {{ ucfirst($po->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('new-purchase-orders.show', $po->id) }}" 
                                           class="btn btn-info btn-sm">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No ongoing purchase orders found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Completed Purchase Orders -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Completed Purchase Orders</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>PO Number</th>
                                <th>Supplier</th>
                                <th>Status</th>
                                <th>GR Number</th>
                                <th>Invoice</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($completedPOs as $po)
                                <tr>
                                    <td>{{ $po->po_number }}</td>
                                    <td>{{ $po->supplier->name }}</td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ ucfirst($po->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($po->goodsReceipts->isNotEmpty())
                                            {{ $po->goodsReceipts->first()->gr_number }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($po->invoices->isNotEmpty())
                                            <span class="badge bg-success">PAID</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('new-purchase-orders.show', $po->id) }}" 
                                           class="btn btn-info btn-sm">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No completed purchase orders found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layout>