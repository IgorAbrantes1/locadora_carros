<template>
    <div class="card mb-4">
        <div class="card-header">
            <span class="h5 m-0 p-0 row justify-content-center">{{ title }}</span>
        </div>
        <div class="card-body" :class="classes">
            <slot name="body"/>
        </div>
        <div class="card-footer">
            <slot name="footer"/>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        'title': String,
        'classes': String
    }
}
</script>

<style scoped>

</style>
