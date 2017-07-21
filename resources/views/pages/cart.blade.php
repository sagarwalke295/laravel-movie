@extends('layouts.app')

@section('content')
    <header class="page-header">
        <h3>Your Cart</h3>
    </header>
    {{--<pre>--}}
        {{--@php(var_dump($cart))--}}
    {{--</pre>--}}
    @if (empty($cart))
        <p>There are no items in your cart.</p>
    @else
        <div class="row">
            <div class="col-sm-8">
                @foreach ($cart as $item)
                    <form class="cart-update-form" method="post" action="{{route('update_cart')}}">
                        {{csrf_field()}}
                        <div class="panel panel-default">
                            <!-- Default panel contents -->
                            <div class="panel-heading">
                                <strong>{{$item['session']->movie->title}}</strong><br>
                                <strong>Cinema:</strong> {{$item['session']->cinema->city}}<br>
                                <strong>Date:</strong> {{$item['session']->scheduled_date_string}}<br>
                                <strong>Time:</strong> {{$item['session']->scheduled_time_string}}
                            </div>

                            <table class="table table-default">
                                <thead>
                                <tr>
                                    <th>Ticket Type</th>
                                    <th>Quantity</th>
                                    <th>Ticket Cost</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($item['tickets'] as $ticket)
                                    @if (intval($ticket['quantity']) > 0)
                                        <tr>
                                            <td>{{$ticket['ticket_type_name']}}</td>
                                            <td><input type="number" name="{{$ticket['ticket_type_id']}}" style='width:auto' class="form-control input-sm" value="{{$ticket['quantity']}}" max="20" min="0"></td>
                                            <td>{{sprintf('$%.2f', ($ticket['ticket_type_cost']))}}</td>
                                            <td>{{sprintf('$%.2f', ($ticket['ticket_type_cost'] * intval($ticket['quantity'])))}}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>

                            <div class="panel-footer clearfix">
                                <input hidden name="session_id" value="{{$item['session']->id}}">
                                <button type="submit" class="pull-right btn btn-primary">Update Quantities</button>
                            </div>
                        </div>
                    </form>
                @endforeach
            </div>
            <div class="col-sm-4">
                <div class="panel panel-default">
                    <!-- Default panel contents -->
                    <div class="panel-heading">
                        Grand Total
                    </div>
                    <div class="panel-body">
                        <strong style="font-size: 3em">{{sprintf('$%.2f', $grand_total)}}</strong>
                    </div>
                </div>
                <a role="button" class="btn-block btn btn-success" href="{{route('checkout')}}">Checkout</a>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        // AJAX to delete a ticket type (using the X button)
        $('.remove-button').each(function () {
            $(this).click(function () {
                var session_id = $(this).attr('data-session-id');
                var ticket_type_id = $(this).attr('data-ticket-type-id');
                var data = 'session_id='+session_id+'&ticket_type_id='+ticket_type_id;
                console.log(data);
                $.ajax({
                    method: 'POST',
                    url: '/removecartitem',
                    data: data,
                    success: function (response) {
                        console.log(response);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });

                location.reload();
            })
        });
    </script>
@endsection