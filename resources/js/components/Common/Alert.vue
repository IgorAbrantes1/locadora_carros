<template>
    <div>
        <div :class="style" class="alert" role="alert">
            <ul v-for="(message, key) in messages" :key="key" class="my-0 py-0">
                <li>{{ message[0] }}</li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        'type': String,
        'messages': []
    },

    computed: {
        style() {
            return 'alert alert-' + this.type;
        }
    }
}
</script>

<style scoped>

</style>
