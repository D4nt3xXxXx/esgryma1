import app from './main'; // import the instance
import axios from 'axios';
import swal from 'sweetalert2';


export default function setup() {
    // Add a request interceptor
    axios.interceptors.request.use(function (config) {
        app.$Progress.start(); // for every request start the progress
        return config;
    }, function (error) {
        swal.fire({
            title: `Lo sentimos, hubo un error al al tratar de procesar la solicitud`,
            html: '<strong>Descripción del error</strong>: ' + (error.request.response || error.data),
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-danger',
            type: 'error',
            footer: '<div class="container-fluid"><div class="row text-center"><p><strong>Comuníquese con el administrador del sistema, o al correo electrónico </strong> <br><a href="mailto:breyner.jpb@gmail.com?Subject=Error%20detaectado">breyner.jpb@gmail.com</a></p></div></div>'
        })
        app.$Progress.fail();
        app.$vs.loading.close();
        return Promise.reject(error);
    });

// Add a response interceptor
    axios.interceptors.response.use(function (response) {

        app.$Progress.finish(); // finish when a response is received
        return response;
    }, function (error) {
        swal.fire({
            title: `Lo sentimos, hubo un error al al tratar de procesar la solicitud`,
            html: '<strong>Descripción del error</strong>: ' + (error.request.response || error.data),
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-danger',
            type: 'error',
            footer: '<div class="container-fluid"><div class="row text-center"><p><strong>Comuníquese con el administrador del sistema, o al correo electrónico </strong> <br><a href="mailto:breyner.jpb@gmail.com?Subject=Error%20detaectado">breyner.jpb@gmail.com</a></p></div></div>'
        })
        app.$Progress.fail();
        app.$vs.loading.close();
        return Promise.reject(error);
    });
}
