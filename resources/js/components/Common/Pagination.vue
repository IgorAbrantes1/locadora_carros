<template>
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <slot/>
        </ul>
    </nav>
</template>

<script>
export default {}
</script>

<style scoped>
.pagination {
    cursor: pointer;
}
</style>
