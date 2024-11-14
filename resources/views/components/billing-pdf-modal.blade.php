@props(['url'])

<div class="modal fade gd-example-modal-xl" id="pdfModal" tabindex="-1" role="dialog"  aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        {{-- <a href="{{ $url }}" target="_blank">Click me to redirect</a> --}}
        <iframe src="{{$url}}" frameborder="0" style="width: 100%; min-height: 90vh;"></iframe>
      </div>
    </div>
  </div>