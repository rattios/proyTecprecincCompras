import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { pedidosComponent } from './pedidos.component';
import { misPedidosComponent } from './mis-pedidos.component';
import { todosPedidosComponent } from './todos-pedidos.component';
import { presupuestoComponent } from './presupuesto/presupuesto.component';

import { entrantesComponent } from './entrantes/entrantes.component';
import { encursoComponent } from './encurso/encurso.component';
import { entregadosComponent } from './entregados/entregados.component';
import { canceladosComponent } from './cancelados/cancelados.component';

import { transferenciasPuraComponent } from './transferencias/transferenciasPura.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'Solicitudes'
    },
    children: [
      {
        path: 'pedido',
        component: pedidosComponent,
        data: {
          title: 'Pedido'
        }
      },
      {
        path: 'presupuestos',
        component: presupuestoComponent,
        data: {
          title: 'Presupuesto'
        }
      },
      {
        path: 'entrantes',
        component: entrantesComponent,
        data: {
          title: 'Entrantes'
        }
      },
      {
        path: 'encurso',
        component: encursoComponent,
        data: {
          title: 'En Curso'
        }
      },
      {
        path: 'entregados',
        component: entregadosComponent,
        data: {
          title: 'Entregados'
        }
      },
      {
        path: 'cancelados',
        component: canceladosComponent,
        data: {
          title: 'Cancelados'
        }
      },
      {
        path: 'transferencias_pura',
        component: transferenciasPuraComponent,
        data: {
          title: 'Transferencias Pura'
        }
      },
      {
        path: 'mis-pedidos',
        component: misPedidosComponent,
        data: {
          title: 'Mis Pedidos'
        },
      },
      {
        path: 'todos-pedidos',
        component: todosPedidosComponent,
        data: {
          title: 'Todos los Pedidos'
        }
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class pedidosRoutingModule {}
