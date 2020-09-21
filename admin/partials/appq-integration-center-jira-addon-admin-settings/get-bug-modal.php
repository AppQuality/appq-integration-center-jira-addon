
<!-- Modal -->
<div class="modal" style="z-index: 99999;" id="getBugModal" tabindex="-1" role="dialog" aria-labelledby="getBugModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div style="z-index: 99999;" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Get Field Mapping From Bug</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body form mt-2">
          <div class="form-group row m-2 mb-4">
            <label for="issue_id" style="position: absolute;top:0 ;font-size: 12px; opacity: 0.5; margin-bottom: 0;">Insert a bug id</label>
            <input class="form-control col-8" type="text" id="issue_id" placeholder="PROJ-####" />
            <button type="button" id="retrieve_mappings" class="col-4 btn btn-primary"> Retrieve Mappings</button>
          </div>
          
          <ul id="retrieved_mappings">
          </ul>
      </div>
    </div>
  </div>
</div>
