@extends('master')
@section('content')
    <section class="section">
        @include('admin.layout.breadcrumbs', [
            'title' => __('Events'),
        ])

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-4 mt-2">
                                <div class="col-lg-8">
                                    <h2 class="section-title mt-0"> {{ __('All Events') }}</h2>
                                </div>
                                <div class="col-lg-4 text-right">
                                    @can('event_create')
                                        <button class="btn btn-primary add-button"><a href="{{ url('event/create') }}"><i
                                                        class="fas fa-plus"></i> {{ __('Add New') }}</a></button>
                                    @endcan
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table" id="report_table">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>{{ __('Image') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Start Date') }}</th>
                                        <th>{{ __('Number of People') }}</th>
                                        <th>{{ __('Category') }}</th>
                                        <th>{{ __('Total Earning') }}</th>
                                        <th>{{ __('Send Amount') }}</th>
                                        @if (Auth::user()->hasRole('admin'))
                                            <th>{{ __('Organization') }}</th>
                                        @endif
                                        @if (Auth::user()->hasRole('Organizer'))
                                            <th>{{ __('Scanner') }}</th>
                                        @endif
                                        <th>{{ __('Status') }}</th>
                                        @if (Gate::check('event_edit') || Gate::check('event_delete'))
                                            <th>{{ __('Action') }}</th>
                                        @endif
                                        @if (Gate::check('ticket_access'))
                                            <th>{{ __('Tickets') }}</th>
                                        @endif
                                        <th>{{ __('Send Amount') }}</th>
                                        <th>{{ __('Amount History') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($events as $item)
                                        <tr>
                                            <td></td>
                                            <th> <img class="table-img"
                                                      src="{{ url('images/upload/' . $item->image) }}">
                                            </th>
                                            <td>

                                                <h6 class="mb-0">{{ $item->name }}</h6>
                                                <p class="mb-0">{{ $item->address }} </p>
                                            </td>
                                            <td>
                                                <p class="mb-0">
                                                    {{ Carbon\Carbon::parse($item->start_time)->format('Y-m-d h:i a') . ', ' . $item->start_time->format('l') }}
                                                </p>
                                            </td>
                                            <td>{{ $item->people }}</td>
                                            <td>{{ $item->category->name }}</td>
                                            <td>£ {{ number_format($item->sold_tickets_price,2) }}</td>
                                            <td>£ {{ number_format($item->send_amount,2) }}</td>
                                            @if (Auth::user()->hasRole('admin'))
                                                <td>{{ $item->organization->first_name . ' ' . $item->organization->last_name }}
                                                </td>
                                            @endif
                                            @if (Auth::user()->hasRole('Organizer'))
                                                <td>
                                                    @if($item->scanner_id)
                                                        @php
                                                            $scannerIds = explode(',', $item->scanner_id);
//                                                                @dd($scannerIds);
                                                            $scannerNames = [];
                                                            foreach ($scannerIds as $scannerId) {
                                                                $scanner = \App\Models\User::find($scannerId);
                                                                if ($scanner) {
                                                                    $scannerNames[] = $scanner->first_name . ' ' . $scanner->last_name;
                                                                }
                                                            }
                                                        @endphp
                                                        {{ implode(', ', $scannerNames) }}
                                                    @else
                                                        No scanners
                                                    @endif
                                                </td>
                                            @endif
                                            <td>
                                                <h5><span
                                                            class="badge {{ $item->status == '1' ? 'badge-success' : 'badge-warning' }}  m-1">{{ $item->status == '1' ? 'Publish' : 'Draft' }}</span>
                                                </h5>
                                            </td>
                                            @if (Gate::check('event_edit') || Gate::check('event_delete'))
                                                <td>
                                                    <a href="{{ url('/events_details', $item->id) }}" title="View Event"
                                                       class="btn-icon"><i class="fas fa-eye"></i></a>
                                                    <a href="{{ url('event-gallery/' . $item->id) }}"
                                                       title="Event Gallery" class="btn-icon"><i
                                                                class="far fa-images"></i></a>
                                                    @can('event_edit')
                                                        <a href="{{ route('events.edit', $item->id) }}" title="Edit Event"
                                                           class="btn-icon"><i class="fas fa-edit"></i></a>
                                                    @endcan
                                                    @can('event_delete')
                                                        <a href="#"
                                                           onclick="deleteData('events','{{ $item->id }}');"
                                                           title="Delete Event" class="btn-icon text-danger"><i
                                                                    class="fas fa-trash-alt text-danger"></i></a>
                                                    @endcan
                                                </td>
                                            @endif
                                            @if (Gate::check('ticket_access'))
                                                <td>
                                                    <a href="{{ url($item->id . '/' . Str::slug($item->name) . '/tickets') }}"
                                                       class=" btn btn-primary">{{ __('Manage Tickets') }}</a>
                                                </td>
                                            @endif
                                            <td>
                                                <button class="btn btn-primary sendAmountBtn" data-event-id="{{ $item->id }}">{{ __('Send Amount') }}</button>
                                            </td>
                                            <td>
                                                <button class="btn btn-primary eventHistory" data-event-id="{{ $item->id }}">{{ __('Amount History') }}</button>
                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal For Send Amount-->
    <div id="sendAmountModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Send Amount</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <!-- Form to input send amount, currency, send from, send to -->
                    <form id="sendAmountForm">
                        @csrf
                        <input type="hidden" id="eventId" name="event_id">

                        <div class="form-group">
                            <label for="sendAmount">Amount:</label>
                            <input type="number" class="form-control" id="sendAmount" name="send_amount" placeholder="Enter amount">
                        </div>

                        <div class="form-group">
                            <label for="currency">Currency:</label>
                            <input type="text" value="£" class="form-control" id="currency" name="currency" placeholder="Enter currency">
                        </div>

                        <div class="form-group">
                            <label for="sendFrom">Send From:</label>
                            <input type="text" class="form-control" id="sendFrom" name="send_from" placeholder="Enter sender's number">
                        </div>

                        <div class="form-group">
                            <label for="sendTo">Send To:</label>
                            <input type="text" class="form-control" id="sendTo" name="send_to" placeholder="Enter recipient's number">
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal For Amount History-->

    <div id="amountHistoryModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Amount History</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Table to display transaction history -->
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Send From</th>
                            <th>Send To</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Rows for displaying transaction data will be filled dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Function to handle click event on 'Send Amount' button
            $('.sendAmountBtn').click(function () {
                var eventId = $(this).data('event-id');

                // Setting the event_id value in the modal
                $('#eventId').val(eventId);

                // Showing the modal manually
                $('#sendAmountModal').modal('show');
            });

            // Function to handle form submission
            $('#sendAmountForm').submit(function (e) {
                e.preventDefault(); // Prevent default form submission

                // Gather form data
                var eventId = $('#eventId').val();
                var sendAmount = $('#sendAmount').val();
                var currency = $('#currency').val();
                var sendFrom = $('#sendFrom').val();
                var sendTo = $('#sendTo').val();

                // Client-side validation
                if (sendAmount === '' || currency === '' || sendFrom === '' || sendTo === '') {
                    alert('Please fill in all fields.');
                    return;
                }

                // AJAX request setup with CSRF token
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // AJAX request to send data to backend
                $.ajax({
                    type: 'POST',
                    url: '/save-event-amount', // Replace with your backend endpoint
                    data: {
                        event_id: eventId,
                        send_amount: sendAmount,
                        currency: currency,
                        send_from: sendFrom,
                        send_to: sendTo
                    },
                    success: function (response) {
                        // Handle success response
                        console.log(response);
                        $('#sendAmountModal').modal('hide'); // Hide modal after successful submission
                        window.location.reload();
                    },
                    error: function (error) {
                        // Handle error response
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            // Event handler for the "Amount History" button
            $('.eventHistory').click(function () {
                var eventId = $(this).data('event-id');

                // AJAX request to fetch transaction history data
                $.ajax({
                    type: 'GET',
                    url: '/event-amount-history', // Replace with your backend endpoint to fetch history data
                    data: {
                        event_id: eventId
                    },
                    success: function (response) {
                        // Populate modal with transaction history data
                        populateAmountHistoryModal(response);
                    },
                    error: function (error) {
                        console.error('Error:', error);
                    }
                });
            });

            // Function to populate the Amount History modal with data
            function populateAmountHistoryModal(data) {
                var modalBody = $('#amountHistoryModal .modal-body');

                // Clear existing table rows
                modalBody.find('tbody').empty();

                // Append new table rows with transaction data
                data.forEach(function (transaction) {
                    console.log(data)
                    var row = '<tr>' +
                        '<td>' + transaction.date + '</td>' +
                        '<td>' + transaction.send_amount + '</td>' +
                        '<td>' + transaction.send_from + '</td>' +
                        '<td>' + transaction.send_to + '</td>' +
                        '</tr>';
                    modalBody.find('tbody').append(row);
                });

                // Show the modal
                $('#amountHistoryModal').modal('show');
            }
        });

    </script>
@endsection

