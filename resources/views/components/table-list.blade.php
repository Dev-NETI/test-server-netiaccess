@props(['title' => false, 'data', 'total' => 0])
<div class="row">
    <div class="col-lg-12">
        <div class="card mt-4">
            @if ($title)
            <div class="card-header">
                <h3>{{ $title }}</h3>
            </div>
            @endif
            <div class="card-body row">
                <div class="offset-md-8 col-md-4">
                    <input type="text" class="form-control my-2" placeholder="Search..." {{ $attributes }} />
                </div>
                <div class="table-responsive col-md-12">
                    <x-table>
                        {{ $slot }}
                    </x-table>
                </div>
            </div>
            <div class="card-footer">
                <div class="row mt-5" style="padding-bottom: 6.5em;">
                    {{ $data->links('livewire.components.customized-pagination-link') }}
                    Total: {{ $total }}
                </div>
            </div>
        </div>
    </div>
</div>