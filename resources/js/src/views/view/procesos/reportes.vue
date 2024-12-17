<template>
    <div v-if="isRole('liderprogramacion')">
        <dashboar-principal :categoria="[2]" :subcategoria="[20,21]"></dashboar-principal>
    </div>
    <div v-else-if="isRole('admin|coordservicio')">
        <dashboar-principal :categoria="[0,1,2]" :subcategoria="[]"></dashboar-principal>
    </div>

</template>

<script>
    import DashboarPrincipal from "../dashboarPrincipal";
    export default {
        name: "reportes",
        components: {DashboarPrincipal}
    }
</script>

<style scoped>

</style>
