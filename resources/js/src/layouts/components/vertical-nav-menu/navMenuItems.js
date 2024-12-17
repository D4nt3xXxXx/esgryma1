/*=========================================================================================
  File Name: sidebarItems.js
  Description: Sidebar Items list. Add / Remove menu items from here.
  Strucutre:
          url     => router path
          name    => name to display in sidebar
          slug    => router path name
          icon    => Feather Icon component/icon name
          tag     => text to display on badge
          tagColor  => class to apply on badge element
          i18n    => Internationalization
          submenu   => submenu of current item (current item will become dropdown )
                NOTE: Submenu don't have any icon(you can add icon if u want to display)
          isDisabled  => disable sidebar item/group
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


export default [
    // {
    //   url: "/apps/email",
    //   name: "Email",
    //   slug: "email",
    //   icon: "MailIcon",
    // },
    {
        url: null,
        name: "Inicio",
        tag: "2",
        tagColor: "warning",
        icon: "HomeIcon",
        rol: '*',
        submenu: [
            {
                url: '/app/inicio',
                name: "Inicio",
                slug: "inicio",
                rol: 'admin|liderprogramacion|asisadministrativo|gestor'
            },
            /*{
                url: '/app/liderProgramacion',
                name: "Lider programación",
                slug: "asistente-administrativa",
                rol: "admin|liderprogramacion"
            },
            {
                url: '/app/asistente-administrativo',
                name: "Asistente Administrativo",
                slug: "asistente-administrativa",
                rol: "admin|asisadministrativo"
            },
            {
                url: '/app/gestor',
                name: "gestor",
                slug: "gestor",
                rol: "admin|gestor"
            },*/
        ]
    },

    {
        header: "Procesos",
        tag: "2",
        tagColor: "warning",
        icon: "CopyIcon",
        rol: '*',
        items: [
            {
                url: null,
                name: "Reserva",
                slug: "Reserva",
                icon: "ListIcon",
                tag: "2",
                rol: 'admin|liderprogramacion|coordprevencion',
                submenu: [
                    {
                        url: '/app/reserva',
                        name: "Lista de reservas",
                        slug: "reservas",
                        rol: 'admin|liderprogramacion|coordprevencion'
                    },
                    {
                        url: '/app/reserva/nueva',
                        name: "Nueva reserva",
                        slug: "reserva_manual",
                        rol: 'admin|liderprogramacion'
                    },
                    {
                        url: '/app/reserva/cargueAutomatico',
                        name: "Cargue Automatico",
                        slug: "reserva_manual",
                        rol: 'admin|liderprogramacion'
                    },
                    /*{
                        url: '/app/reserva/OrdenServicio',
                        name: "Orden",
                        slug: "reserva.OrdenServicio",
                        rol: 'admin|asisadministrativo'
                    }*/

                ]

            },
            {
                url: null,
                name: "Planeacion",
                slug: "planeacion",
                icon: "CheckSquareIcon",
                rol: 'admin|liderprogramacion',
                submenu: [
                    {
                        url: "/app/planeacion/crear",
                        name: "Planear en bloque",
                        slug: "planeacion.crear",
                        rol: 'admin|liderprogramacion'
                    },
                    {
                        url: '/app/planeacion/planes',
                        name: "Lista Planes",
                        slug: "planeacion.listaPlanes",
                        rol: 'admin|liderprogramacion'
                    },
                ]
            },
            {
                url: null,
                name: "Ordenes",
                slug: "Ordenes",
                icon: "ClipboardIcon",
                tag: "2",
                rol: 'admin|asisadministrativo|coordprevencion',
                submenu: [
                    {
                        url: '/app/ordenes/gestion',
                        name: "Gestión de ordenes",
                        slug: "ordenes",
                        rol: 'admin|asisadministrativo|coordprevencion'
                    }

                ]
            },
            {
                url: null,
                name: "Novedades",
                slug: "novedades",
                icon: "CheckSquareIcon",
                rol: 'admin|liderprogramacion|asisadministrativo',
                submenu: [
                    {
                        url: "/app/novedad/nuevaNovedad?opcion=nueva",
                        name: "Nueva Novedad",
                        slug: "novedad.nuevaNovedad",
                        rol: 'admin|liderprogramacion|asisadministrativo'
                    },
                    {
                        url: '/app/novedad/listaNovedades',
                        name: "Lista novedades",
                        slug: "novedad.listaNovedades",
                        rol: 'admin|liderprogramacion'
                    },
                ]
            },
            {
                url: "/app/salones",
                name: "Salones",
                slug: "salones",
                icon: "CalendarIcon",
                rol: 'admin|asisadministrativo'
            },
            {
                url: "/app/ot",
                name: "O.T",
                slug: "ot",
                icon: "MenuIcon",
                rol: 'admin|gestor'
            },
            {
                url: "/app/agenda",
                name: "Agenda",
                slug: "agenda",
                icon: "MenuIcon",
                rol: 'admin|gestor|asisadministrativo'
            },
            {
                url: "/app/listaOtRev",
                name: "Lista OT rev",
                slug: "ot.listaOTRev",
                icon: "MenuIcon",
                rol: 'admin|coordprevencion'
            },
            {
                url: "/app/reportes",
                name: "Reportes",
                slug: "procesos.reportes",
                icon: "MenuIcon",
                rol: 'admin|coordservicio|liderprogramacion'
            },
        ]
    },

    {
        header: "Maestros",
        tag: "2",
        tagColor: "warning",
        icon: "SettingsIcon",
        rol: 'admin|liderprogramacion',
        items: [
            {
                url: '/app/clientes',
                name: "Clientes",
                slug: "clientes",
                icon: "MenuIcon",
                rol: 'admin|liderprogramacion'
            },
            {
                url: '/app/actividades',
                name: "Actividades",
                slug: "actividades",
                icon: "MenuIcon",
                rol: 'admin|liderprogramacion'
            },
            {
                header: 'Clasificación',
                name: "Clasificacion",
                slug: "clasificacion",
                icon: "MenuIcon",
                rol: 'admin|liderprogramacion',
                submenu: [
                    {
                        url: '/app/maestros.categoria',
                        name: "Categoria",
                        slug: "maestros.categoria",
                        icon: "MenuIcon",
                        rol: 'admin|liderprogramacion'
                    },
                    {
                        url: '/app/maestros.grupo',
                        name: "Grupo",
                        slug: "maestros.grupo",
                        icon: "MenuIcon",
                        rol: 'admin|liderprogramacion'
                    },
                    {
                        url: '/app/maestros.tema',
                        name: "Tema",
                        slug: "maestros.tema",
                        icon: "MenuIcon",
                        rol: 'admin|liderprogramacion'
                    },
                    {
                        url: '/app/maestros.tipoActividad',
                        name: "Tipo",
                        slug: "maestros.tipoActividad",
                        icon: "MenuIcon",
                        rol: 'admin|liderprogramacion'
                    },
                ]
            },
            {
                url: '/app/maestros.usuarios',
                name: "Usuarios",
                slug: "maestros.usuarios",
                icon: "MenuIcon",
                rol: 'admin|liderprogramacion'
            },
            {
                url: '/app/maestros.tipoNovedad',
                name: "Tipo novedad",
                slug: "maestros.tipoNovedad",
                icon: "MenuIcon",
                rol: 'admin|liderprogramacion'
            },
            {
                url: '/app/maestros.metaMes',
                name: "Meta mes",
                slug: "maestros.metaMes",
                icon: "MenuIcon",
                rol: 'admin|liderprogramacion'
            }
        ]
    },
]

