<div wire:ignore.self class="modal fade" id="modalPriceBreakdown" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Breakdown Prices</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body table-responsive">
                <table class="table table-striped border border-black" width="100%">
                    <thead class="table-secondary">
                        <tr class="h6">
                            <td>EnroledID</td>
                            <td style="min-width: 10em;">Fullname</td>
                            <td>Dorm Type</td>
                            <td>Dorm Rate</td>
                            @if ($foreign)
                            <td>Checkin AM</td>
                            <td>Checkin PM</td>
                            <td>Checkout AM</td>
                            <td>Checkout PM</td>
                            @endif
                            <td style="min-width: 10em;">Check In/Out Date</td>
                            <td>Days</td>
                            <td>Training Days</td>
                            <td>Meal Rate</td>
                            @if ($foreign)
                            <td>Breakfast</td>
                            <td>Lunch</td>
                            <td>Dinner</td>
                            @endif
                            <td>Meal Count</td>
                            @if ($foreign)
                            <td style="min-width: 10em;">Meal Added</td>
                            @endif
                            <td>Transpo Rate</td>
                            @if ($foreign)
                            <td>Divide to</td>
                            @endif
                            <td>Transpo Count</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($trainees))
                        @foreach ($newData as $key => $value)
                        <tr style="font-size: 10px">
                            <td>{{ $value['enroledid'] }}</td>
                            <td>{{ $value['name'] }}</td>
                            <td>{{ $value['dormtype'] }}</td>
                            <td>{{ $value['dormrate'] }}</td>
                            @if ($foreign)
                            <td>{{ $value['checkinam'] }}</td>
                            <td>{{ $value['checkinpm'] }}</td>
                            <td>{{ $value['checkoutam'] }}</td>
                            <td>{{ $value['checkoutpm'] }}</td>
                            @endif
                            <td>{{ $value['checkin'] }} - {{$value['checkout']}}</td>
                            <td>{{ $value['days'] }}</td>
                            <td>{{ $value['trainingdays'] }}</td>
                            <td>{{ $value['mealrate'] }}</td>
                            @if ($foreign)
                            <td>{{$value['breakfast']}}</td>
                            <td>{{$value['lunch']}}</td>
                            <td>{{$value['dinner']}}</td>
                            @endif
                            <td>{{ $value['mealcount'] }}</td>
                            @if ($foreign)
                            <td>
                                @foreach ($value['meal'] as $key => $mealvalue )
                                {{$key }} : {{$mealvalue}} <br>
                                @endforeach
                            </td>
                            @endif
                            <td>{{ $value['transporate'] }}</td>
                            @if ($foreign)
                            <td>{{ $value['divideto'] }}</td>
                            @endif
                            <td>{{ $value['transpocount'] }}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr style="font-size: 10px">
                            <td colspan="9">No data to show</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>