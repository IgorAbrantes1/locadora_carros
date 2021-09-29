<template>
    <div :id="id" aria-hidden="true" aria-labelledby="modalLabel" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modalLabel" class="modal-title">{{ modalTitle }}</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <slot name="alert"/>
                    <slot name="body"/>
                </div>
                <div class="modal-footer">
                    <slot name="footer"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        'id': String,
        'modalTitle': String
    }
}
</script>

<style scoped>

</style>
