<div class="modal modal-blur fade" id="add-event-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header Unioil-header">
            <h5 class="modal-title">Add Event</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" method="post" id="add-event-form" class="row g-3">
                <!-- Event Name -->
                <div class="col-md-6 col-12">
                    <label for="event_name" class="form-label">Event Name</label>
                    <input type="text" class="form-control" name="event_name" id="event_name" placeholder="Enter event name" maxlength="30">
                </div>

                <!-- Event Price -->
                <div class="col-md-6 col-12">
                    <label for="event_price" class="form-label">Event Prize</label>
                    <input type="text" class="form-control" name="event_price" id="event_price" placeholder="Enter event prize"  maxlength="50">
                </div>

                <!-- Event Start -->
                <div class="col-md-6 col-12">
                    <label for="event_start" class="form-label">Event Start</label>
                    <input type="text" class="form-control" name="event_start" id="event_start" placeholder="Enter event start">
                </div>

                <!-- Event End -->
                <div class="col-md-6 col-12">
                    <label for="event_end" class="form-label">Event End</label>
                    <input type="text" class="form-control" name="event_end" id="event_end" placeholder="Enter event end">
                </div>

                <!-- Event Description -->
                <div class="col-12">
                    <label for="event_description" class="form-label">Event Description</label>
                    <textarea class="form-control" name="event_description" id="event_description" rows="4" placeholder="Enter event description"></textarea>
                </div>

            </form>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark me-auto" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="submitdata('add-event-form',`/api/add-event`)">Save changes</button>
          </div>
        </div>
      </div>
    </div>