<!-- =========================================================================================
    File Name: NumberInputDecimal.vue
    Description: Add support for decimal increment
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
      Author: Pixinvent
    Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
    <vx-card title="Decimal" code-toggler>

        <span>Number Input also supports decimal values</span>

        <div class="demo-alignment">
            <vs-input-number v-model="number0" :step="0.5"/>
        </div>

        <template slot="codeContainer">
        </template>
    </vx-card>
</template>

<script>
export default {
    data() {
        return {
            number0: 1.5,
        }
    }
}
</script>
