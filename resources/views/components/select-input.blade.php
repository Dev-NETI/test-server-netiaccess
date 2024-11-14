@props(['label', 'defaultOption', 'data' => null])
<label for="">{{ $label }}</label>
<select Class="form-control" {{$attributes}}>
    <option value="">{{ $defaultOption }}</option>
    @if ($data !== null)
        @foreach ($data as $item)
            <option value="{{$item->dropdown_id}}">{{$item->dropdown_name}}</option>
        @endforeach
    @endif
</select>
