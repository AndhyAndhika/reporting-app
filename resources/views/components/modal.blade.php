<div class="modal " id="{{ $id }}" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content {{ $class ?? '' }}">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="{{ $id }}Label">
                    {{ $title }}
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body">
                {{ $body }}
            </div>
        </div>
    </div>
</div>
