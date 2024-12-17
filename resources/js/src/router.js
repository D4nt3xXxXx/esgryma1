/*=========================================================================================
  File Name: router.js
  Description: Routes for vue-router. Lazy loading is enabled.
  Object Strucutre:
                    path => router path
                    name => router name
                    component(lazy loading) => component to load
                    meta : {
                      rule => which user can have access (ACL)
                      breadcrumb => Add breadcrumb to specific page
                      pageTitle => Display title besides breadcrumb
                    }
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import Vue from 'vue'
import Router from 'vue-router'
import auth from "@/auth/authService";
import axios from 'axios';
import store from './store/store'

import firebase from 'firebase/app'
import 'firebase/auth'

Vue.use(Router);

const router = new Router({
    mode: 'history',
    base: process.env.BASE_URL,
    scrollBehavior() {
        return {x: 0, y: 0}
    },
    routes: [

        {
            // =============================================================================
            // MAIN LAYOUT ROUTES
            // =============================================================================
            path: '/app',
            component: () => import('./layouts/main/Main.vue'),
            children: [
                {
                    path: 'index/',
                    redirect: '/app/inicio'
                },
                {
                    path: '/app/dashboard/analytics',
                    name: 'dashboard-analytics',
                    component: () => import('./views/DashboardAnalytics.vue'),
                    meta: {
                        rule: 'editor',
                    }
                },
                {
                    path: '/app/inicio',
                    name: 'inicio',
                    component: () => import('./views/inicio.vue'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/liderProgramacion',
                    name: 'liderProgramacion',
                    component: () => import('./views/DashboardECommerce.vue'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/asistente-administrativo',
                    name: 'asistente-administrativa',
                    component: () => import('./views/AsistenteAdministrativa.vue'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/gestor',
                    name: 'gestor',
                    component: () => import('./views/gestor.vue'),
                    meta: {
                        rule: 'gestor'
                    }
                },
                {
                    path: '/app/orden-servicio',
                    name: 'orden-servicio',
                    component: () => import('./views/OrdenServicio.vue'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/reserva',
                    name: 'reservas',
                    component: () => import('./views/view/reservas/reservas'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/reserva/nueva',
                    name: 'reserva.nueva',
                    component: () => import('./views/view/reservas/reserva_manual'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/reserva/cargueAutomatico',
                    name: 'reserva.cargueAutomatico',
                    component: () => import('./views/view/reservas/cargue_automatico'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/reserva/segmentar',
                    // name: 'reserva.edit',
                    component: () => import('./views/view/reservas/reserva_edit'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/planeacion/planes',
                    name: 'planeacion.listaPlanes',
                    component: () => import('./views/view/planeacion/planes'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/planeacion/planDetalle',
                    name: 'planeacion.planDetalle',
                    component: () => import('./views/view/planeacion/index'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/planeacion/crear',
                    name: 'planeacion.crear',
                    component: () => import('./views/view/planeacion/planearBloque'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/novedad/nuevaNovedad',
                    name: 'novedad.nuevaNovedad',
                    component: () => import('./views/view/procesos/novedades/nuevaNovedad'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/novedad/listaNovedades',
                    name: 'novedad.listaNovedades',
                    component: () => import('./views/view/procesos/novedades/listaNovedades'),
                    meta: {
                        rule: 'admin'
                    }
                },

                {
                    path: '/app/salones',
                    name: 'salones',
                    component: () => import('./views/view/salones/index'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/ot',
                    name: 'ot',
                    component: () => import('./views/view/procesos/OT/index'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/listaOtRev',
                    name: 'ot.listaOTRev',
                    component: () => import('./views/view/procesos/OT/listOtRev'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/reportes',
                    name: 'procesos.reportes',
                    component: () => import('./views/view/procesos/reportes'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/agenda',
                    name: 'agenda',
                    component: () => import('./views/view/procesos/agenda'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/mestros',
                    name: 'maestros',
                    component: () => import('./views/view/maestros/index'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/maestros.categoria',
                    name: 'maestros.categoria',
                    component: () => import('./views/view/maestros/categoria'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/maestros.grupo',
                    name: 'maestros.grupo',
                    component: () => import('./views/view/maestros/grupo'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/maestros.tema',
                    name: 'maestros.tema',
                    component: () => import('./views/view/maestros/tema'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/maestros.tipo',
                    name: 'maestros.tipo',
                    component: () => import('./views/view/maestros/tipo'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/maestros.usuarios',
                    name: 'maestros.usuarios',
                    component: () => import('./views/view/maestros/usuarios'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/maestros.usuariosEdit',
                    name: 'maestros.usuariosEdit',
                    component: () => import('./views/view/maestros/usuario/configuracionUsuario'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/maestros.tipoNovedad',
                    name: 'maestros.tipoNovedad',
                    component: () => import('./views/view/maestros/tipo_novedad'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/maestros.tipoActividad',
                    name: 'maestros.tipoActividad',
                    component: () => import('./views/view/maestros/tipoActividad'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/maestros.metaMes',
                    name: 'maestros.metaMes',
                    component: () => import('./views/view/maestros/metaMes'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/clientes',
                    name: 'clientes',
                    component: () => import('./views/view/maestros/clientes'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/actividades',
                    name: 'actividades',
                    component: () => import('./views/view/maestros/actividades'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/clasificacion',
                    name: 'clasificacion',
                    component: () => import('./views/view/maestros/index'),
                    meta: {
                        rule: 'admin'
                    }
                },


                {
                    path: '/app/ordenes/gestion',
                    // name: 'gestion.ot',
                    component: () => import('./views/view/ordenes/ordenes_gestion'),
                    meta: {
                        rule: 'admin'
                    }
                },
                {
                    path: '/app/permitirNotificaciones',
                    // name: 'gestion.ot',
                    component: () => import('./views/view/componentes/notificaciones/notificacion'),
                    meta: {
                        rule: 'admin'
                    }
                },
                // {
                //     path: '/app/gestion-ot',
                //     // name: 'gestion.ot',
                //     component: () => import('./views/view/orden-trabajo/ordenes_gestion_modal'),
                //     meta: {
                //         rule: 'admin'
                //     }
                // },


            ]
        },
        {
            path: '/app',
            component: () => import('@/layouts/full-page/FullPage.vue'),
            children: [
                // =============================================================================
                // PAGES
                // =============================================================================
                {
                    path: '/app/pages/error-404',
                    name: 'page-error-404',
                    component: () => import('@/views/pages/Error404.vue'),
                    meta: {
                        rule: 'editor'
                    }
                }
            ]
        },
        // Redirect to 404 page, if no match found
        {
            path: '/app/*',
            redirect: '/app/pages/error-404'
        }
    ],
})

router.afterEach(() => {
    // Remove initial loading
    const appLoading = document.getElementById('loading-bg')
    if (appLoading) {
        appLoading.style.display = "none";
    }
})
router.beforeEach((to, from, next) => {
    axios.get("/autenticacion")
        .then(res => {
            store.commit("agregardatos", res.data);
            const autenticacion = store.getters.autenticacion;
            if (Object.keys(autenticacion).length == 0) {
                window.location = '/login';
            } else {
                next();
            }
        })
        .catch(error => {
            console.log(error.response.data.message);
        });

    return next()
});
export default router
