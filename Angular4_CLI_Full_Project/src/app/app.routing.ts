import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

// Import Containers
import {
  FullLayout,
  SimpleLayout
} from './containers';

export const routes: Routes = [
  {
    path: '',
    redirectTo: 'pages',
    pathMatch: 'full',
  },
  {
    path: '',
    component: FullLayout,
    data: {
      title: 'Home'
    },
    children: [
      {
        path: 'dashboard',
        loadChildren: './views/dashboard/dashboard.module#DashboardModule'
        
      },{
        path: 'dashboard_empleados',
        loadChildren: './views/dashboard_empleados/dashboard_empleados.module#Dashboard_empleadosModule'
        
      },
      {
        path: 'components',
        loadChildren: './views/components/components.module#ComponentsModule'
      },
      {
        path: 'icons',
        loadChildren: './views/icons/icons.module#IconsModule'
      },
      {
        path: 'widgets',
        loadChildren: './views/widgets/widgets.module#WidgetsModule'
      },
      {
        path: 'charts',
        loadChildren: './views/chartjs/chartjs.module#ChartJSModule'
      },
      {
        path: 'login',
        loadChildren: './views/pages/pages.module#PagesModule'
      },
      {
        path: 'proveedores',
        loadChildren: './views/proveedores/proveedores.module#ProveedoresModule'
      },
      {
        path: 'usuarios',
        loadChildren: './views/usuarios/usuarios.module#UsuariosModule'
      },
      {
        path: 'pedidos',
        loadChildren: './views/pedidos/pedidos.module#pedidosModule'
      },
      {
        path: 'presupuestos',
        loadChildren: './views/presupuestos/presupuestos.module#presupuestosModule'
      },
      {
        path: 'compras',
        loadChildren: './views/compras/compras.module#comprasModule'
      },
      {
        path: 'inventario',
        loadChildren: './views/inventario/stock.module#stockModule'
      },
      {
        path: 'reportes',
        loadChildren: './views/reportes/reportes.module#reportesModule'
      },
      {
        path: 'departamentos',
        loadChildren: './views/departamentos/departamentos.module#departamentosModule'
      },
      {
        path: 'productos',
        loadChildren: './views/productos/stock.module#stockModule'
      },
      {
        path: 'transferencias',
        loadChildren: './views/transferencias/transferencias.module#transferenciasModule'
      },
      {
        path: 'cc',
        loadChildren: './views/cc/cc.module#ccModule'
      },
      {
        path: 'categorias',
        loadChildren: './views/categorias/categorias.module#CategoriasModule'
      },
      {
        path: 'mensajes',
        loadChildren: './views/mensajes/mensajes.module#MensajesModule'
      }
    ]
  },
  {
    path: 'pages',
    component: SimpleLayout,
    data: {
      title: 'Pages'
    },
    children: [
      {
        path: '',
        loadChildren: './views/pages/pages.module#PagesModule',
      }
    ]
  }
];

@NgModule({
  imports: [ RouterModule.forRoot(routes) ],
  exports: [ RouterModule ]
})
export class AppRoutingModule {}
