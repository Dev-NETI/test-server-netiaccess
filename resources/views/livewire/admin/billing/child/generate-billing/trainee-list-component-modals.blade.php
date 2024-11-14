<div wire:ignore.self class="modal fade" id="seeDetailsModal" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalScrollableTitle">Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body table-responsive">
        <table class="table table-hover table-striped border rounded-end" style="font-size: 10px;">
          <thead>
            <tr>
              <th>Fullname</th>
              <th>Training Rate</th>
              <th>Meals</th>
              <th>Meals Rate</th>
              <th>Bus</th>
              <th>Bus Rate</th>
              <th>Dorm Status</th>
              <th>Dorm Days</th>
              <th>Dorm Rate</th>
              <th>Check In</th>
              <th>Check Out</th>
            </tr>
          </thead>
          <tbody>

            @foreach ($enroleddata as $data)
            @dd($enroleddata)
            <tr>
              <td>{{ $data->traineeid }}</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" wire:click="saveBilled" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>