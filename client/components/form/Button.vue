<template>
    <button type="button"
        class="flex items-center justify-center gap-x-2 outline-none rounded-md text-xs truncate font-semibold" :class="[
            props.buttonStyle === 'primary' && 'bg-tertiary border border-tertiary text-white hover:bg-tertiary-800',
            props.buttonStyle === 'action' && 'border bg-tertiary text-white hover:bg-tertiary-800',
            props.buttonStyle === 'success' && 'bg-green-800 text-white hover:bg-green-900',
            props.buttonStyle === 'warning' && 'bg-orange-500 text-white hover:bg-orange-400',
            props.buttonStyle === 'danger' && 'bg-red-600 text-white hover:bg-red-700',
            props.buttonStyle === 'incident' && 'border border-red-600 text-red-600 hover:border-red-700 hover:text-red-700',
            props.buttonStyle === 'incident-link' && 'text-red-600 hover:text-red-700',
            props.buttonStyle === 'link' && 'text-gray-800 hover:text-tertiary',
            props.buttonStyle === 'back' && 'text-tertiary hover:text-tertiary/90 pl-0',
            props.buttonStyle === 'cancel' && 'bg-tertiary-50 hover:bg-tertiary-100/90',
            props.buttonStyle === 'xxx' && 'bg-gray-200 text-gray-700 hover:bg-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-300',
            props.buttonStyle === 'white' && 'bg-white hover:hover:bg-tertiary-25',
            !props.buttonStyle && 'text-tertiary border border-tertiary hover:bg-tertiary hover:text-white',
            props.buttonSize === 'sm' && 'p-3',
            props.buttonSize === 'xs' && 'p-2',
            !props.buttonSize && 'px-4 py-3.5',
            props.disabled && 'py-3.5 bg-red-400 hover:bg-red-400 cursor-not-allowed'
        ]" :disabled="props.disabled">
        <slot name="icon-left" />
        <slot></slot>
        <slot name="right-left" />
    </button>
</template>

<script setup lang="ts">
const props = defineProps({
    buttonStyle: {
        type: String,
        required: false,
    },
    disabled: {
        type: Boolean,
        required: false,
        default: false,
    },
    buttonSize: {
        type: String,
        required: false,
    },
})
</script>