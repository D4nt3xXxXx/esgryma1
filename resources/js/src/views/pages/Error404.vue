<!-- =========================================================================================
    File Name: Error404.vue
    Description: 404 Page
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
      Author: Pixinvent
    Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
    <div class="h-screen flex w-full bg-img">
        <div
            class="vx-col flex items-center justify-center flex-col sm:w-1/2 md:w-3/5 lg:w-3/4 xl:w-1/2 mx-auto text-center">
            <img src="@assets/images/pages/404.png" alt="graphic-404" class="mx-auto mb-4 max-w-full">
            <h1 class="sm:mx-0 mx-4 mb-12 text-5xl d-theme-heading-color">404 - Pagina no encontrada!</h1>
            <p class="sm:mx-0 mx-4 mb-4 sm:mb-16 d-theme-text-inverse">La pagina que esta intentando ingresar no se
                encuentra disponible, puede que no exista o está fuera de uso. Cualquier inquietud contactar con el
                administrador del sistemas.</p>
            <vs-button size="large" @click="regresar">Regresar</vs-button>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            regresar() {
                this.$router.back();
            }
        }
    }
</script>
