<template>
    <div v-if="type === 'file' || type === 'image'" class="mt-2">
        <label :for="id">{{ title }}<span class="text-danger">{{ optional === false ? '*' : '' }}</span></label>
        <slot/>
        <div :id="idHelp" class="form-text">{{ textHelp }}</div>
    </div>
    <div v-else class="form-floating">
        <slot/>
        <label :for="id">{{ title }}<span class="text-danger">{{ optional === false ? '*' : '' }}</span></label>
        <div :id="idHelp" class="form-text">{{ textHelp }}</div>
    </div>
</template>

<script>
export default {
    props: {
        'id': String,
        'title': String,
        'idHelp': String,
        'textHelp': String,
        'optional': Boolean,
        'type': String
    }
}
</script>
