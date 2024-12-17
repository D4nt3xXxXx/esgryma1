<template>
    <!--<div v-if="isRole('liderprogramacion')">
        <dashboar-principal :categoria="[2]" :subcategoria="[20,21]"></dashboar-principal>
    </div>
    <div v-else-if="isRole('asisadministrativo')">
        <dashboar-principal :categoria="[2]" :subcategoria="[1]"></dashboar-principal>
    </div>
    <div v-else-if="isRole('admin|liderservicio')">
        <dashboar-principal :categoria="[0,1,2]" :subcategoria="[]"></dashboar-principal>
    </div>
    <div v-else-if="isRole('gestor')">
        <dashboar-principal :categoria="[3]" :subcategoria="[23]"></dashboar-principal>
    </div>-->
    <gestor></gestor>
</template>

<script>
    import liderProgramacion from './DashboardECommerce'
    import asisAdministrativo from './AsistenteAdministrativa'
    import gestor from './gestor'
    import DashboarPrincipal from "./view/dashboarPrincipal";

    export default {
        name: "inicio",
        components: {
            DashboarPrincipal,
            liderProgramacion, asisAdministrativo, gestor
        }
    }
</script>

<style scoped>

</style>
