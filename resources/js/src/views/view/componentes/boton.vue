<template>
    <button :class="clases" :type="type" :disabled="cargando">
        <slot></slot>
        <span v-if="cargando" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        <span v-if="cargando">{{texto_cargando}}</span>
        <span v-else>{{texto}}</span>
    </button>
</template>

<script>
    export default {
        name: "boton",
        props: {
            type: {
                type: String,
                default: 'button'
            }
            ,
            clases: {
                default:"vs-component vs-button vs-button-primary vs-button-filled",
                type: [Array,Object, String],
            },
            cargando: {
                type: Boolean,
                required: true
            },
            texto_cargando: {
                type: String,
                required: true
            },
            texto: {
                type: String,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
