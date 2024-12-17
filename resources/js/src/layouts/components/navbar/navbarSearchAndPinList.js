export default {
    pages: {
        key: "title",
        data: [
            // DASHBOARDS
            {title: "Analytics Dashboard", url: "/dashboard/analytics", icon: "HomeIcon", is_bookmarked: false},
            {title: "eCommerce Dashboard", url: "/dashboard/ecommerce", icon: "HomeIcon", is_bookmarked: false},

            // APPS
            {title: "Todo", url: "/apps/todo", icon: "CheckSquareIcon", is_bookmarked: true},
            {title: "Chat", url: "/apps/chat", icon: "MessageSquareIcon", is_bookmarked: true},
            {title: "Email", url: "/apps/email", icon: "MailIcon", is_bookmarked: true},
            {title: "Calendar", url: "/apps/calendar/vue-simple-calendar", icon: "CalendarIcon", is_bookmarked: true},
            {title: "E-Commerce Shop", url: "/apps/eCommerce/shop", icon: "ShoppingCartIcon", is_bookmarked: true},
            {
                title: "E-Commerce Item Detail",
                url: "/apps/eCommerce/item",
                icon: "SmartphoneIcon",
                is_bookmarked: false
            },
            {title: "E-Commerce Wish List", url: "/apps/eCommerce/wish-list", icon: "HeartIcon", is_bookmarked: false},
            {
                title: "E-Commerce Checkout",
                url: "/apps/eCommerce/checkout",
                icon: "CreditCardIcon",
                is_bookmarked: false
            },

            // UI ELEMENTS
            {
                title: "Data List - List View",
                url: "/ui-elements/data-list/list-view",
                icon: "ListIcon",
                is_bookmarked: false
            },
            {
                title: "Data List - Thumb View",
                url: "/ui-elements/data-list/thumb-view",
                icon: "ImageIcon",
                is_bookmarked: false
            },
            {title: "Vuesax Grid", url: "/ui-elements/grid/vuesax", icon: "LayoutIcon", is_bookmarked: false},
            {title: "Tailwind Grid", url: "/ui-elements/grid/tailwind", icon: "LayoutIcon", is_bookmarked: false},
            {title: "Colors", url: "/ui-elements/colors", icon: "DropletIcon", is_bookmarked: false},
            {title: "Basic Cards", url: "/ui-elements/card/basic", icon: "CreditCardIcon", is_bookmarked: false},
            {
                title: "Statistics Card",
                url: "/ui-elements/card/statistics",
                icon: "CreditCardIcon",
                is_bookmarked: false
            },
            {
                title: "Analytics Cards",
                url: "/ui-elements/card/analytics",
                icon: "CreditCardIcon",
                is_bookmarked: false
            },
            {
                title: "Card Actions",
                url: "/ui-elements/card/card-actions",
                icon: "CreditCardIcon",
                is_bookmarked: false
            },
            {title: "Card Colors", url: "/ui-elements/card/card-colors", icon: "FeatherIcon", is_bookmarked: false},
            {title: "Table", url: "/ui-elements/table", icon: "GridIcon", is_bookmarked: false},
            {title: "agGrid Table", url: "/ui-elements/ag-grid-table", icon: "GridIcon", is_bookmarked: false},
            {title: "Alert Component", url: "/components/alert", icon: "AlertTriangleIcon", is_bookmarked: false},
            {title: "Avatar Component", url: "/components/avatar", icon: "UserIcon", is_bookmarked: false},
            {
                title: "Breadcrumb Component",
                url: "/components/breadcrumb",
                icon: "NavigationIcon",
                is_bookmarked: false
            },
            {title: "Button Component", url: "/components/button", icon: "BoldIcon", is_bookmarked: false},
            {title: "Button Group Component", url: "/components/button-group", icon: "BoldIcon", is_bookmarked: false},
            {title: "Chip Component", url: "/components/chip", icon: "TagIcon", is_bookmarked: false},
            {title: "Collapse Component", url: "/components/collapse", icon: "PlusIcon", is_bookmarked: false},
            {title: "Dialogs Component", url: "/components/dialogs", icon: "CopyIcon", is_bookmarked: false},
            {title: "Divider Component", url: "/components/divider", icon: "MinusIcon", is_bookmarked: false},
            {
                title: "DropDown Component",
                url: "/components/dropdown",
                icon: "MoreHorizontalIcon",
                is_bookmarked: false
            },
            {title: "List Component", url: "/components/list", icon: "ListIcon", is_bookmarked: false},
            {title: "Loading Component", url: "/components/loading", icon: "LoaderIcon", is_bookmarked: false},
            {title: "Navbar Component", url: "/components/navbar", icon: "CreditCardIcon", is_bookmarked: false},
            {
                title: "Notifications Component",
                url: "/components/notifications",
                icon: "BellIcon",
                is_bookmarked: false
            },
            {
                title: "Pagination Component",
                url: "/components/pagination",
                icon: "ChevronsRightIcon",
                is_bookmarked: false
            },
            {title: "Popup Component", url: "/components/popup", icon: "CopyIcon", is_bookmarked: false},
            {title: "Progress Component", url: "/components/progress", icon: "SlidersIcon", is_bookmarked: false},
            {title: "Sidebar Component", url: "/components/sidebar", icon: "SidebarIcon", is_bookmarked: false},
            {title: "Slider Component", url: "/components/slider", icon: "SlidersIcon", is_bookmarked: false},
            {title: "Tabs Component", url: "/components/tabs", icon: "CreditCardIcon", is_bookmarked: false},
            {title: "Tooltip Component", url: "/components/tooltip", icon: "AlertCircleIcon", is_bookmarked: false},
            {title: "Upload Component", url: "/components/upload", icon: "UploadIcon", is_bookmarked: false},

            // FORMS
            // {title: "Select Form Element",       url: "/forms/form-elements/select",         icon: "CheckIcon",          is_bookmarked: false},
            {
                title: "Switch Form Element",
                url: "/forms/form-elements/switch",
                icon: "ToggleLeftIcon",
                is_bookmarked: false
            },
            {
                title: "Checkbox Form Element",
                url: "/forms/form-elements/checkbox",
                icon: "CheckSquareIcon",
                is_bookmarked: false
            },
            {title: "Radio Form Element", url: "/forms/form-elements/radio", icon: "DiscIcon", is_bookmarked: false},
            {title: "Input Form Element", url: "/forms/form-elements/input", icon: "TypeIcon", is_bookmarked: false},
            {
                title: "Number Input Form Element",
                url: "/forms/form-elements/number-input",
                icon: "TypeIcon",
                is_bookmarked: false
            },
            {
                title: "Textarea Form Element",
                url: "/forms/form-elements/textarea",
                icon: "TypeIcon",
                is_bookmarked: false
            },
            {title: "Form Layouts", url: "/forms/form-layouts", icon: "LayoutIcon", is_bookmarked: false},
            {title: "Form Wizard", url: "/forms/form-wizard", icon: "GitCommitIcon", is_bookmarked: false},
            {title: "Form Validation", url: "/forms/form-validation", icon: "CheckSquareIcon", is_bookmarked: false},
            {title: "Form Input Group", url: "/forms/form-input-group", icon: "MenuIcon", is_bookmarked: false},

            // PAGES
            {title: "Login Page", url: "/pages/login", icon: "LockIcon", is_bookmarked: false},
            {title: "Register Page", url: "/pages/register", icon: "UserPlusIcon", is_bookmarked: false},
            {
                title: "Forgot Password Page",
                url: "/pages/forgot-password",
                icon: "HelpCircleIcon",
                is_bookmarked: false
            },
            {title: "Reset Password Page", url: "/pages/reset-password", icon: "UnlockIcon", is_bookmarked: false},
            {title: "Lock Screen Page", url: "/pages/lock-screen", icon: "LockIcon", is_bookmarked: false},
            {title: "Coming Soon Page", url: "/pages/comingsoon", icon: "ClockIcon", is_bookmarked: false},
            {title: "404 Page", url: "/pages/error-404", icon: "MonitorIcon", is_bookmarked: false},
            {title: "500 Page", url: "/pages/error-500", icon: "MonitorIcon", is_bookmarked: false},
            {title: "Not Authorized Page", url: "/pages/not-authorized", icon: "XCircleIcon", is_bookmarked: false},
            {title: "Maintenance Page", url: "/pages/maintenance", icon: "MonitorIcon", is_bookmarked: false},
            {title: "Profile Page", url: "/pages/profile", icon: "UserIcon", is_bookmarked: false},
            {title: "User List", url: "/pages/user/user-list", icon: "ListIcon", is_bookmarked: false},
            {title: "User View", url: "/pages/user/user-view/268", icon: "UserIcon", is_bookmarked: false},
            {title: "User Edit", url: "/pages/user/user-edit/268", icon: "EditIcon", is_bookmarked: false},
            {title: "User Settings", url: "/pages/user-settings", icon: "SettingsIcon", is_bookmarked: false},
            {title: "FAQ Page", url: "/pages/faq", icon: "HelpCircleIcon", is_bookmarked: false},
            {title: "KnowledgeBase Page", url: "/pages/knowledge-base", icon: "BookIcon", is_bookmarked: false},
            {title: "Search Page", url: "/pages/search", icon: "SearchIcon", is_bookmarked: false},
            {title: "Invoice Page", url: "/pages/invoice", icon: "FileIcon", is_bookmarked: false},

            // CHARTS & MAPS
            {
                title: "Apex Charts",
                url: "/charts-and-maps/charts/apex-charts",
                icon: "PieChartIcon",
                is_bookmarked: false
            },
            {title: "chartjs", url: "/charts-and-maps/charts/chartjs", icon: "PieChartIcon", is_bookmarked: false},
            {title: "echarts", url: "/charts-and-maps/charts/echarts", icon: "PieChartIcon", is_bookmarked: false},
            {title: "Google Map", url: "/charts-and-maps/maps/google-map", icon: "MapIcon", is_bookmarked: false},

            // EXTENSIONS
            {title: "Select Extension", url: "/extensions/select", icon: "CheckIcon", is_bookmarked: false},
            {title: "Quill Editor", url: "/extensions/quill-editor", icon: "EditIcon", is_bookmarked: false},
            {title: "Drag & Drop", url: "/extensions/drag-and-drop", icon: "CopyIcon", is_bookmarked: false},
            {title: "Datepicker", url: "/extensions/datepicker", icon: "CalendarIcon", is_bookmarked: false},
            {title: "Datetime Picker", url: "/extensions/datetime-picker", icon: "ClockIcon", is_bookmarked: false},
            {title: "Access Control", url: "/extensions/access-control", icon: "LockIcon", is_bookmarked: false},
            {title: "I18n", url: "/extensions/i18n", icon: "GlobeIcon", is_bookmarked: false},
            {title: "Carousel", url: "/extensions/carousel", icon: "LayersIcon", is_bookmarked: false},
            {title: "Clipboard", url: "/extensions/clipboard", icon: "CopyIcon", is_bookmarked: false},
            {title: "Context Menu", url: "/extensions/context-menu", icon: "MoreHorizontalIcon", is_bookmarked: false},
            {title: "Star Rating", url: "/extensions/star-ratings", icon: "StarIcon", is_bookmarked: false},
            {title: "Autocomplete", url: "/extensions/autocomplete", icon: "Edit3Icon", is_bookmarked: false},
            {title: "Tree", url: "/extensions/tree", icon: "GitPullRequestIcon", is_bookmarked: false},
            {title: "Import", url: "/import-export/import", icon: "FileTextIcon", is_bookmarked: false},
            {title: "Export", url: "/import-export/export", icon: "ExternalLinkIcon", is_bookmarked: false},
            {
                title: "Export Selected",
                url: "/import-export/export-selected",
                icon: "ExternalLinkIcon",
                is_bookmarked: false
            },
        ]
    },
    files: {
        key: "file_name",
        data: [
            {file_name: "Joe's CV", from: "Stacy Watson", file_ext: "doc", size: "1.7 mb"},
            {file_name: "Passport Image", from: "Ben Sinitiere", file_ext: "jpg", size: " 52 kb"},
            {file_name: "Questions", from: "Charleen Patti", file_ext: "doc", size: "1.5 gb"},
            {file_name: "Parenting Guide", from: "Doyle Blatteau", file_ext: "doc", size: "2.3 mb"},
            {file_name: "Class Notes", from: "Gwen Greenlow", file_ext: "doc", size: " 30 kb"},
            {file_name: "Class Attendance", from: "Tom Alred", file_ext: "xls", size: "52 mb"},
            {file_name: "Company Salary", from: "Nellie Dezan", file_ext: "xls", size: "29 mb"},
            {file_name: "Company Logo", from: "Steve Sheldon", file_ext: "jpg", size: "1.3 mb"},
            {file_name: "Crime Rates", from: "Sherlock Holmes", file_ext: "xls", size: "37 kb"},
            {file_name: "Ulysses", from: "Theresia Wrenne", file_ext: "pdf", size: "7.2 mb"},
            {file_name: "War and Peace", from: "Goldie Highnote", file_ext: "pdf", size: "10.5 mb"},
            {file_name: "Vedas", from: "Ajay Patel", file_ext: "pdf", size: "8.3 mb"},
            {file_name: "The Trial", from: "Sirena Linkert", file_ext: "pdf", size: "1.5 mb"},
        ]
    },
    contacts: {
        key: "name",
        data: [
            {
                img: require("@assets/images/portrait/small/avatar-s-4.jpg"),
                name: "Rena Brant",
                email: "nephrod@preany.co.uk",
                time: "21/05/2019"
            },
            {
                img: require("@assets/images/portrait/small/avatar-s-2.jpg"),
                name: "Mariano Packard",
                email: "seek@sparaxis.org",
                time: "14/01/2018"
            },
            {
                img: require("@assets/images/portrait/small/avatar-s-24.jpg"),
                name: "Risa Montufar",
                email: "vagary@unblist.org",
                time: "10/08/2019"
            },
            {
                img: require("@assets/images/portrait/small/avatar-s-15.jpg"),
                name: "Maragaret Cimo",
                email: "designed@insanely.net",
                time: "01/12/2019"
            },
            {
                img: require("@assets/images/portrait/small/avatar-s-7.jpg"),
                name: "Jona Prattis",
                email: "unwieldable@unblist.org",
                time: "21/05/2019"
            },
            {
                img: require("@assets/images/portrait/small/avatar-s-5.jpg"),
                name: "Edmond Chicon",
                email: "museist@anaphyte.co.uk",
                time: "15/11/2019"
            },
            {
                img: require("@assets/images/portrait/small/avatar-s-25.jpg"),
                name: "Abbey Darden",
                email: "astema@defectively.co.uk",
                time: "07/05/2019"
            },
            {
                img: require("@assets/images/portrait/small/avatar-s-10.jpg"),
                name: "Seema Moallankamp",
                email: "fernando@storkish.co.uk",
                time: "13/08/2017"
            },
            {
                img: require("@assets/images/portrait/small/avatar-s-2.jpg"),
                name: "Charleen Warmington",
                email: "furphy@cannibal.net",
                time: "11/08/1891"
            },
            {
                img: require("@assets/images/portrait/small/avatar-s-25.jpg"),
                name: "Geri Linch",
                email: "insignia@markab.org",
                time: "18/01/2015"
            },
            {
                img: require("@assets/images/portrait/small/avatar-s-23.jpg"),
                name: "Shellie Muster",
                email: "maxillary@equalize.co.uk",
                time: "26/07/2019"
            },
            {
                img: require("@assets/images/portrait/small/avatar-s-20.jpg"),
                name: "Jesenia Vanbramer",
                email: "hypotony@phonetist.net",
                time: "12/09/2017"
            },
            {
                img: require("@assets/images/portrait/small/avatar-s-23.jpg"),
                name: "Mardell Channey",
                email: "peseta@myrica.com",
                time: "11/11/2019"
            },
        ]
    },
}
