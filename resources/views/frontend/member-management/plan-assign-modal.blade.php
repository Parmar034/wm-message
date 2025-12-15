<div class="modal fade" id="assignPlanModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Assign Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ route('member-plan.assign.store') }}" data-parsley-validate>
                @csrf
                <div class="modal-body">

                    <input type="hidden" name="user_id" id="modal_user_id">

                    <div class="mb-3">
                        <label class="form-label">Member name</label>
                        <input type="text" class="form-control" id="modal_user_name" name="modal_user_name" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Select Plan</label>
                        <select class="form-control" name="plan_id" id="modal_plan_name" required>
                            <option value="">-- Select Plan --</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}"
                                    data-price="{{ $plan->price }}"
                                    data-type="{{ $plan->plan_type }}"
                                    data-limit="{{ $plan->message_count }}">
                                    {{ $plan->plan_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="modal-plan-details" style="display:none;">
                        <p><strong>Plan Type:</strong> <span id="modal-plan-type"></span></p>
                        <p><strong>Plan Price:</strong> ₹<span id="modal-plan-price"></span></p>
                        <p><strong>Message Limit:</strong> <span id="modal-plan-limit"></span></p>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Assign Plan</button>
                </div>
            </form>

        </div>
    </div>
</div>
