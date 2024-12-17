<template>
    <div class="vx-col w-full mb-base">
        <vx-card title="Multiple Columns" code-toggler>
            <div class="vx-row">
                <div class="vx-col sm:w-1/2 w-full mb-2">
                    <vs-input class="w-full" label-placeholder="First Name" v-model="input25"/>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-2">
                    <vs-input class="w-full" label-placeholder="Last Name" v-model="input26"/>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col sm:w-1/2 w-full mb-2">
                    <vs-input class="w-full" label-placeholder="City" v-model="input27"/>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-2">
                    <vs-input class="w-full" label-placeholder="Country" v-model="input28"/>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col sm:w-1/2 w-full mb-2">
                    <vs-input class="w-full" label-placeholder="Company" v-model="input29"/>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-6">
                    <vs-input type="email" class="w-full" label-placeholder="Email" v-model="input30"/>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col w-full mb-6">
                    <vs-checkbox class="inline-flex" v-model="check7">Remember Me</vs-checkbox>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col w-full">
                    <vs-button class="mr-3 mb-2">Submit</vs-button>
                    <vs-button color="warning" type="border" class="mb-2"
                               @click="input25 = input26 = input27 = input28 = input29 = input30 = ''; check7 = false;">
                        Reset
                    </vs-button>
                </div>
            </div>
        </vx-card>
    </div>
</template>

<script>
    export default {
        name: "index"
    }
</script>

<style scoped>

</style>